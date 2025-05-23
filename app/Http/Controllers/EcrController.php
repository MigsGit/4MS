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
            $status = $request->status ?? "";
            $data = [];
            $relations = [];
            $conditions = [
                'status' => $status
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
            ->addColumn('get_status',function ($row){

                switch ($row->status) {
                    case 'IA':
                        $status = 'Internal Approval';
                        break;
                    case 'QA':
                        $status = 'QA Approval';
                        break;
                    default:
                        $status = '';
                        break;
                }
                switch ($row->approval_status) {
                    case 'OTRB':
                        $approvalStatus = 'Requested by:';
                        break;
                    case 'OTTE':
                        $approvalStatus = 'Requested by:';
                        break;
                    default:
                        $approvalStatus = '';
                        break;
                }
                $result = '';
                $result .= '<center>';
                $result .= '<span class="badge rounded-pill bg-primary"> '.$status.' </span>';
                $result .= '<span class="badge rounded-pill bg-danger"> '.$approvalStatus.' </span>';
                $result .= '</center>';
                return $result;
                return $result;
            })
            ->rawColumns(['get_actions','get_status'])
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
            // 'status' => $request->status,
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
            //TODO: Get ECR Id When opening modal
            $data = [];
            $relations = [];
            $conditions = [
                'classifications_id' => $request->category
            ];
            $ecr_req_data = [];
            $ecr_req_relations = [];
            $ecr_req_conditions = [
                'ecrs_id' => 1,
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
                    $isValid = "is-valid";
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
            $ecr_id = 1;
            $ecrDetailRequest = collect($request->description_of_change)->map(function ($description_of_change,$index) use ($request,$ecr_id){
                return [
                    'ecrs_id' =>  $ecr_id,
                    'description_of_change' => $description_of_change,
                    'reason_of_change' => $request->reason_of_change[$index],
                    'created_at' => now(),
                ];
            });

            foreach ($ecrDetailRequest as $ecrDetailRequestValue) {
               $this->resourceInterface->create(EcrDetail::class, $ecrDetailRequestValue);
            }

            //Requested by, Engg, Heads, QA Approval
            $ecrApprovalTypes = [
                'OTRB' => $request->requested_by,
                'OTTE' => $request->technical_evaluation,
                'OTRVB' => $request->reviewed_by,
                'QA' => [$request->qad_checked_by,$request->qad_approved_by_internal,$request->qad_approved_by_external],
            ];
            $ecrApprovalRequestCtr = 0; //assigned counter
            $ecrApprovalRequest = collect($ecrApprovalTypes)->flatMap(function ($users,$type) use ($request,&$ecrApprovalRequestCtr,$ecr_id){
                    return collect($users)->map(function ($userId) use ($request,$type,&$ecrApprovalRequestCtr,$ecr_id){
                        return [
                            'ecrs_id' =>  $ecr_id,
                            'rapidx_user_id' => $userId,
                            'type' => $type,
                            'counter' => $ecrApprovalRequestCtr++,
                            'remarks' => $request->remarks,
                            'created_at' => now(),
                        ];
                    });

            })->toArray();
            // EcrApproval::where('ecrs_id', $ecr_id)->delete();
            EcrApproval::insert($ecrApprovalRequest);
            DB::commit();
            return response()->json(['is_success' => 'true']);
            //PMI Approvers
            $types = [
                'PB' => $request->prepared_by,
                'CB' => $request->checked_by,
                'AB' => $request->approved_by,
            ];
            $pmiApprovalRequestCtr = 0;
            $pmiApprovalRequest = collect($types)->flatMap(function ($users,$type) use ($request,&$pmiApprovalRequestCtr,$ecr_id){
                //return array users id as array value
                return collect($users)->map(function ($userId) use ($type, $request,&$pmiApprovalRequestCtr,$ecr_id) {
                    // $type as a array name
                    //return array users id, defined type by use keyword,
                    return [
                        'ecrs_id' => $ecr_id,
                         // 'ecrs_id' =>  $ecr_id,
                        'rapidx_user_id' => $userId,
                        'type' => $type,
                        'counter' => $pmiApprovalRequestCtr++,
                        'remarks' => $request->remarks,
                        'created_at' => now(),
                    ];
                });
            })->toArray();
            PmiApproval::insert($pmiApprovalRequest);
            // DB::commit();
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
            $ecrApprovalCollection = collect($ecr[0]->ecr_approvals)->groupBy('type')->toArray();
            $pmiApprovalCollection = collect($ecr[0]->pmi_approvals)->groupBy('type')->toArray();
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
                    'ecrs_id' => 1, //TODO: Get ECR Id from Params
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
}
