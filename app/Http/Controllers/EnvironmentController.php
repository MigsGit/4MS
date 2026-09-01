<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\EnvironmentRequest;
use App\Interfaces\CommonInterface;
use App\Interfaces\EmailInterface;
use App\Interfaces\ResourceInterface;
use App\Models\Ecr;
use App\Models\Environment;
use App\Models\RapidxUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnvironmentController extends Controller
{
    protected $resourceInterface;
    protected $commonInterface;
    protected $emailInterface;
    public function __construct(
        ResourceInterface $resourceInterface,
        CommonInterface $commonInterface,
        EmailInterface $emailInterface
    ) {
        $this->resourceInterface = $resourceInterface;
        $this->commonInterface = $commonInterface;
        $this->emailInterface = $emailInterface;
    }
    public function loadEcrEnvironmentByStatus(Request $request){
        try {
            $adminAccess = $request->adminAccess;
            $data = [];
             $statusArray = [];
            if ($request->filled('status')) {
                if (is_array($request->status)) {
                    $statusArray = $request->status;
                } else {
                    $statusArray = array_filter(explode(',', $request->status));
                }
            }
            $relations = [
                'pmi_approvals_pending',
                'environment',
            ];
            $conditions = [
                'status' => 'OK',
                'category' => $request->category
            ];
            $ecr = $this->resourceInterface->readCustomEloquent(Ecr::class,$data,$relations,$conditions);
            if (!empty($statusArray)){
                $statusArray1 = $statusArray[0];

                if($statusArray1 != 'OK' && $statusArray1 != 'DIS' && $statusArray1 != 'CAN' ){
                    $statusArray = ['FORAPP','PB','RUP',];
                }
                $ecr->whereHas('environment',function($query) use ($statusArray){
                    // if is adminAccess exist deactivate the session condition
                    $query->whereIn('status', $statusArray);
                });
            }

            // if( $adminAccess === 'null' || blank($adminAccess) || $adminAccess === 'pmi' ){
            //If the Man Approval is OK / Zero, PMI Approvals Pending displayed
            if ( $adminAccess === 'pmi' || $ecr->count() === 0) {
                $ecr->whereHas('pmi_approvals_pending', function ($query) {
                    $query->where('rapidx_user_id',session('rapidx_user_id'));
                });
            }
            if( $adminAccess === 'created'){
                $ecr->where('created_by' , session('rapidx_user_id'));
            }
            if( $adminAccess === 'all') {
                $ecr;
            }
            $ecr->whereNull('deleted_at');
            return DataTables($ecr)
            ->addColumn('get_actions',function ($row) use ($request){
                $result = "";
                $approvalStatus = $row->approval_status;
                $statusEnvironment = $row->environment->status;
                $pmiApprovalsPending = $row->pmi_approvals_pending[0]->rapidx_user->id ?? '';
                // return     $test = $statusEnvironment;
                $result .= '<center>';
                $result .= '<div class="btn-group dropstart mt-4">';
                $result .= '<button type="button" class="btn btn-secondary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-expanded="false">';
                $result .= '    Action';
                $result .= '</button>';
                $result .= '<ul class="dropdown-menu">';
                if($statusEnvironment === 'EXDISPO' || $statusEnvironment === 'EXDISAPP' || $statusEnvironment === 'OK'){
                    //Upload External Disposition
                    $result .= '<li><button class="dropdown-item" type="button" ecrs-id="'.$row->id.'" id="btnSaveDisposition"><i class="fa-solid fa-edit"></i> &nbsp;Add/Edit Disposition</button></li>';
                    $result .= '<li><button class="dropdown-item" type="button" ecr-id="'.$row->id.'" id="btnViewEcrById"><i class="fa-solid fa-eye"></i> &nbsp;View/Approval</button></li>';
                    return $result;
                }

                if($approvalStatus === "PB"  || $statusEnvironment === "DIS" && $row->created_by === session('rapidx_user_id')){
                    $result .= '   <li><button class="dropdown-item" type="button" ecr-id="'.$row->id.'" id="btnGetEcrId"><i class="fa-solid fa-edit"></i> &nbsp;Edit</button></li>';
                    $result .= '   <li><button class="dropdown-item" type="button" ecr-id="'.$row->id.'" id="btnDownloadEnvironmentRef"><i class="fa-solid fa-upload"></i> &nbsp;Upload File</button></li>';
                }

                // if($row->pmi_approvals_pending[0]->rapidx_user->id === session('rapidx_user_id')){
                if($pmiApprovalsPending === session('rapidx_user_id') || session('rapidx_department_id') === 22 || session('rapidx_department_id') === 1 || $row->created_by === session('rapidx_user_id') ){
                    $result .= '   <li><button class="dropdown-item" type="button" ecr-id="'.$row->id.'" id="btnViewEcrById"><i class="fa-solid fa-eye"></i> &nbsp;View/Approval</button></li>';
                }

                $result .= '</ul>';
                $result .= '</div>';
                $result .= '</center>';
                return $result;
            })
            ->addColumn('get_status',function ($row) use($request){
                $result = '';
                $currentApprover = $row->pmi_approvals_pending[0]['rapidx_user']['name'] ?? '';
                $approvalStatusEnvironment = $row->environment->approval_status;
                $statusEnvironment = $row->environment->status;
                $getStatus = $this->commonInterface->getStatus4m($statusEnvironment);
                $getApprovalStatus = $this->commonInterface->getPmiApprovalStatus($approvalStatusEnvironment);
                $result .= '<center>';
                $result .= '<span class="'.$getStatus['bgStatus'].'"> '.$getStatus['status'].' </span>';
                $result .= '<br>';
                if($currentApprover != ''){
                    $result .= '<span class="badge rounded-pill bg-danger"> '.$getApprovalStatus['approvalStatus'].' '.$currentApprover.' </span>';
                }
                $result .= '</center>';
                $result .= '</br>';
                return $result;
            })
            ->addColumn('get_details',function ($row) use($request){
                $result = '';

                $date = Carbon::parse($row->environment->created_at); //String to Object Date conversion

                // Number of working days to add
                $daysToAdd = 14;

                while ($daysToAdd > 0) {
                    $date->addDay(); // add one day at a time
                    if ($date->isWeekday()) { // exclude Saturday & Sunday
                        $daysToAdd--;
                    }
                }

                $result .= '<p class="card-text"><strong>'. $row->internal_external.'</strong></p>';
                $result .= '<p class="card-text"><strong>Customer Name:</strong> ' . $row->customer_name . '</p>';
                $result .= '<p class="card-text"><strong>Part Number:</strong> ' . $row->part_no . '</p>';
                $result .= '<p class="card-text"><strong>Part Name:</strong> ' . $row->part_name . '</p>';
                $result .= '<p class="card-text"><strong>Device Code:</strong> ' . $row->device_name . '</p>';
                $result .= '<p class="card-text"><strong>Product Line:</strong> ' . $row->product_line . '</p>';
                $result .= '<p class="card-text"><strong>Date of Request:</strong> ' . $row->date_of_request . '</p>';
                $result .= '<p class="card-text"><strong>Target Completion:</strong> ' .$date->toDateString(). '</p>';
                $result .= '<p class="card-text"><strong>Created By:</strong> ' . $row->rapidx_user_created_by->name ?? '' . '</p>';
                return $result;
            })
            ->addColumn('get_attachment',function ($row) use ($request){
                $result = '';
                $result .= '<center>';
                $result .= "<a class='btn btn-outline-danger btn-sm mr-1 mt-3 btn-get-ecr-id' ecr-id='".$row->id."' encrypted-ecr-id='".encrypt($row->id)."' id='btnViewEnvironmentRef'><i class='fa-solid fa-file-pdf'></i></a>";
                $result .= '</center>';
                return $result;
            })
            ->addColumn('created_by', function ($row) {
                // Keeping your code exactly as you asked
                $rapidx = RapidxUser::where('id', $row->created_by)->first();
                return '<center><p>' . ($rapidx->name ?? '') . '</p></center>';
            })
            ->filterColumn('created_by', function($query, $keyword) {
                // 1. Go to the RapidX database and find all User IDs that match the name
                $userIds = RapidxUser::where('name', 'like', "%{$keyword}%")
                    ->pluck('id') // Get just the IDs (e.g., [1, 5, 12])
                    ->toArray();

                // 2. Tell the main query to only show rows where 'created_by' is in that list
                $query->whereIn('created_by', $userIds);
            })
            ->rawColumns([
                'created_by',
                'get_actions',
                'get_status',
                'get_attachment',
                'get_details',
            ])
            ->make(true);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function uploadEnvironmentRef(EnvironmentRequest $environmentRequest){
        date_default_timezone_set('Asia/Manila');
        DB::beginTransaction();
        try {
            if($environmentRequest->hasfile('environment_ref') ){
                $arrUploadFile = $this->commonInterface->uploadFile($environmentRequest->environment_ref,$environmentRequest->ecrsId,'environment');
                $impOriginalFilename = implode(' | ',$arrUploadFile['arr_original_filename']);
                $impFilteredDocumentName = implode(' | ',$arrUploadFile['arr_filtered_document_name']);

                $conditions = [
                   'ecrs_id' =>  $environmentRequest->ecrs_id
                ];
                $environmentRequestValidated['original_filename'] = $impOriginalFilename;
                $environmentRequestValidated['filtered_document_name'] = $impFilteredDocumentName;
                $this->resourceInterface->updateConditions(Environment::class,$conditions,$environmentRequestValidated);
            }
            DB::commit();
            return response()->json(['is_success' => 'true']);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
    public function viewEnvironmentRef(Request $request){
        try {
            $ecrsId = decrypt($request->ecrsId);
            $conditions = [
                'ecrs_id' => $ecrsId,
            ];
            $data = $this->resourceInterface->readCustomEloquent(Environment::class,[],[],$conditions);
            $environmentRefByEcrsId = $data
            ->get([
                'filtered_document_name',
                'file_path',
            ]);
            if(count($environmentRefByEcrsId) != 0){
                $arrFilteredDocumentName = explode(' | ' ,$environmentRefByEcrsId[0]->filtered_document_name);
                $selectedFilteredDocumentName =  $arrFilteredDocumentName[$request->index];
                $filePathWithEcrsId = $environmentRefByEcrsId[0]->file_path."/".$ecrsId."/".$selectedFilteredDocumentName;
                $pdfPath = storage_path("app/public/".$filePathWithEcrsId."");
                $this->commonInterface->viewPdfFile($pdfPath);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function getEnvironmentRefByEcrsId(Request $request){
        try {
            $ecrsId = $request->ecrsId;
            $conditions = [
                'ecrs_id' => $ecrsId,
            ];
            $data = $this->resourceInterface->readCustomEloquent(Environment::class,[],[],$conditions);
            $environmentRefByEcrsId = $data
            ->get([
                'id',
                'ecrs_id',
                'original_filename',
            ]);
            return response()->json([
                'isSuccess' => 'true',
                'originalFilename'=> explode(' | ',$environmentRefByEcrsId[0]->original_filename),
                'ecrsId'=> encrypt($environmentRefByEcrsId[0]->ecrs_id),
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
