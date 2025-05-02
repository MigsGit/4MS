<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Ecr;
use App\Models\EcrApproval;
use App\Models\DropdownMaster;
use App\Http\Requests\EcrRequest;
use App\Interfaces\CommonInterface;
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
            return response()->json(['is_success' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }
    public function saveEcr(Request $request, EcrRequest $ecrRequest){

        // $ecr =  $this->resourceInterface->create( Ecr::class,$ecrRequest->validated());


        // return $ecr_id =  $ecr['data_id'];


        //TODO: Make ECR Table
        date_default_timezone_set('Asia/Manila');
        try {
            // return $request->requested_by;
            foreach ($request->requested_by as $key => $requestedByValue) {
                $ecrApprovalRequest = [
                    'ecrs_id' =>  1,
                    'rapidx_user_id' => $requestedByValue,
                    'type' => 'OTHER',
                    'counter' => $key,
                    'remarks' => $request->remarks,
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                // return $ecrApprovalRequest;
                // $ecr =  $this->resourceInterface->create( EcrApproval::class,$ecrApprovalRequest);
            }
            return $request->prepared_by;
            foreach ($request->prepared_by as $key => $preparedByValue) {
                $ecrApprovalRequest = [
                    'ecrs_id' =>  1,
                    'rapidx_user_id' => $preparedByValue,
                    'type' => 'QA',
                    'counter' => $key,
                    'remarks' => $request->remarks,
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                // return $ecrApprovalRequest;
                // $ecr =  $this->resourceInterface->create( EcrApproval::class,$ecrApprovalRequest);
            }

            /*
                'rapidx_user_id' => $request->technical_evaluation,
                'rapidx_user_id' => $request->reviewed_by,
             */


            return response()->json(['is_success' => 'true']);
        } catch (Exception $e) {
            return response()->json(['is_success' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }
}
