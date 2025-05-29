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
    public function check_access(Request $request){
        // $found_key = array_search("32", array_column($_SESSION['rapidx_user_accesses'], 'module_id')); // * 32 is the id of this module on RAPIDX
        // $found_key = in_array("32", array_column($_SESSION['rapidx_user_accesses'], 'module_id')); // * 32 is the id of this module on RAPIDX
        // return $found_key;
        // if($found_key == ""){
        if(!in_array("32", array_column($_SESSION['rapidx_user_accesses'], 'module_id'))){
            return response()->json(['msg' => 'User Dont Have Access', 'access' => $_SESSION['rapidx_user_accesses']], 401);
        }
        else{
            $user_system_access_check = DB::connection('mysql')->table('user_accesses')
            ->where('rapidx_emp_no', $_SESSION['rapidx_user_id'])
            ->whereNull('deleted_at')
            ->select('*')
            ->first();

            // return $user_system_access_check;

            if($user_system_access_check != ""){
                $exploded_category =  explode(",", $user_system_access_check->category_id);

                $uAccessArray = [];
                for ($i=0; $i <count($exploded_category) ; $i++) {
                    array_push($uAccessArray, $exploded_category[$i]);
                }

                $encrypt_id = Helpers::encryptId($user_system_access_check->id);

                return response()->json([
                    'uAccess' => $uAccessArray,
                    'uName' => $_SESSION['rapidx_name'],
                    'appid' => $encrypt_id,
                    'uType' => $user_system_access_check->user_type,
                    'isAuth' => $user_system_access_check->is_auth ]);
            }
            else{
                return response()->json(['msg' => 'User Dont Have Access '], 401);

            }


        }
    }
    public function send_mail($mail_filename, $data, $request, $admin_email, $user_email, $subject){
        Mail::send("mail.{$mail_filename}", $data, function($message) use ($request, $admin_email, $user_email, $subject){
            $message->to($admin_email);
            $message->cc($user_email);
            $message->bcc('cpagtalunan@pricon.ph');
            $message->bcc('iggarcia@pricon.ph');
            $message->subject($subject);
        });
    }
}
