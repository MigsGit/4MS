<?php

namespace App\Http\Controllers;

use Mail;
use Helpers;
use App\Models\RapidxUser;
use App\Models\UserAccess;
use App\Models\EcrApproval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
}
