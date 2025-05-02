<?php

namespace App\Http\Controllers;
use App\Models\Ecr;
use App\Models\EcrDetail;
use App\Models\EcrApproval;
use App\Models\PmiApproval;
use Illuminate\Http\Request;
use App\Models\DropdownMaster;
use App\Http\Requests\EcrRequest;
use Illuminate\Support\Facades\DB;
use App\Interfaces\CommonInterface;
use App\Http\Controllers\Controller;
use App\Interfaces\ResourceInterface;
use App\Http\Requests\EcrDetailRequest;





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
    public function saveEcr(Request $request, EcrRequest $ecrRequest,EcrDetailRequest $ecrDetailRequest){
        date_default_timezone_set('Asia/Manila');
        try {
            //TODO: DELETE, Auto Increment Ctrl Number, InsertById
            DB::beginTransaction();
            $ecrDetailRequest = collect($request->description_of_change)->map(function ($description_of_change,$index) use ($request){
                return [
                    'ecrs_id' =>  1,
                    'description_of_change' => $description_of_change,
                    'reason_of_change' => $request->reason_of_change[$index],
                    'created_at' => now(),
                ];
            });
            DB::commit();
            foreach ($ecrDetailRequest as $ecrDetailRequestValue) {
               $this->resourceInterface->create(EcrDetail::class, $ecrDetailRequestValue);
            }
            return 'true';
            
            $ctr = 0; //assigned counter
            //Requested by, Engg, Heads, QA Approval
            return $ecr_approval_types = [
                'OTRB' => $request->requested_by,
                'OTTE' => $request->technical_evaluation,
                'OTRVB' => $request->reviewed_by,
                'QA' => [$request->qad_checked_by,$request->qad_approved_by_internal,$request->qad_approved_by_external],
            ];
            $ecrApprovalRequest = collect($ecr_approval_types)->flatMap(function ($users,$type) use ($request,&$ctr,$ecr_id){
                    return collect($users)->map(function ($userId) use ($request,$type,&$ctr,$ecr_id){
                        return [
                            'ecrs_id' =>  1,
                            // 'ecrs_id' =>  $ecr_id,
                            'rapidx_user_id' => $userId,
                            'type' => $type,
                            'counter' => $ctr,
                            'remarks' => $request->remarks,
                            'created_at' => now(),
                        ];
                    });

            })->toArray();
            $ecr =  EcrApproval::insert($ecrApprovalRequest);

            //PMI Approvers
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
                         // 'ecrs_id' =>  $ecr_id,
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
