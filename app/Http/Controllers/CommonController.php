<?php

namespace App\Http\Controllers;

use Mail;
use Helpers;
use App\Models\Ecr;
use App\Models\Man;
use App\Models\Material;
use App\Models\RapidxUser;
use App\Models\EcrApproval;
use App\Models\Environment;
use App\Models\PmiApproval;
use Illuminate\Http\Request;
use App\Models\MethodApproval;
use App\Models\MachineApproval;
use App\Models\MaterialApproval;
use App\Models\SpecialInspection;
use Illuminate\Support\Facades\DB;
use App\Interfaces\CommonInterface;
use App\Interfaces\ResourceInterface;
use App\Http\Requests\SpecialInspectionRequest;


class CommonController extends Controller
{
    protected $resourceInterface;
    protected $commonInterface;
    public function __construct(ResourceInterface $resourceInterface,CommonInterface $commonInterface) {
        $this->resourceInterface = $resourceInterface;
        $this->commonInterface = $commonInterface;
    }
    public function loadSpecialInspectionByEcrId(Request $request){
        try {
            $ecrsId = $request->ecrsId ?? "";
            $data = [];
            $relations = [
                'rapidx_user'
            ];
            $conditions = [
                'ecrs_id' => $ecrsId
            ];
            $data = $this->resourceInterface->readCustomEloquent(SpecialInspection::class,$data,$relations,$conditions);
            $specialInspection = $data->get();
            return DataTables($specialInspection)
            ->addColumn('get_actions',function ($row){
                $result = '';
                $result .= '<center>';
                $result .= "<button type='button' class='btn btn-outline-info btn-sm mr-1' special-inspections-id='".$row->id."' id='btnGetSpecialInspectionId'> <i class='fa-solid fa-pen-to-square'></i></button>";
                $result .= '</center>';
                return $result;
            })
            ->addColumn('get_inspector',function ($row){
                $result = '';
                $result .= '<center>';
                $result .= $row->rapidx_user['name'] ?? "---";
                $result .= '</center>';
                return $result;
            })
            ->rawColumns([
                'get_actions',
                'get_inspector',
            ])
            ->make(true);
            return response()->json(['is_success' => 'true']);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function loadPmiInternalApprovalSummary(Request $request){
        try {
            $ecrsId = $request->ecrsId ?? "";
            $data = [];
            $relations = [
                'rapidx_user'
            ];
            $conditions = [
                'ecrs_id' => $ecrsId
            ];
            $pmiApproval = $this->resourceInterface->readCustomEloquent(PmiApproval::class,$data,$relations,$conditions);
            $pmiApproval = $pmiApproval->orderBy('counter','asc')->get();
            return DataTables($pmiApproval)
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
            return response()->json(['is_success' => 'true']);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getRapidxUserByIdOpt(Request $request){
        try {
            // $rapidxUserById = RapidxUser::where('department_id',22)->where('logdel',0)->get(); //22 QAD
            $data = [];
            $relations = [];
            $conditions = [
                'department_id' => 1,
                'user_stat' => 1,
            ];
            // $query->where('deleted_at',NULL);

            $rapidxUserById = $this->resourceInterface->readWithRelationsConditions(RapidxUser::class,$data,$relations,$conditions);
            $rapidxUserById = $rapidxUserById;
            // $arr_merge_group = array_merge(...array_map(function($item) {
            //     return (array) $item;
            // }, $arr_group_by1));
            return response()->json(['is_success' => 'true','rapidxUserById'=>$rapidxUserById]);
        } catch (Exception $e) {
            return response()->json(['is_success' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }
    public function getCurrentApproverSession(Request $request){
        try {
            switch  ($request->approvalType) {
                case 'ecrApproval':
                    $currentModel = EcrApproval::class;
                    $conditions = [
                        'ecrs_id' => $request->ecrsId,
                        'status' => 'PEN',
                    ];
                    $data = [
                        'rapidx_user_id'
                    ];
                    break;
                case 'materialApproval':
                    $currentModel = MaterialApproval::class;
                    $conditions = [
                        'materials_id' => $request->selectedId,
                        'status' => 'PEN',
                    ];
                    $data = [
                        'rapidx_user_id'
                    ];
                    break;
                case 'machineApproval':
                    $currentModel = MachineApproval::class;
                    $conditions = [
                        'machines_id' => $request->selectedId,
                        'status' => 'PEN',
                    ];
                    $data = [
                        'rapidx_user_id'
                    ];
                    break;
                case 'methodApproval':
                    $currentModel = MethodApproval::class;
                    $conditions = [
                        'methods_id' => $request->selectedId,
                        'status' => 'PEN',
                    ];
                    $data = [
                        'rapidx_user_id'
                    ];
                    break;
                case 'pmiApproval':
                    $currentModel = PmiApproval::class;
                    $conditions = [
                        'ecrs_id' => $request->selectedId,
                        'status' => 'PEN',
                    ];
                    $data = [
                        'rapidx_user_id'
                    ];
                    break;
                default:
                    //TODO:Error Handling
                    break;
            }
            $relations = [];
            $approvalQuery = $this->resourceInterface->readCustomEloquent($currentModel,$data,$relations,$conditions);
            $approval =  $approvalQuery
            ->whereNotNull('rapidx_user_id')
            ->get();
            $isSessionApprover =  session('rapidx_user_id') ===  $approval[0]->rapidx_user_id ? true: false ;
            // $approval[0]->rapidx_user_id;
            return response()->json(['isSuccess' => 'true','isSessionApprover'=>$isSessionApprover,'type'=>$currentModel]);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function getCurrentPmiInternalApprover(Request $request){
        try {
            $conditions = [
                'ecrs_id' => $request->ecrsId, //TODO: Ecr Id
                'status' => 'PEN',
            ];
            $data = [
                'rapidx_user_id'
            ];
            $relations = [

            ];
            $ecrApprovalQuery = $this->resourceInterface->readCustomEloquent(PmiApproval::class,$data,$relations,$conditions);
            $ecrApproval =  $ecrApprovalQuery->get();
            $isSessionPmiInternalApprover =  session('rapidx_user_id') ===  $ecrApproval[0]->rapidx_user_id ? true: false ;
            return response()->json(['isSuccess' => 'true','isSessionPmiInternalApprover'=>$isSessionPmiInternalApprover]);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function getApprovalStatus($approval_status){
        try {
             switch ($approval_status) {
                 case 'PB':
                     $approvalStatus = 'Prepared by:';
                     break;
                 case 'CB':
                     $approvalStatus = 'Checked by:';
                     break;
                 case 'AB':
                     $approvalStatus = 'Approved by:';
                     break;
                 default:
                     $approvalStatus = '---';
                     break;
             }
             return [
                 'approvalStatus' => $approvalStatus,
             ];
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function getSpecialInspectionById(Request $request){
        try {
            $conditions = [
                'id' => $request->specialInspectionsId,
            ];
            $data = [];
            $relations = [];
            $data = $this->resourceInterface->readCustomEloquent(SpecialInspection::class,$data,$relations,$conditions);
            $specialInspection =  $data->get();
            return response()->json(['isSuccess' => 'true','specialInspection'=>$specialInspection[0]]);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function savePmiInternalApproval(Request $request){
        try {
            date_default_timezone_set('Asia/Manila');
            DB::beginTransaction();
            $ecrsId = $request->ecrsId;
            //Get Current Ecr Approval is equal to Current Session
            $pmiInternalApprovalCurrent = PmiApproval::where('ecrs_id',$ecrsId)
            ->whereNotNull('rapidx_user_id')
            ->where('status','PEN')
            ->first();
            if($pmiInternalApprovalCurrent->rapidx_user_id != session('rapidx_user_id')){
                return response()->json(['isSuccess' => 'false','msg' => 'You are not the current approver !'],500);
            }
            //Get the ECR Category
            $isCategory = Ecr::where('id',$ecrsId)
            ->whereNull('deleted_at')
            ->limit(1)
            ->get(['category']);
            $isCategory = $isCategory[0]->category;
            switch ($isCategory) {
                case 'Man':
                    $currentModel = Man::class;
                    break;
                case 'Material':
                    $currentModel = Material::class;
                    break;
                case 'Machine':
                    $currentModel = Machine::class;
                    break;
                case 'Method':
                    $currentModel = Method::class;
                    break;
                case 'Environment':
                    $currentModel = Environment::class;
                    $isEnvironmentRefFileExist = Environment::where('ecrs_id',$ecrsId)
                    ->whereNotNull('original_filename')
                    ->count();
                    if ( $isEnvironmentRefFileExist === 0){
                        return response()->json(['isSuccess' => 'false','msg' => 'Please upload Environment Reference File !'],500);
                    }
                    break;
                default:
                    return response()->json(['isSuccess' => 'false','msg' => 'Unknown Model!'],500);
                    break;
            }
            //Get Current Status
            $pmiInternalApprovalCurrent->update([
                'status' => $request->status,
                'remarks' => $request->remarks,
            ]);
            //Get the ECR Approval Status & Id, Update the Approval Status as PENDING
           $pmiInternalApproval = PmiApproval::where('ecrs_id',$ecrsId)
           ->whereNotNull('rapidx_user_id')
           ->where('status','-')
           ->limit(1)
           ->get(['id','approval_status']);
            if ( count($pmiInternalApproval) != 0){
                $pmiInternalApprovalValidated = [
                    'status' => 'PEN',
                ];
                $pmiInternalApprovalConditions = [
                    'id' => $pmiInternalApproval[0]->id,
                ];
                $this->resourceInterface->updateConditions(PmiApproval::class,$pmiInternalApprovalConditions,$pmiInternalApprovalValidated);
                //Update the ECR Approval Status
                $enviromentConditions = [
                    'ecrs_id' => $ecrsId,
                ];
                $enviromentValidated = [
                    'approval_status' => $pmiInternalApproval[0]->approval_status,
                ];
                $this->resourceInterface->updateConditions($currentModel,$enviromentConditions,$enviromentValidated);
            }else{
                $enviromentConditions = [
                    'ecrs_id' => $ecrsId,
                ];
                $enviromentValidated = [
                    'status' => 'OK',
                    'approval_status' => 'OK',
                ];
                $this->resourceInterface->updateConditions($currentModel,$enviromentConditions,$enviromentValidated);
            }
             //DISAPPROVED ECR
             if($request->status === "DIS"){
                $enviromentConditions = [
                    'ecrs_id' => $ecrsId,
                ];
                $enviromentValidated = [
                    'status' => 'DIS',
                    'approval_status' => 'PB',
                ];
                $this->resourceInterface->updateConditions($currentModel,$enviromentConditions,$enviromentValidated);
            }
            DB::commit();
            return response()->json(['is_success' => 'true']);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
    public function saveSpecialInspection(SpecialInspectionRequest $specialInspectionRequest){
        try {
            date_default_timezone_set('Asia/Manila');
            DB::beginTransaction();
            if( isset($specialInspectionRequest->special_inspections_id)){ //Edit
                $specialInspectionRequestValidated = $specialInspectionRequest->validated();
                $conditions = [
                    'id' => $specialInspectionRequest->special_inspections_id
                ];
                $specialInspectionRequestValidated['updated_by'] = session('rapidx_user_id');
                $this->resourceInterface->updateConditions(SpecialInspection::class,$conditions,$specialInspectionRequestValidated);
            }else{ //Add
                $specialInspectionRequestValidated = $specialInspectionRequest->validated();
                $specialInspectionRequestValidated['created_at'] = now();
                $specialInspectionRequestValidated['created_by'] = session('rapidx_user_id');
                $this->resourceInterface->create(SpecialInspection::class,$specialInspectionRequestValidated);
            }
            DB::commit();
            return response()->json(['is_success' => 'true']);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
