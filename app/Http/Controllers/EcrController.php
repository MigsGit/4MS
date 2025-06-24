<?php

namespace App\Http\Controllers;
use App\Models\Ecr;
use App\Models\EcrDetail;
use App\Models\RapidxUser;
use App\Models\EcrApproval;
use App\Models\PmiApproval;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\DropdownMaster;
use App\Models\EcrRequirement;
use App\Http\Requests\EcrRequest;
use Illuminate\Support\Facades\DB;
use App\Interfaces\CommonInterface;
use App\Http\Controllers\Controller;
use App\Models\DropdownMasterDetail;
use App\Interfaces\ResourceInterface;
use App\Http\Requests\EcrDetailRequest;
use App\Models\ClassificationRequirement;

class EcrController extends Controller
{
    protected $resourceInterface;
    protected $commonInterface;
    public function __construct(ResourceInterface $resourceInterface,CommonInterface $commonInterface) {
        $this->resourceInterface = $resourceInterface;
        $this->commonInterface = $commonInterface;
    }
    public function getFilteredSection($department){
        try {
            if ( Str::contains($department, "LQC")) {
                $filteredSection = "LQC";
            } elseif (Str::contains($department, "Engineering")) {
                $filteredSection = "ENG'G";
            } elseif (Str::contains($department, "Production")) {
                $filteredSection = "PROD";
            }elseif (Str::contains($department, "-")) {
                $filteredSection = "LOG-PCH";
            }
            else {
                $filteredSection = "???";
            }
            return $filteredSection;
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function generateControlNumber(){
        date_default_timezone_set('Asia/Manila');
        //Systemon HRIS / Subcon
        $hris_data = DB::connection('mysql_systemone_hris')
        ->select("SELECT Department,Division,Section FROM vw_employeeinfo WHERE EmpNo = '".session('rapidx_employee_number')."'");
        $subcon_data = DB::connection('mysql_systemone_subcon')
        ->select("SELECT Department,Division,Section FROM vw_employeeinfo WHERE EmpNo = '".session('rapidx_employee_number')."'");
        if(count($hris_data) > 0){
            $vwEmployeeinfo =  $hris_data;
            $filteredSection = $this->getFilteredSection($vwEmployeeinfo[0]->Department);
            $division = ($vwEmployeeinfo[0]->Division == "TS-F1" || $vwEmployeeinfo[0]->Division == "TS-F3") ? "TS" :  "???";
        }
        else{
            $vwEmployeeinfo =  $subcon_data;
            $filteredSection = $this->getFilteredSection($vwEmployeeinfo[0]->Department);
            $division = ($vwEmployeeinfo[0]->Division == "TS-F1" || $vwEmployeeinfo[0]->Division == "TS-F3") ? "TS" :  "???";
        }
        // Check if the Created At & App No / Division / Material Category is exisiting
        // Example:TS-ADMIN-LOG-PCH-25-01-001
        $ecr = Ecr::orderBy('id','desc')->whereDate('created_at',now())
            ->whereNull('deleted_at')
            ->limit(1)->get(['ecr_no']);
        //If not exist reset the ecr to 1
        if(count( $ecr ) != 0){
            $currentCtrlNo = explode('-',$ecr[0]->ecr_no);
            $arrCtrNo		 	= end($currentCtrlNo);
            $series 	 	= str_pad(($arrCtrNo+1),3,"0",STR_PAD_LEFT);
            $currentCtrlNo = $division."-".$filteredSection."-".date('m').date('y').'-'.$series;

        }else{
            $currentCtrlNo = $division."-".$filteredSection."-".date('m').date('y').'-001';
        }
        return [
            'currentCtrlNo' => $currentCtrlNo
        ];
    }
    public function saveEcr(Request $request, EcrRequest $ecrRequest){
        date_default_timezone_set('Asia/Manila');
        try {
            //TODO:  DELETE, InsertById, N/A in Dropdown
            DB::beginTransaction();
            $generatedControlNumber =  $this->generateControlNumber();
            $ecrsId = $request->ecrs_id;
            $ecrRequest = $ecrRequest->validated();
            $ecrConditions = [
                'id' => $ecrsId
            ];
            if( isset($ecrsId) ){
                $ecr = Ecr::where('id',$ecrsId)->get(['created_by']);
                // if ( $ecr[0]['created_by'] != session('rapidx_user_id') ){
                //     return response()->json(['isSuccess' => 'false','msg' => "Invalid User ! You cannot update this request "],500);
                // }
                $ecrRequest['status'] = 'IA';
                $ecrRequest['approval_status'] = 'OTRB';
                // $ecrRequest['ecr_no'] = $generatedControlNumber['currentCtrlNo'];
                $this->resourceInterface->updateConditions(Ecr::class,$ecrConditions,$ecrRequest);
                $currenErcId = $ecrsId;
            }else{
                $ecrRequest['created_at'] = now();
                $ecrRequest['ecr_no'] = $generatedControlNumber['currentCtrlNo'];
                $ecr =  $this->resourceInterface->create(Ecr::class,$ecrRequest);
                $currenErcId = $ecr['data_id'];
            }
            $ecrDetailRequest = collect($request->description_of_change)->map(function ($description_of_change,$index) use ($request,$currenErcId){
                return [
                    'ecrs_id' =>  $currenErcId,
                    'description_of_change' => $description_of_change,
                    'reason_of_change' => $request->reason_of_change[$index],
                    'created_at' => now(),
                ];
            });
            EcrDetail::where('ecrs_id', $currenErcId)->delete();
            foreach ($ecrDetailRequest as $ecrDetailRequestValue) {
               $this->resourceInterface->create(EcrDetail::class, $ecrDetailRequestValue);
            }
            //Requested by, Engg, Heads, QA Approval
            $ecrApprovalTypes = [
                'OTRB' => $request->requested_by,
                'OTTE' => $request->technical_evaluation,
                'OTRVB' => $request->reviewed_by,
                'QACB' => $request->qad_checked_by,
                'QAIN' => $request->qad_approved_by_internal,
            ];
            $ecrApprovalRequestCtr = 0; //assigned counter
            $ecrApprovalRequest = collect($ecrApprovalTypes)->flatMap(function ($users,$approval_status) use ($request,&$ecrApprovalRequestCtr,$currenErcId){
                    return collect($users)->map(function ($userId) use ($request,$approval_status,&$ecrApprovalRequestCtr,$currenErcId){
                        return [
                            'ecrs_id' =>  $currenErcId,
                            'rapidx_user_id' => $userId,
                            'approval_status' => $approval_status,
                            'counter' => $ecrApprovalRequestCtr++,
                            'remarks' => $request->remarks,
                            'created_at' => now(),
                        ];
                    });

            })->toArray();
            //Delete previous ecr approval,save,update status to Pending
            EcrApproval::where('ecrs_id', $currenErcId)->delete();
            EcrApproval::insert($ecrApprovalRequest);
            EcrApproval::where('counter', 0)
            ->where('ecrs_id', $currenErcId)
            ->update(['status'=>'PEN']);
            //PMI Approvers
            $approval_statuss = [
                'PB' => $request->prepared_by,
                'CB' => $request->checked_by,
                'AB' => $request->approved_by,
            ];
            $pmiApprovalRequestCtr = 0;
            $pmiApprovalRequest = collect($approval_statuss)->flatMap(function ($users,$approval_status) use ($request,&$pmiApprovalRequestCtr,$currenErcId){
                //return array users id as array value
                return collect($users)->map(function ($userId) use ($approval_status, $request,&$pmiApprovalRequestCtr,$currenErcId) {
                    // $approval_status as a array name
                    //return array users id, defined type by use keyword,
                    return [
                        'ecrs_id' => $currenErcId,
                        'rapidx_user_id' => $userId,
                        'approval_status' => $approval_status,
                        'counter' => $pmiApprovalRequestCtr++,
                        'remarks' => $request->remarks,
                        'created_at' => now(),
                    ];
                });
            })->toArray();
            //Save PMI Internal Approval
            PmiApproval::where('ecrs_id', $currenErcId)->delete();
            PmiApproval::insert($pmiApprovalRequest);
            PmiApproval::where('counter', 0)
            ->where('ecrs_id', $currenErcId)
            ->update(['status'=>'PEN']);
            DB::commit();
            return response()->json(['is_success' => 'true']);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
    public function saveEcrApproval(Request $request){
        try {
            date_default_timezone_set('Asia/Manila');
            DB::beginTransaction();
            $ecrsId = $request->ecrs_id;

            //Get Current Ecr Approval is equal to Current Session
            $ecrApprovalCurrent = EcrApproval::where('ecrs_id',$ecrsId)->where('status','PEN')->limit(1)->get(['rapidx_user_id','approval_status']);
            if($ecrApprovalCurrent[0]->rapidx_user_id != session('rapidx_user_id')){
                return response()->json(['isSuccess' => 'false','msg' => 'You are not the current approver !'],500);
            }

            //Get Current Status
            $ecrApproval = Ecr::where('id',$ecrsId)->get(['approval_status','status']);
            //Verify if the ECR Requirement is Completed.
            $isCompletedEcrRequirementComplete = $this->isCompletedEcrRequirementComplete($ecrsId);
            if($ecrApproval[0]->status === 'QA'){
                if(  $isCompletedEcrRequirementComplete === 'false' && $request->status === 'APP'){
                    return response()->json(['isSuccess' => 'false','msg' => 'Incomplete details, Please fill up the ECR Requirement!'],500);
                }
            }
            //Update the ECR Approval Status
            $ecrApprovalValidated = [
                'status' => $request->status,
                'remarks' => $request->remarks,
            ];
            $ecrApprovalConditions = [
                'ecrs_id' => $request->ecrs_id,
                'approval_status' => $ecrApproval[0]->approval_status,
                'rapidx_user_id' => session('rapidx_user_id'), //Double check the rapidx user id to update status
            ];
            $this->resourceInterface->updateConditions(EcrApproval::class,$ecrApprovalConditions,$ecrApprovalValidated);

            //Get the ECR Approval Status & Id, Update the Approval Status as PENDING
            $ecrApproval = EcrApproval::where('ecrs_id',$ecrsId)->where('status','-')->limit(1)->get(['id','approval_status']);

            if ( count($ecrApproval) === 0){
                $EcrConditions = [
                    'id' => $request->ecrs_id,
                ];
                $ecrValidated = [
                    'status' => 'OK', //APPROVED ECR / ALL APPROVERS APPROVED
                ];
                $this->resourceInterface->updateConditions(Ecr::class,$EcrConditions,$ecrValidated);
            }
            if ( count($ecrApproval) != 0   && $request->status === "APP" ){
                $ecrApprovalValidated = [
                    'status' => 'PEN',
                ];
                $ecrApprovalConditions = [
                    'id' => $ecrApproval[0]->id,
                ];
                $this->resourceInterface->updateConditions(EcrApproval::class,$ecrApprovalConditions,$ecrApprovalValidated);
                //Update the ECR Approval Status
                $EcrConditions = [
                    'id' => $request->ecrs_id,
                ];
                $ecrValidated = [
                    'approval_status' => $ecrApproval[0]->approval_status,
                ];
                //Change QA Status
                if (str_contains($ecrApproval[0]->approval_status, 'QA')) {
                    $ecrValidated = [
                        'approval_status' => $ecrApproval[0]->approval_status,
                        'status' => 'QA',
                    ];
                }
                $this->resourceInterface->updateConditions(Ecr::class,$EcrConditions,$ecrValidated);
            }else{
                //DISAPPROVED ECR
                $EcrConditions = [
                    'id' => $request->ecrs_id,
                ];
                $ecrValidated = [
                    'status' => 'DIS',
                ];
                $this->resourceInterface->updateConditions(Ecr::class,$EcrConditions,$ecrValidated);
            }
            DB::commit();
            return response()->json(['is_success' => 'true']);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
    public function isCompletedEcrRequirementComplete($ecrsId){ //Requirement
        $classificationRequirementCount =  ClassificationRequirement::whereIn('classifications_id',[1,2,3,4,5])->count();
        $ecrRequirementCount = EcrRequirement::where('ecrs_id',$ecrsId)->count();
        return $classificationRequirementCount === $ecrRequirementCount ? 'true' : 'false';
    }
    public function saveEcrDetails(Request $request, EcrDetailRequest $ecrDetailRequest){
        date_default_timezone_set('Asia/Manila');
        try {
             $ecrDetailRequestValidated = $ecrDetailRequest->validated();
             $ecrDetailRequestValidated['customer_approval'] = $request->customer_approval ?? NULL;
             $ecrDetailRequestValidated['remarks'] = $request->remarks;
             $conditions = [
                 'id' => $request->ecr_details_id
             ];
             // return $ecrDetailRequestValidated;
             $this->resourceInterface->updateConditions(EcrDetail::class,$conditions,$ecrDetailRequestValidated);
             return response()->json(['is_success' => 'true']);
        } catch (Exception $e) {
             throw $e;
        }
    }
    public function loadEcr(Request $request){
        try {
            $status = explode(',',$request->status) ?? "";
            $data = [];

            $relations = [
                'ecr_approval_pending'
            ];
            $conditions = [];
            $ecr = $this->resourceInterface->readCustomEloquent(Ecr::class,$data,$relations,$conditions);
            $ecr->whereIn('status',$status)
            ->whereHas('ecr_approval',function($query) use ($request){
                // if is adminAccess exist deactivate the session condition
                if( $request->adminAccess != 'all' && $request->status === 'IA'){
                    // $query->where('status','PEN');
                    $query->where('rapidx_user_id',session('rapidx_user_id'));
                }
                if( $request->adminAccess != 'all' && $request->status === 'QA'){
                    $query->where('status','PEN');
                    $query->where('rapidx_user_id',session('rapidx_user_id'));
                }

            })
            ->get();
            return DataTables($ecr)
            ->addColumn('get_actions',function ($row){
                $result = '';
                $result .= '<center>';
                $result .= "<button class='btn btn-outline-info btn-sm mr-1 btn-get-ecr-id' ecr-id='".$row->id."' id='btnGetEcrId'> <i class='fa-solid fa-pen-to-square'></i></button>";
                $result .= '<br>';
                $result .= '<br>';
                $result .= "<button class='btn btn-outline-primary btn-sm mr-1 btn-get-ecr-id' ecr-id='".$row->id."' id='btnViewEcrId'> <i class='fa-solid fa-eye'></i></button>";
                $result .= '</br>';
                return $result;
            })
            ->addColumn('get_status',function ($row): string{
                $currentApprover = $row->ecr_approval_pending['rapidx_user']['name'] ?? '';

                $getStatus = $this->getStatus($row->status);
                $getApprovalStatus = $this->getApprovalStatus($row->approval_status);
                $result = '';
                $result .= '<center>';
                $result .= '<span class="'.$getStatus['bgStatus'].'"> '.$getStatus['status'].' </span>';
                $result .= '<br>';
                if($row->status != 'DIS'){
                    $result .= '<span class="badge rounded-pill bg-danger"> '.$getApprovalStatus['approvalStatus'].' '.$currentApprover.' </span>';
                }
                $result .= '</center>';
                $result .= '</br>';
                return $result;
            })
            ->rawColumns(['get_actions','get_status'])
            ->make(true);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function loadEcrApprovalSummary(Request $request){
        try {
            $ecrsId = $request->ecrsId ?? "";
            $data = [];
            $relations = [
                'rapidx_user'
            ];
            $conditions = [
                'ecrs_id' => $ecrsId
            ];

            $ecr = $this->resourceInterface->readCustomEloquent(EcrApproval::class,$data,$relations,$conditions);
            $ecr = $ecr->orderBy('counter','asc')->get();
            return DataTables($ecr)
            ->addColumn('get_count',function ($row) use(&$ctr){
                $ctr++;
                $result = '';
                $result .= $ctr;
                $result .= '</br>';
                return $result;
            })
            ->addColumn('get_approver_name',function ($row){
                $result = '';
                $result .= $row->rapidx_user['name'];
                $result .= '</br>';
                return $result;
            })
            ->addColumn('get_role',function ($row){
                $getApprovalStatus = $this->getApprovalStatus($row->approval_status);
                $result = '';
                $result .= '<center>';
                $result .= '<span class="badge rounded-pill bg-primary"> '.$getApprovalStatus['approvalStatus'].' </span>';
                $result .= '<center>';
                $result .= '</br>';
                return $result;
            })
            ->addColumn('get_status',function ($row){
                switch ($row->status) {

                    case 'PEN':
                        $status = 'PENDING';
                        $bgColor = 'badge rounded-pill bg-warning';
                        break;
                    case 'APP':
                        $status = 'APPROVED';
                        $bgColor = 'badge rounded-pill bg-success';
                        break;
                    case 'DIS':
                        $status = 'DISAPPROVED';
                        $bgColor = 'badge rounded-pill bg-danger';
                        break;
                    default:
                        $status = '---';
                        $bgColor = '';
                        break;
                }

                $result = '';
                $result .= '<center>';
                $result .= '<span class="'.$bgColor.'"> '.$status.' </span>';
                $result .= '<br>';
                $result .= '</br>';
                return $result;
            })
            ->rawColumns(['get_count','get_status','get_approver_name','get_role'])
            ->make(true);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function loadEcrByStatus(Request $request){
        $data = [];
        $relations = [
            'pmi_approvals_pending.rapidx_user',
            'environment',
        ];
        $conditions = [
            'status' => 'OK',
            'category' => $request->category
        ];
        $ecr = $this->resourceInterface->readWithRelationsConditionsActive(Ecr::class,$data,$relations,$conditions);
        return DataTables($ecr)
        ->addColumn('get_actions',function ($row) use ($request){
            $result = '';
            $result .= '<center>';
            $result .= "<button class='btn btn-outline-info btn-sm mr-1 btn-get-ecr-id' ecr-id='".$row->id."' id='btnGetEcrId'> <i class='fa-solid fa-pen-to-square'></i></button>";
            if($request->category === 'Environment'){
                $result .= '</br>';
                $result .= '</br>';
                $result .= "<button class='btn btn-outline-danger btn-sm mr-1 btn-get-ecr-id' ecr-id='".$row->id."' id='btnDownloadEnvironmentRef'> <i class='fa-solid fa-upload'></i></button>";
            }
            $result .= '</center>';
            return $result;
            return $result;
        })
        ->addColumn('get_status',function ($row) use($request){
            $currentApprover = $row->pmi_approvals_pending[0]['rapidx_user']['name'] ?? '';
            $result = '';
            $result .= '<center>';
            // $result .= '<span class="'.$getStatus['bgStatus'].'"> '.$getStatus['status'].' </span>';
            $result .= '<br>';
            $result .= '<span class="badge rounded-pill bg-danger"> '.$row->approval_status.' '.$currentApprover.' </span>';
            $result .= '</br>';
            return $result;
        })
        ->addColumn('get_attachment',function ($row) use ($request){
            $result = '';
            $result .= '<center>';
            if($request->category  === 'Environment'){
                $result .= "<a class='btn btn-outline-danger btn-sm mr-1 btn-get-ecr-id' ecr-id='".$row->id."' id='btnViewEnvironmentRef'> View Attachment</a>";
            }
            $result .= '</center>';
            return $result;
            return $result;
        })
        ->rawColumns([
            'get_actions',
            'get_status',
            'get_attachment',
        ])
        ->make(true);
        try {
            return response()->json(['is_success' => 'true']);
        } catch (Exception $e) {
            return response()->json(['is_success' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }
    public function loadEcrDetailsByEcrId(Request $request){
        try {
            $data = [];
            $relations = [
                'dropdown_master_detail_description_of_change',
                'dropdown_master_detail_reason_of_change',
                'dropdown_master_detail_type_of_part',
            ];
            $conditions = [
                'ecrs_id' => $request->ecr_id
            ];
            $ecrDetail = $this->resourceInterface->readWithRelationsConditionsActive(EcrDetail::class,$data,$relations,$conditions);
            return DataTables($ecrDetail)
            ->addColumn('get_actions',function ($row){
                $result = '';
                $result .= '<center>';
                $result .= "<button class='btn btn-outline-info btn-sm mr-1 btn-get-ecr-id' ecr-details-id='".$row->id."' id='btnGetEcrDetailsId'> <i class='fa-solid fa-pen-to-square'></i></button>";
                $result .= '</center>';
                return $result;
            })
            ->addColumn('reason_of_change',function ($row){
                $result = '';
                $result .= $row->dropdown_master_detail_reason_of_change->dropdown_masters_details ?? '';
                return $result;
            })
            ->addColumn('description_of_change',function ($row){
                $result = '';
                $result .= $row->dropdown_master_detail_description_of_change->dropdown_masters_details ?? '';
                return $result;
            })
            ->addColumn('type_of_part',function ($row){
                $result = '';
                $result .= $row->dropdown_master_detail_type_of_part->dropdown_masters_details ?? '';
                return $result;
            })
            ->addColumn('get_customer_approval',function ($row){
                $result = '';
                $result .= $row->customer_approval;
                $result = '';
                switch ($row->customer_approval) {
                    case 'R':
                        $bgColor = 'bg-success text-white';
                        $customerApproval = 'REQUIRED';
                        break;
                    case 'NR':
                        $bgColor = 'bg-warning text-white';
                        $customerApproval = 'NOT REQUIRED';
                        break;
                    default:
                        $bgColor = 'bg-secondary text-white';
                        $customerApproval = 'N/A';
                        break;
                }
                $result .='<span class="badge '.$bgColor.'"> '.$customerApproval.' </span>';
                return $result;
            })
            ->rawColumns([
                'get_actions',
                'reason_of_change',
                'description_of_change',
                'type_of_part',
                'get_customer_approval',
            ])
            ->make(true);
        } catch (Exception $e) {
            return response()->json(['is_success' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }
    public function loadEcrRequirements(Request $request){
        try {
            $data = [];
            $relations = [];
            $conditions = [
                'classifications_id' => $request->category
            ];
            $ecr_req_data = [];
            $ecr_req_relations = [];
            $ecr_req_conditions = [
                'ecrs_id' => $request->ecrsId ?? "",
            ];
            $classificationRequirement = $this->resourceInterface->readWithRelationsConditionsActive(ClassificationRequirement::class,$data,$relations,$conditions);
            $ecrRequirement = $this->resourceInterface->readWithRelationsConditionsActive(EcrRequirement::class,$ecr_req_data,$ecr_req_relations,$ecr_req_conditions);
            return DataTables($classificationRequirement)
            ->addColumn('get_actions',function ($row) use($ecrRequirement) {
                $ecrRequirementCollection = collect($ecrRequirement);
                $ecrRequirementMatch = $ecrRequirementCollection->firstWhere('classification_requirements_id', $row->id);
                $ecrRequirementId = $ecrRequirementMatch['id'] ?? '';
                $cSelected = $ecrRequirementMatch['decision'] === 'C' ? 'selected' : '';
                $xSelected = $ecrRequirementMatch['decision'] === 'X' ? 'selected' : '';
                $naSelected = $ecrRequirementMatch['decision'] === 'N/A' ? 'selected' : '';
                if($ecrRequirementId === ''){
                    $isValid = "is-invalid";
                    $emptySelected = "selected";
                }else{
                    $isValid = "";
                    $emptySelected = "";
                }
                $result = '';
                $result .= '<center>';
                $result .= "<select id='btnChangeEcrReqDecision' class='form-select btn-change-ecr-req-decision ".$isValid."' ref=btnChangeEcrReqDecision ecr-requirements-id ='".$ecrRequirementId."' classification-requirement-id='".$row->id."'>";
                $result .=  "<option value='' ".$emptySelected." disabled> --Select-- </option>";
                $result .=  "<option value='N/A' ".$naSelected."> N/A </option>";
                $result .=  "<option value='C' ".$cSelected."> √ </option>";
                $result .=  "<option value='X' ".$xSelected."> X </option>";
                $result .=  "</select>";
                $result .= '</center>';
                return $result;
            })
            ->rawColumns([
                'get_actions',
            ])
            ->make(true);
        } catch (Exception $e) {
            return response()->json(['is_success' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }
    public function getDropdownMasterByOpt(Request $request){
        try {
            $data = [];

            $relations = [
                'dropdown_master_details'
            ];

            $conditions = [
                'table_reference' => $request->tblReference,
                'deleted_at' => NULL,
            ];

            $dropdownMasterByOpt = $this->resourceInterface->readWithRelationsConditions(DropdownMaster::class,$data,$relations,$conditions);
            $dropdownMasterByOpt = $dropdownMasterByOpt[0]->dropdown_master_details;
           return response()->json(['is_success' => 'true','dropdownMasterByOpt' => $dropdownMasterByOpt]);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function getEcrById(Request $request){
        try {
            // return 'true' ;
            $data = [];
            $relations = [
                'ecr_details',
                'ecr_approvals',
                'pmi_approvals',

            ];
            $conditions = [
                'id' => $request->ecr_id
            ];

            $ecr = $this->resourceInterface->readWithRelationsConditionsActive(Ecr::class,$data,$relations,$conditions);
            $ecrApprovalCollection = collect($ecr[0]->ecr_approvals)->groupBy('approval_status')->toArray();
            $pmiApprovalCollection = collect($ecr[0]->pmi_approvals)->groupBy('approval_status')->toArray();
            return response()->json(['is_success' => 'true', 'ecr' => $ecr[0] ,
                'ecrApprovalCollection' => $ecrApprovalCollection,
                'pmiApprovalCollection'=>$pmiApprovalCollection
            ]);
        } catch (Exception $e) {
            return response()->json(['is_success' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }
    public function getEcrDetailsId(Request $request){
        try {
            $data = [];
            $conditions = [
                'id' => $request->ecrDetailsId
            ];

            $relations = [
                'dropdown_master_detail_description_of_change',
                'dropdown_master_detail_reason_of_change',
                'dropdown_master_detail_type_of_part',
            ];
            $ecrDetail = $this->resourceInterface->readWithRelationsConditionsActive(EcrDetail::class,$data,$relations,$conditions);
            return response()->json(['is_success' => 'true', 'ecrDetail' => $ecrDetail[0] ,
            ]);
        } catch (Exception $e) {
            return response()->json(['is_success' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }
    public function ecrReqDecisionChange(Request $request){
        try {
            if( isset($request->ecr_req_id) ){ //edit
                $conditions = [
                    'id' => $request->ecr_req_id
                ];
                $data = [
                    'classification_requirements_id' => $request->classification_requirement_id,
                    'decision' => $request->ecr_req_value,
                ];

                $this->resourceInterface->updateConditions(EcrRequirement::class,$conditions,$data);
            }else{ //add
                $data = [
                    'classification_requirements_id' => $request->classification_requirement_id,
                    'decision' => $request->ecr_req_value,
                    'ecrs_id' => $request->ecrsId,
                ];
                $this->resourceInterface->create(EcrRequirement::class,$data);
            }

            return response()->json(['is_success' => 'true']);
        } catch (Exception $e) {

            throw $e;
        }
    }
   public function getStatus($status){

       try {
            switch ($status) {
                case 'IA':
                    $status = 'Internal Approval';
                    $bgStatus = 'badge rounded-pill bg-primary';
                    break;
                case 'QA':
                    $status = 'QA Approval';
                    $bgStatus = 'badge rounded-pill bg-warning';
                    break;
                case 'DIS':
                    $status = 'DISAPPROVED';
                    $bgStatus = 'badge rounded-pill bg-danger';
                    break;
                default:
                    $status = '';
                    $bgStatus = '';
                    break;
            }
            return [
                'status' => $status,
                'bgStatus' => $bgStatus,
            ];
       } catch (Exception $e) {
           throw $e;
       }
   }
   public function getApprovalStatus($approval_status){
       try {
            switch ($approval_status) {
                case 'OTRB':
                    $approvalStatus = 'Requested by:';
                    break;
                case 'OTTE':
                    $approvalStatus = 'Technical Engg:';
                    break;
                case 'OTRVB':
                    $approvalStatus = 'Reviewed By:';
                    break;
                case 'QACB':
                    $approvalStatus = 'QA Engineer';
                    break;
                case 'QAIN':
                    $approvalStatus = 'QA Manager';
                    break;
                default:
                    $approvalStatus = '';
                    break;
            }
            return [
                'approvalStatus' => $approvalStatus,
            ];
       } catch (Exception $e) {
           throw $e;
       }
   }
   public function getPmiApprovalStatus($approval_status){
       try {
            switch ($approval_status) {
                case 'PB':
                    $approvalStatus = 'Prepared by:';
                    break;
                case 'CB':
                    $approvalStatus = 'Checked by:';
                    break;
                case 'AP':
                    $approvalStatus = 'Approved by:';
                    break;
                default:
                    $approvalStatus = '';
                    break;
            }
            return [
                'approvalStatus' => $approvalStatus,
            ];
       } catch (Exception $e) {
           throw $e;
       }
   }
}
