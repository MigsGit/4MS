<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DropdownMaster;
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
    public function saveEcr(Request $request){
        return $request->all() ;
        date_default_timezone_set('Asia/Manila');
        DB::beginTransaction();
        try {
            DB::commit();
            return response()->json(['is_success' => 'true']);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['is_success' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }
}
