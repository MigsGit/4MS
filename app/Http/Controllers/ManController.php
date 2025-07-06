<?php

namespace App\Http\Controllers;

use App\Models\Ecr;
use App\Models\Man;
use App\Models\ManDetail;
use App\Models\ManApproval;
use App\Models\ManChecklist;
use Illuminate\Http\Request;
use App\Http\Requests\ManRequest;
use Illuminate\Support\Facades\DB;
use App\Interfaces\CommonInterface;
use App\Http\Controllers\Controller;
use App\Models\DropdownMasterDetail;
use App\Interfaces\ResourceInterface;

class ManController extends Controller
{
    protected $resourceInterface;
    protected $commonInterface;
    public function __construct(ResourceInterface $resourceInterface,CommonInterface $commonInterface) {
        $this->resourceInterface = $resourceInterface;
        $this->commonInterface = $commonInterface;
    }
    public function loadEcrManByStatus(Request $request){
        $data = [];
        $relations = [
            'pmi_approvals_pending.rapidx_user',
            'man_detail.man_approvals_pending.rapidx_user',
            'man_detail',
        ];
        $conditions = [
            'status' => 'OK',
            'category' => $request->category
        ];
        $ecr = $this->resourceInterface->readWithRelationsConditionsActive(Ecr::class,$data,$relations,$conditions);
        return DataTables($ecr)
    ->addColumn('get_actions',function ($row) use ($request){
            $result = "";
            $result .= '<center>';
            $result .= '<div class="btn-group dropstart mt-4">';
            $result .= '<button type="button" class="btn btn-secondary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-expanded="false">';
            $result .= '    Action';
            $result .= '</button>';
            $result .= '<ul class="dropdown-menu">';
            if($row->man_detail->status === "RUP" || $row->man_detail->status === "PMIAPP"){
                $result .= '   <li><button class="dropdown-item" type="button" man-status= "'.$row->man_detail->status.'" ecrs-id="'.$row->id.'" materials-id="'.$row->man_detail->id.'"id="btnViewManById"><i class="fa-solid fa-eye"></i> &nbsp;View/Approval</button></li>';
            }
            // if($row->man_detail->status === "RUP" && $row->created_by === session('rapidx_user_id')){
            if($row->man_detail->status === "RUP"){
                $result .= '   <li><button class="dropdown-item" type="button" man-status= "'.$row->man_detail->status.'" ecrs-id="'.$row->id.'" id="btnGetEcrId"><i class="fa-solid fa-edit"></i> &nbsp;Edit</button></li>';
            }
            $result .= '</ul>';
            $result .= '</div>';
            $result .= '</center>';
            return $result;
        })
        ->addColumn('get_status',function ($row) use($request){
            $currentApprover = $row->man_detail->man_approvals_pending[0]['rapidx_user']['name'] ?? '';
            $getStatus = $this->getStatus($row->man_detail->status);
            $result = '';
            $result .= '<center>';
            $result .= '<span class="'.$getStatus['bgStatus'].'"> '.$getStatus['status'].' </span>';
            $result .= '<br>';
            if( $currentApprover != ''){
                $result .= '<span class="badge rounded-pill bg-danger"> '.$currentApprover.' </span>';
            }
            if( $row->man_detail->status === 'PMIAPP' ){ //TODO: Last Status PMI Internal
                $currentApprover = $row->pmi_approvals_pending[0]['rapidx_user']['name'] ?? '';
                $approvalStatus = $row->man_detail->approval_status;
                $getPmiApprovalStatus = $this->commonInterface->getPmiApprovalStatus($approvalStatus);
                $result .= '<span class="badge rounded-pill bg-danger"> '.$getPmiApprovalStatus['approvalStatus'].' '.$currentApprover.' </span>';
            }
            $result .= '</center>';
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
    public function loadManByEcrId(Request $request){
        try {
            $data = [];
            $relations = [
                'rapidx_user_qc_inspector_operator',
                'rapidx_user_trainer',
                'rapidx_user_lqc_supervisor',
            ];
            $conditions = [
                'ecrs_id' => $request->ecrsId ?? ''
            ];
            $ecrDetail = $this->resourceInterface->readWithRelationsConditionsActive(Man::class,$data,$relations,$conditions);
            return DataTables($ecrDetail)
            ->addColumn('get_actions',function ($row){
                $result = '';
                $result .= "<button class='btn btn-outline-info btn-sm mr-1' man-details-id='".$row->id."' id='btnManDetailsId'> <i class='fa-solid fa-pen-to-square'></i></button>";
                $result .= '</br> </br>';
                $result .= "<button class='btn btn-outline-success btn-sm mr-1' man-details-id='".$row->id."' id='btnManChecklistId'> <i class='fa-solid fa-check'></i></button>";
                $result .= '</center>';
                return $result;
            })
            ->addColumn('qc_inspector_operator',function ($row){
                $result = '';
                $result .= $row->rapidx_user_qc_inspector_operator->name ?? null;
                return $result;
            })
            ->addColumn('trainer',function ($row){
                $result = '';
                $result .= $row->rapidx_user_trainer->name ?? null;
                return $result;
            })
            ->addColumn('lqc_supervisor',function ($row){
                $result = '';
                $result .= $row->rapidx_user_lqc_supervisor->name ?? null;
                return $result;
            })
            ->addColumn('trainer_result',function ($row){
                $result = '';
                switch ($row->trainer_result) {
                    case 'OK':
                        $bgColor = 'bg-success text-white';
                        break;
                    case 'NG':
                        $bgColor = 'bg-danger text-white';
                        break;
                    default:
                        $bgColor = 'bg-secondary text-white';
                        break;
                }
                $result .='<span class="badge '.$bgColor.'"> '.$row->trainer_result.' </span>';
                return $result;
            })
            ->addColumn('lqc_result',function ($row){
                $result = '';
                switch ($row->lqc_result) {
                    case 'PASSED':
                        $bgColor = 'bg-success text-white';
                        break;
                    case 'FAILED':
                        $bgColor = 'bg-danger text-white';
                        break;
                    default:
                        $bgColor = 'bg-secondary text-white';
                        break;
                }
                $result .='<span class="badge '.$bgColor.'"> '.$row->lqc_result.' </span>';
                return $result;
            })
            ->rawColumns([
                'get_actions',
                'qc_inspector_operator',
                'trainer',
                'trainer_result',
                'lqc_supervisor',
                'lqc_result',
            ])
            ->make(true);
        } catch (Exception $e) {
            throw $e;

        }
    }
    public function loadManChecklist(Request $request){
        try {
            $data = [];
            $relations = [];
            $conditions = [
                'dropdown_masters_id' => $request->dropdown_masters_id
            ];
            $man_checklist_data = [];
            $man_checklist_relations = [];
            $man_checklist_conditions = [
                'man_id' => $request->manDetailsId,
            ];

            $dropdownMasterDetail = $this->resourceInterface->readCustomEloquent(DropdownMasterDetail::class,$data,$relations,$conditions);
            $dropdownMasterDetail->orderBy('dropdown_masters_details');
            // return $classificationRequirement; dropdown_masters_details
            $manChecklist = $this->resourceInterface->readWithRelationsConditionsActive(ManChecklist::class,$man_checklist_data,$man_checklist_relations,$man_checklist_conditions);
            return DataTables($dropdownMasterDetail)
            ->addColumn('get_actions',function ($row) use($manChecklist) {
                // return 'true';
                $manChecklistCollection = collect($manChecklist);
                $manChecklistMatch = $manChecklistCollection->firstWhere('dropdown_master_details_id', $row->id);
                $manChecklistId = $manChecklistMatch['id'] ?? '';
                $cSelected = $manChecklistMatch['decision'] === 'C' ? 'selected' : '';
                $xSelected = $manChecklistMatch['decision'] === 'X' ? 'selected' : '';
                if($manChecklistId === ''){
                    $isValid = "is-invalid";
                    $emptySelected = "selected";
                }else{
                    $isValid = "";
                    $emptySelected = "";
                }
                $result = '';
                $result .= '<center>';
                $result .= "<select id='btnChangeManChecklistDecision' class='form-select btn-change-ecr-req-decision ".$isValid."' ref=btnChangeManChecklistDecision man-checklists-id ='".$manChecklistId."' dropdown-master-details-id='".$row->id."'>";
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
            throw $e;
        }
    }
    public function loadManApproverSummaryEcrsId (Request $request){
        try {
            $ecrsId = $request->ecrsId ?? "";
            $data = [];
            $relations = [
                'rapidx_user'
            ];
            $conditions = [
                'ecrs_id' => $ecrsId
            ];
            $methodpproval = $this->resourceInterface->readCustomEloquent(ManApproval::class,$data,$relations,$conditions);
            $methodpproval = $methodpproval
            ->whereNotNull('rapidx_user_id')
            ->orderBy('id','asc')
            ->get();
            return DataTables($methodpproval)
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
                $result .= '<span class="badge rounded-pill bg-primary"> '.$getApprovalStatus['approvalStatus'].'</span>';
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
    public function saveMan(Request $request,ManRequest $manRequest){
        try {
            date_default_timezone_set('Asia/Manila');
            DB::beginTransaction();
            $manModel = Man::class;
            $manRequestValidated = $manRequest->validated();
            if ( isset($request->man_id) ){ //Edit
                $manRequestValidated['trainer'] = $request->trainer;
                $manRequestValidated['trainer_sample_size'] = $request->trainer_sample_size;
                $manRequestValidated['trainer_result'] = $request->trainer_result;
                $manRequestValidated['lqc_supervisor'] = $request->lqc_supervisor;
                $manRequestValidated['lqc_sample_size'] = $request->lqc_sample_size;
                $manRequestValidated['lqc_result'] = $request->lqc_result;
                $manRequestValidated['process_change_factor'] = $request->process_change_factor;
                $conditions = [
                    'id' => $request->man_id
                ];
                $this->resourceInterface->updateConditions($manModel,$conditions,$manRequestValidated);
            }else{ //Add
                $this->resourceInterface->create($manModel,$manRequestValidated);
            }
            DB::commit();
            return response()->json(['is_success' => 'true']);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;

        }
    }
    public function getManById(Request $request){
        try {
            $data = [];
            $relations = [
            ];
            $conditions = [
                'id' => $request->manId
            ];
            $man = $this->resourceInterface->readWithRelationsConditionsActive(Man::class,$data,$relations,$conditions);
            return response()->json(['is_success' => 'true','man'=>$man[0]]);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function manChecklistDecisionChange(Request $request){
        try {
            if( isset($request->manChecklistsId) ){ //edit
                // return 'edit';
                $conditions = [
                    'id' => $request->manChecklistsId
                ];
                $data = [
                    // 'dropdown_master_details_id' => $request->dropdownMasterDetailsId,
                    'decision' => $request->manChecklistValue,
                ];

                $this->resourceInterface->updateConditions(ManChecklist::class,$conditions,$data);
            }else{ //add
                $data = [
                    'dropdown_master_details_id' => $request->dropdownMasterDetailsId,
                    'decision' => $request->manChecklistValue,
                    'man_id' => $request->manDetailsId,
                ];
                $this->resourceInterface->create(ManChecklist::class,$data);
            }

            return response()->json(['is_success' => 'true']);
        } catch (Exception $e) {

            throw $e;
        }
    }
    public function saveManApproval(Request $request){
        try {
            date_default_timezone_set('Asia/Manila');
            DB::beginTransaction();
            $selectedId = $request->selectedId;
            //Get Current Ecr Approval is equal to Current Session
            $methodApprovalCurrent = ManApproval::where('ecrs_id',$selectedId)
            ->whereNotNull('rapidx_user_id')
            ->where('status','PEN')
            ->first();
            if($methodApprovalCurrent->rapidx_user_id != session('rapidx_user_id')){
                return response()->json(['isSuccess' => 'false','msg' => 'You are not the current approver !'],500);
            }
            //Update the machine Approval Status
            $methodApprovalCurrent->update([
                'status' => $request->status,
                'remarks' => $request->remarks,
            ]);
            //Get the ECR Approval Status & Id, Update the Approval Status as PENDING
           $methodApproval = ManApproval::where('ecrs_id',$selectedId)
           ->whereNotNull('rapidx_user_id')
           ->where('status','-')
           ->limit(1)
           ->get(['id','approval_status']);
            if ( count($methodApproval) != 0){
                $methodApprovalValidated = [
                    'status' => 'PEN',
                ];
                $methodApprovalConditions = [
                    'id' => $methodApproval[0]->id,
                ];
                $this->resourceInterface->updateConditions(ManApproval::class,$methodApprovalConditions,$methodApprovalValidated);
                //Update the ECR Approval Status
                $enviromentConditions = [
                    'id' => $selectedId,
                ];
                $enviromentValidated = [
                    'approval_status' => $methodApproval[0]->approval_status,
                ];
                $this->resourceInterface->updateConditions(ManDetail::class,$enviromentConditions,$enviromentValidated);
            }else{
                $enviromentConditions = [
                    'id' => $selectedId,
                ];
                $enviromentValidated = [
                    'status' => 'PMIAPP',
                    'approval_status' => 'PB',
                ];
                $this->resourceInterface->updateConditions(ManDetail::class,$enviromentConditions,$enviromentValidated);
            }
             //DISAPPROVED ECR
             if($request->status === "DIS"){
                $conditions = [
                    'id' => $selectedId,
                ];
                $requestValidated = [
                    'status' => 'DIS',
                    'approval_status' => 'DIS', //Repeat the status
                ];
                $this->resourceInterface->updateConditions(ManDetail::class,$conditions,$requestValidated);
            }
            DB::commit();
            return response()->json(['is_success' => 'true']);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
    //Common Function
    public function getStatus($status){

        try {
             switch ($status) {
                 case 'RUP':
                     $status = 'For Requestor Update';
                     $bgStatus = 'badge rounded-pill bg-primary';
                     break;
                case 'FORAPP':
                    $status = 'For Approval';
                    $bgStatus = 'badge rounded-pill bg-warning';
                    break;
                case 'PMIAPP':
                    $status = 'PMI Approval';
                    $bgStatus = 'badge rounded-pill bg-info';
                    break;
                case 'OK':
                    $status = 'Completed';
                    $bgStatus = 'badge rounded-pill bg-success';
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
                case 'RUP':
                    $approvalStatus = 'Requestor ECR Update:';
                    break;
                case 'PRNDPB':
                    $approvalStatus = 'Production Prepared by:';
                    break;
                case 'PRNDCB':
                    $approvalStatus = 'Production Checked by:';
                    break;
                case 'PRNDAP':
                    $approvalStatus = 'Production Approved by:';
                    break;
                case 'PURPB':
                    $approvalStatus = 'Purchasing Prepared by:';
                    break;
                case 'PURCB':
                    $approvalStatus = 'Purchasing Checked by:';
                    break;
                case 'PURAB':
                    $approvalStatus = 'Purchasing Approved by:';
                    break;
                case 'PPCPB':
                    $approvalStatus = 'PPC Prepared by:';
                    break;
                case 'PPCCB':
                    $approvalStatus = 'PPC Checked by:';
                    break;
                case 'PPCAB':
                    $approvalStatus = 'PPC Approved by:';
                    break;
                case 'EMSPB':
                    $approvalStatus = 'EMS Prepared by:';
                    break;
                case 'EMSCB':
                    $approvalStatus = 'EMS Checked by:';
                    break;
                case 'EMSAB':
                    $approvalStatus = 'EMS Approved by:';
                    break;
                case 'LQCPB':
                    $approvalStatus = 'QC Prepared by';
                    break;
                case 'LQCCB':
                    $approvalStatus = 'QC Checked by';
                    break;
                case 'LQCAB':
                    $approvalStatus = 'QC Approved by';
                    break;
                case 'MENGPB':
                    $approvalStatus = 'Maintenance Engg Prepared by';
                    break;
                case 'MENGCB':
                    $approvalStatus = 'Maintenance Engg Checked by';
                    break;
                case 'MENGAB':
                    $approvalStatus = 'Maintenance Engg Approved by';
                    break;
                case 'PENGPB':
                    $approvalStatus = 'Process Engg Prepared by';
                    break;
                case 'PENGCB':
                    $approvalStatus = 'Process Engg Checked by';
                    break;
                case 'PENGAB':
                    $approvalStatus = 'Process Engg Approved by';
                    break;
                case 'QAPB':
                    $approvalStatus = 'QA Prepared by';
                    break;
                case 'QACB':
                    $approvalStatus = 'QA Checked by';
                    break;
                case 'QAAB':
                    $approvalStatus = 'QA Approved by';
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

