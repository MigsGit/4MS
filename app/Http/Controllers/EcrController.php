<?php

namespace App\Http\Controllers;
use App\Models\Ecr;
use App\Models\EcrDetail;
use App\Models\EcrApproval;
use App\Models\PmiApproval;
use Illuminate\Http\Request;
use App\Models\DropdownMaster;
use App\Models\EcrRequirement;
use App\Http\Requests\EcrRequest;
use Illuminate\Support\Facades\DB;
use App\Interfaces\CommonInterface;
use App\Http\Controllers\Controller;
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
    public function loadEcr(Request $request){
        // return 'true' ;
        try {
            $status = explode(',',$request->status) ?? "";
            $data = [];
            $relations = [
                'ecr_approval_pending'
            ];
            $conditions = [];
            $ecr = $this->resourceInterface->readCustomEloquent(Ecr::class,$data,$relations,$conditions);
            $ecr->whereIn('status',$status)
            // ->whereHas('ecr_approval',function($query) use ($request){
            //     $query->where('status','PEN');
            // })
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
            ->addColumn('get_status',function ($row){
                $currentApprover = $row->ecr_approval_pending['rapidx_user']['name'] ?? '';
                $getStatus = $this->getStatus($row->status);
                $getApprovalStatus = $this->getApprovalStatus($row->approval_status);
                $result = '';
                $result .= '<center>';
                $result .= '<span class="'.$getStatus['bgStatus'].'"> '.$getStatus['status'].' </span>';
                $result .= '<br>';
                $result .= '<span class="badge rounded-pill bg-danger"> '.$getApprovalStatus['approvalStatus'].' '.$currentApprover.' </span>';
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
        // return 'true' ;
        $data = [];
        $relations = [];
        $conditions = [
            'status' => 'OK',
            'category' => $request->category
        ];
        $ecr = $this->resourceInterface->readWithRelationsConditionsActive(Ecr::class,$data,$relations,$conditions);
        return DataTables($ecr)
        ->addColumn('get_actions',function ($row){
            $result = '';
            $result .= '<center>';
            $result .= "<button class='btn btn-outline-info btn-sm mr-1 btn-get-ecr-id' ecr-id='".$row->id."' id='btnGetEcrId'> <i class='fa-solid fa-pen-to-square'></i></button>";
            $result .= '</center>';
            return $result;
            return $result;
        })
        ->rawColumns(['get_actions'])
        ->make(true);
        try {
            return response()->json(['is_success' => 'true']);
        } catch (Exception $e) {
            return response()->json(['is_success' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }
    public function loadEcrDetailsByEcrId(Request $request){
        // return 'true' ;
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
            ->rawColumns([
                'get_actions',
                'reason_of_change',
                'description_of_change',
                'type_of_part',
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
    public function saveEcr(Request $request, EcrRequest $ecrRequest){
        date_default_timezone_set('Asia/Manila');
        try {
            //TODO: EDIT ecr_no, DELETE, Auto Increment Ctrl Number, InsertById, N/A in Dropdown
            DB::beginTransaction();
            $ecrsId = $request->ecrs_id;
            $ecrRequest = $ecrRequest->validated();
            $ecrConditions = [
                'id' => $ecrsId
            ];
            if( isset($ecrsId) ){
                $ecrRequest['status'] = 'IA';
                // return $ecrRequest;
                $this->resourceInterface->updateConditions(Ecr::class,$ecrConditions,$ecrRequest);
                $currenErcId = $ecrsId;
            }else{
                $ecrRequest['created_at'] = now();
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
            DB::commit();
            return response()->json(['is_success' => 'true']);
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
                'QAEX' => $request->qad_approved_by_external,
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
            EcrApproval::where('ecrs_id', $currenErcId)->delete();
            EcrApproval::insert($ecrApprovalRequest);

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
            PmiApproval::where('ecrs_id', $currenErcId)->delete();
            PmiApproval::insert($pmiApprovalRequest);
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
            //Get Current Ecr Approval is equal to Current Session
            $ecrApprovalCurrent = EcrApproval::where('ecrs_id',$request->ecrs_id)->where('status','PEN')->limit(1)->get(['rapidx_user_id']);
            if($ecrApprovalCurrent[0]->rapidx_user_id != session('rapidx_user_id')){
                return response()->json(['isSuccess' => 'false','msg' => 'You are not the current approver !'],500);
            }
            //Get Current Status
            $ecrApproval = Ecr::where('id',$request->ecrs_id)->get(['approval_status']);
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
            $ecrApproval = EcrApproval::where('ecrs_id',$request->ecrs_id)->where('status','-')->limit(1)->get(['id','approval_status']);
            if ( count($ecrApproval) != 0){
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
                $EcrConditions = [
                    'id' => $request->ecrs_id,
                ];
                $ecrValidated = [
                    'status' => 'OK', //APPROVED ECR / ALL APPROVERS APPROVED
                ];
                $this->resourceInterface->updateConditions(Ecr::class,$EcrConditions,$ecrValidated);
            }
            //DISAPPROVED ECR
            if($request->status === "DIS"){
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
   public function saveEcrDetails(Request $request, EcrDetailRequest $ecrDetailRequest){
       date_default_timezone_set('Asia/Manila');
       try {
            $ecrDetailRequestValidated = $ecrDetailRequest->validated();
            $ecrDetailRequestValidated['remarks'] = $request->remarks;
            $conditions = [
                'id' => $request->ecr_details_id
            ];
            $this->resourceInterface->updateConditions(EcrDetail::class,$conditions,$ecrDetailRequestValidated);
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
                case 'QAEX':
                    $approvalStatus = 'QMD External';
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
