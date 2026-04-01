<?php

use App\Models\Ecr;
use App\Models\EcrDetail;
use App\Models\EcrApproval;
use App\Models\User;
use App\Interfaces\ResourceInterface;
use App\Interfaces\CommonInterface;
use App\Interfaces\EmailInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create Mocks for Interfaces
    $this->resourceMock = Mockery::mock(ResourceInterface::class);
    $this->commonMock = Mockery::mock(CommonInterface::class);
    $this->emailMock = Mockery::mock(EmailInterface::class);

    // Bind Mocks to the Laravel Service Container
    $this->app->instance(ResourceInterface::class, $this->resourceMock);
    $this->app->instance(CommonInterface::class, $this->commonMock);
    $this->app->instance(EmailInterface::class, $this->emailMock);

    // Create a real User and log them in
    $user = User::factory()->create(['id' => 1]);
    $this->actingAs($user);

    // Set session variables
    session([
        'rapidx_user_id' => 1,
        'rapidx_username' => 'test.user',
        'rapidx_department_id' => 1,
        'rapidx_employee_number' => 'EMP001',
    ]);
});

afterEach(function () {
    Mockery::close();
});

it('successfully saves a new ECR and triggers emails', function () {
    Storage::fake('public');
    
    // Create a fake file for ecr_ref
    $file = UploadedFile::fake()->create('ecr_reference.pdf', 100);

    // Note: The generateControlNumber() method uses external database connections
    // (mysql_rapidx, mysql_systemone_hris, mysql_systemone_subcon).
    // Ensure these connections are configured in your test environment or mock them.
    // The method requires session('rapidx_department_id') and session('rapidx_employee_number')
    // which are set in beforeEach().
    
    // Setup Mock Expectations for CommonInterface
    $this->commonMock->shouldReceive('uploadFileEcrRequirement')
        ->once()
        ->andReturn([
            'arr_original_filename' => ['ecr_reference.pdf'],
            'arr_filtered_document_name' => ['filtered_ecr_reference_123.pdf']
        ]);

    // Setup Mock Expectations for ResourceInterface
    // First create call is for Ecr
    $this->resourceMock->shouldReceive('create')
        ->with(Ecr::class, Mockery::type('array'))
        ->once()
        ->andReturn(['data_id' => 101]);

    // updateConditions is called for Ecr file update
    $this->resourceMock->shouldReceive('updateConditions')
        ->with(Ecr::class, Mockery::type('array'), Mockery::type('array'))
        ->atLeast()->once();

    // Additional create calls for EcrDetail (one per description_of_change item)
    $this->resourceMock->shouldReceive('create')
        ->with(EcrDetail::class, Mockery::type('array'))
        ->times(2); // Two description_of_change items

    // Setup Mock Expectations for EmailInterface
    $this->emailMock->shouldReceive('getEmailByRapidxUserId')
        ->with(Mockery::type('integer'))
        ->once()
        ->andReturn([
            'email' => 'approver@company.com',
            'fullName' => 'Approver Name'
        ]);

    $this->emailMock->shouldReceive('ecrEmailMsg')
        ->with(Mockery::type('integer'))
        ->once()
        ->andReturn('<h1>ECR Email Content</h1>');

    $this->emailMock->shouldReceive('sendEmail')
        ->with(Mockery::type('array'))
        ->once();

    // Prepare the POST request payload with all required fields
    $payload = [
        'category' => 'Machine',
        'customer_name' => 'Sample Customer',
        'internal_external' => 'Internal',
        'part_no' => 'PART-001',
        'part_name' => 'Sample Part',
        'device_name' => 'Device-A',
        'product_line' => 'Product Line A',
        'section' => 'Section A',
        'customer_ec_no' => 'CUST-EC-001',
        'date_of_request' => now()->format('Y-m-d'),
        'ecr_no' => 'TEST-ECR-001', // Required by EcrRequest validation
        'description_of_change' => ['Change description 1', 'Change description 2'],
        'reason_of_change' => ['Reason 1', 'Reason 2'],
        'requested_by' => [2],
        'technical_evaluation' => [3],
        'reviewed_by' => [4],
        'qad_checked_by' => 5, // Integer as per validation rules
        'qad_approved_by_internal' => 6, // Integer as per validation rules
        'prepared_by' => [1],
        'checked_by' => [2],
        'approved_by' => [3],
        'ecr_ref' => $file,
        'remarks' => 'Test remark'
    ];

    // Make the POST request
    $response = $this->postJson('/api/save_ecr', $payload);

    // Assert response
    $response->assertStatus(200);
    $response->assertJson(['is_success' => 'true']);

    // Assert Ecr table has expected data
    $ecr = Ecr::where('customer_name', 'Sample Customer')->first();
    expect($ecr)->not->toBeNull();
    expect($ecr->category)->toBe('Machine');
    expect($ecr->customer_name)->toBe('Sample Customer');
    expect($ecr->internal_external)->toBe('Internal');
    expect($ecr->device_name)->toBe('Device-A');
    expect($ecr->created_by)->toBe(1);
    expect($ecr->original_filename)->toContain('ecr_reference.pdf');
    expect($ecr->filtered_document_name)->toContain('filtered_ecr_reference');

    // Assert EcrDetail table has expected data
    $ecrDetails = EcrDetail::where('ecrs_id', $ecr->id)->get();
    expect($ecrDetails)->toHaveCount(2);
    expect($ecrDetails->first()->ecrs_id)->toBe($ecr->id);

    // Assert EcrApproval table has expected data
    $ecrApprovals = EcrApproval::where('ecrs_id', $ecr->id)->get();
    expect($ecrApprovals)->not->toBeEmpty();
    
    // Check that at least one approval has status PEN (Pending)
    $pendingApproval = EcrApproval::where('ecrs_id', $ecr->id)
        ->where('status', 'PEN')
        ->first();
    expect($pendingApproval)->not->toBeNull();
    expect($pendingApproval->rapidx_user_id)->not->toBeNull();

    // Assert EmailInterface->sendEmail was called once
    // Note: Mockery verification happens in afterEach via Mockery::close()
    // But we can also verify it was called with the expected structure
    $this->emailMock->shouldHaveReceived('sendEmail')
        ->once()
        ->with(Mockery::on(function ($emailData) {
            return isset($emailData['to']) 
                && isset($emailData['subject'])
                && $emailData['subject'] === 'FOR APPROVAL: Engineering Change Request (ECR)';
        }));
});
