<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Interfaces\ResourceInterface;
use App\Interfaces\CommonInterface;
use App\Interfaces\EmailInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery;

class EcrControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $resourceMock;
    protected $commonMock;
    protected $emailMock;

    protected function setUp(): void
    {
        parent::setUp();

    // 1. Create Mocks for your Interfaces
        $this->resourceMock = Mockery::mock(ResourceInterface::class);
        $this->commonMock = Mockery::mock(CommonInterface::class);
        $this->emailMock = Mockery::mock(EmailInterface::class);

        // 2. Bind Mocks to the Laravel Service Container
        $this->app->instance(ResourceInterface::class, $this->resourceMock);
        $this->app->instance(CommonInterface::class, $this->commonMock);
        $this->app->instance(EmailInterface::class, $this->emailMock);

        // 3. Create a real User and log them in to avoid 302 Redirects
        $user = User::factory()->create(['id' => 1]);
        $this->actingAs($user);

        // 4. Set the exact session variables your Controller looks for
        session([
            'rapidx_user_id' => $user->id,
            'rapidx_username' => 'test.user'
        ]);
    }

    /** @test */
    public function it_can_successfully_save_a_new_ecr()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->create('ecr_reference.pdf', 100);

        // Setup Mock Expectations
        $this->commonMock->shouldReceive('uploadFileEcrRequirement')
            ->once()
            ->andReturn([
                'arr_original_filename' => ['ecr_reference.pdf'],
                'arr_filtered_document_name' => ['filtered_123.pdf']
            ]);

        $this->resourceMock->shouldReceive('create')
            ->andReturn(['data_id' => 101]);

        $this->resourceMock->shouldReceive('updateConditions')
            ->atLeast()->once();

        $this->emailMock->shouldReceive('getEmailByRapidxUserId')
            ->andReturn(['email' => 'approver@company.com', 'fullName' => 'Approver Name']);

        $this->emailMock->shouldReceive('ecrEmailMsg')
            ->andReturn('<h1>Email Content</h1>');

        $this->emailMock->shouldReceive('sendEmail')
            ->once();

        $payload = [
            'category' => 'Machine',
            'customer_name' => 'Sample Customer',
            'device_name' => 'Device-A',
            'internal_external' => 'Internal',
            'description_of_change' => ['Change logic'],
            'reason_of_change' => ['Better performance'],
            'requested_by' => [2],
            'technical_evaluation' => [3],
            'reviewed_by' => [4],
            'qad_checked_by' => [5],
            'qad_approved_by_internal' => [6],
            'prepared_by' => [1],
            'checked_by' => [2],
            'approved_by' => [3],
            'ecr_ref' => $file,
            'remarks' => 'Test remark'
        ];

        // Ensure this route exists in web.php or api.php
        $response = $this->postJson('/save_ecr', $payload);

        // If you still get a 302, uncomment the line below to see why:
        // $response->dump();

        $response->assertStatus(200);
        $response->assertJson(['is_success' => 'true']);
    }
}
