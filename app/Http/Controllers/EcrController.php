<?php

namespace App\Http\Controllers;
use App\Models\Ecr;
use App\Models\EcrApproval;
use App\Models\PmiApproval;
use Illuminate\Http\Request;
use App\Models\DropdownMaster;
use App\Http\Requests\EcrRequest;
use Illuminate\Support\Facades\DB;
use App\Interfaces\CommonInterface;
use App\Http\Controllers\Controller;
use App\Interfaces\ResourceInterface;





class EcrController extends Controller
{
    protected $resourceInterface;
    protected $commonInterface;
    public function __construct(ResourceInterface $resourceInterface,CommonInterface $commonInterface) {
        $this->resourceInterface = $resourceInterface;
        $this->commonInterface = $commonInterface;
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
            DB::beginTransaction();
            $ctr = 0; //assigned counter
            $ecr_approval_types = [
                'OTRB' => $request->requested_by,
                'OTTE' => $request->technical_evaluation,
                'OTRVB' => $request->reviewed_by,
                'QA' => [$request->qad_checked_by,$request->qad_approved_by_internal,$request->qad_approved_by_external],
            ];
            $ecrApprovalRequest = collect($ecr_approval_types)->flatMap(function ($users,$type) use ($request,&$ctr){
                    return collect($users)->map(function ($userId) use ($request,$type,&$ctr){
                        return [
                            'ecrs_id' =>  1,
                            'rapidx_user_id' => $userId,
                            'type' => $type,
                            'counter' => $ctr,
                            'remarks' => $request->remarks,
                            'created_at' => now(),
                        ];
                    });

            })->toArray();
            return $ecr =  EcrApproval::insert($ecrApprovalRequest);

            $types = [
                'PB' => $request->prepared_by,
                'CB' => $request->checked_by,
                'AB' => $request->approved_by,
            ];

            $pmiApprovalRequest = collect($types)->flatMap(function ($users,$type) use ($request,&$ctr){
                //return array users id as array value
                return collect($users)->map(function ($userId) use ($type, $request,&$ctr) {
                    // $type as a array name
                    //return array users id, defined type by use keyword,
                    return [
                        'ecrs_id' => 1,
                        'rapidx_user_id' => $userId,
                        'type' => $type,
                        'counter' => $ctr++,
                        'remarks' => $request->remarks,
                        'created_at' => now(),
                    ];
                });
            })->toArray();

            $ecr =  PmiApproval::insert($pmiApprovalRequest);
            DB::commit();
            return response()->json(['is_success' => 'true']);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
