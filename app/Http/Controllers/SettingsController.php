<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\DropdownMaster;
use Yajra\DataTables\DataTables;
use App\Interfaces\CommonInterface;
use App\Models\DropdownMasterDetail;
use App\Interfaces\ResourceInterface;
use App\Http\Requests\DropdownMasterDetailRequest;

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
                return $btn = '<button dropdown-master-details-id = "'.$row->id.'"  class="btn btn-outline-info btn-sm" data-toggle="modal" id="btnDropdownMasterDetails" type="button" title="Edit"><i class="fas fa-edit"></i></button>';
            })
            ->addColumn('get_status',function($row){
                return $result = "Active";
            })
            ->rawColumns(['get_action','get_status'])
            ->make(true);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getDropdownMasterDetailsId(Request $request){
        try {
            $conditions = [
                'id' => $request->dropdownMasterDetailsId
            ];
           $dropdownMasterDetail = $this->resourceInterface->readWithRelationsConditionsActive(DropdownMasterDetail::class,[],[],$conditions);
            return response()->json([
                'isSuccess' => 'true',
                'dropdownMasterDetail' => $dropdownMasterDetail
            ]);
        } catch (Exception $e) {
            return response()->json(['is_success' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }
    public function saveDropdownMasterDetails (Request $request,DropdownMasterDetailRequest $dropdownMasterDetailRequest){
        date_default_timezone_set('Asia/Manila');
        try {
            $dropdownMasterDetailRequest = $dropdownMasterDetailRequest->validated();
            $dropdownMasterDetailRequest ['dropdown_masters_id'] = $request->dropdown_masters_id;
            $dropdownMasterDetailRequest ['remarks'] = $request->remarks;
            if ( isset($request->dropdown_master_details_id) ){
                $conditions =[
                    'id' => $request->dropdown_master_details_id
                ];
                $this->resourceInterface->updateConditions(DropdownMasterDetail::class,$conditions,$dropdownMasterDetailRequest);
            }else{
                $dropdownMasterDetailRequest ['created_at'] = now();
                $this->resourceInterface->create(DropdownMasterDetail::class,$dropdownMasterDetailRequest);
            }

            return response()->json(['is_success' => 'true']);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
