<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\DropdownMaster;
use Yajra\DataTables\DataTables;
use App\Interfaces\CommonInterface;
use App\Models\DropdownMasterDetail;
use App\Interfaces\ResourceInterface;

class SettingsController extends Controller
{
    protected $resourceInterface;
    protected $commonInterface;
    public function __construct(ResourceInterface $resourceInterface,CommonInterface $commonInterface) {
        $this->resourceInterface = $resourceInterface;
        $this->commonInterface = $commonInterface;
    }

    public function getUserMaster(Request $request){
        // return 'true' ;
        try {
            $user = User::whereNull('deleted_at')->get();

            return DataTables::of($user)
            ->addColumn('get_action',function($row){
                // return $row->id;
                return $btn = '<button data-id = "'.$row->id.'"  class="btn btn-outline-info btn-sm" data-toggle="modal" id="btnEdocs" type="button" title="Edit"><i class="fas fa-edit"></i></button>';
                // return $btn = '<button data-id = "'.$row->id.'" id="editResProcedure" type="button" class="btn btn-info btn-sm" title="Edit"></i>Edit</button>';
            })
            ->addColumn('get_status',function($row){
                return $result = "Active";
                // return $btn = '<button data-id = "'.$row->id.'" id="editResProcedure" type="button" class="btn btn-info btn-sm" title="Edit"></i>Edit</button>';
            })
            ->rawColumns(['get_action','get_status'])
            ->make(true);
            // return response()->json(['is_success' => 'true']);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getDropdownMaster(Request $request){
        try {
            $dropdownMaster =  $this->resourceInterface->readWithRelationsConditions(DropdownMaster::class,[],[],[]);
            return response()->json([
                'is_success' => 'true',
                'dropdownMaster' => $dropdownMaster
            ]);
        } catch (Exception $e) {
            return response()->json(['is_success' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }
    public function loadDropdownMasterDetails(Request $request){
        try {
            $conditions = [
                'dropdown_masters_id' => $request->dropDownMastersId ?? ""
            ];

            $dropdownMaster =  $this->resourceInterface->readWithRelationsConditions(DropdownMasterDetail::class,[],[],$conditions);
            return DataTables::of($dropdownMaster)
            ->addColumn('get_action',function($row){
                // return $row->id;
                return $btn = '<button data-id = "'.$row->id.'"  class="btn btn-outline-info btn-sm" data-toggle="modal" id="btnEdocs" type="button" title="Edit"><i class="fas fa-edit"></i></button>';
                // return $btn = '<button data-id = "'.$row->id.'" id="editResProcedure" type="button" class="btn btn-info btn-sm" title="Edit"></i>Edit</button>';
            })
            ->addColumn('get_status',function($row){
                return $result = "Active";
                // return $btn = '<button data-id = "'.$row->id.'" id="editResProcedure" type="button" class="btn btn-info btn-sm" title="Edit"></i>Edit</button>';
            })
            ->rawColumns(['get_action','get_status'])
            ->make(true);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
