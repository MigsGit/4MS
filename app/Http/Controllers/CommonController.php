<?php

namespace App\Http\Controllers;

use Mail;
use Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Ecr;
use App\Models\Man;
use App\Models\Material;
use App\Models\RapidxUser;
use App\Models\EcrApproval;
use App\Models\Environment;
use App\Models\PmiApproval;
use App\Models\SpecialAcceptanceDetail;
use App\Interfaces\CommonInterface;
use App\Interfaces\ResourceInterface;


class CommonController extends Controller
{
    protected $resourceInterface;
    protected $commonInterface;
    public function __construct(ResourceInterface $resourceInterface,CommonInterface $commonInterface) {
        $this->resourceInterface = $resourceInterface;
        $this->commonInterface = $commonInterface;
    }
    public function loadSpecialAcceptanceDetailsByEcrId(Request $request){
        try {
            $ecrsId = $request->ecrsId ?? "";
            $data = [];
            $relations = [];
            $conditions = [
                'ecrs_id' => $ecrsId
            ];
            $data = $this->resourceInterface->readCustomEloquent(SpecialAcceptanceDetail::class,$data,$relations,$conditions);
            $specialAcceptanceDetail = $data->get();
            return DataTables($specialAcceptanceDetail)
            ->addColumn('get_actions',function ($row){
                $result = '';
                $result .= '<center>';
                $result .= "<button class='btn btn-outline-info btn-sm mr-1 btn-get-ecr-id' sa-details-id='".$row->id."' id='btnGetSaDetailsId'> <i class='fa-solid fa-pen-to-square'></i></button>";
                $result .= '</center>';
                return $result;
            })
            ->rawColumns(['get_actions'])
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
            $conditions = [
                'ecrs_id' => $request->ecrsId,
                'status' => 'PEN',
            ];
            $data = [
                'rapidx_user_id'
            ];
            $relations = [

            ];
            $ecrApprovalQuery = $this->resourceInterface->readCustomEloquent(EcrApproval::class,$data,$relations,$conditions);
            $ecrApproval =  $ecrApprovalQuery->get();
            $isSessionApprover =  session('rapidx_user_id') ===  $ecrApproval[0]->rapidx_user_id ? true: false ;
            $ecrApproval[0]->rapidx_user_id;
            return response()->json(['isSuccess' => 'true','isSessionApprover'=>$isSessionApprover]);
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
    public function savePmiInternalApproval(Request $request){

        date_default_timezone_set('Asia/Manila');
        DB::beginTransaction();
        try {
            $ecrsId = $request->ecrsId;
            //Get Current Ecr Approval is equal to Current Session
            $pmiInternalApprovalCurrent = PmiApproval::where('ecrs_id',$ecrsId)->where('status','PEN')->limit(1)->get(['rapidx_user_id']);
            if($pmiInternalApprovalCurrent[0]->rapidx_user_id != session('rapidx_user_id')){
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
            $pmiInternalApproval = $currentModel::where('ecrs_id',$ecrsId)->limit(1)->get(['approval_status']);
            //Update the ECR Approval Status
            $pmiInternalApprovalValidated = [
                'status' => $request->status,
                'remarks' => $request->remarks,
            ];
            $pmiInternalApprovalConditions = [
                'ecrs_id' => $ecrsId,
                'approval_status' => $pmiInternalApproval[0]->approval_status,
                'rapidx_user_id' => session('rapidx_user_id'), //Double check the rapidx user id to update status
            ];
            $this->resourceInterface->updateConditions(PmiApproval::class,$pmiInternalApprovalConditions,$pmiInternalApprovalValidated);
            //Get the ECR Approval Status & Id, Update the Approval Status as PENDING
           $pmiInternalApproval = PmiApproval::where('ecrs_id',$ecrsId)->where('status','-')->limit(1)->get(['id','approval_status']);
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
                $this->resourceInterface->updateConditions(Environment::class,$enviromentConditions,$enviromentValidated);
            }else{
                $enviromentConditions = [
                    'ecrs_id' => $ecrsId,
                ];
                $enviromentValidated = [
                    'status' => 'OK',
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
}
