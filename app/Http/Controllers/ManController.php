<?php

namespace App\Http\Controllers;

use App\Models\Man;
use Illuminate\Http\Request;
use App\Http\Requests\ManRequest;
use Illuminate\Support\Facades\DB;
use App\Interfaces\CommonInterface;
use App\Http\Controllers\Controller;
use App\Interfaces\ResourceInterface;

class ManController extends Controller
{
    protected $resourceInterface;
    protected $commonInterface;
    public function __construct(ResourceInterface $resourceInterface,CommonInterface $commonInterface) {
        $this->resourceInterface = $resourceInterface;
        $this->commonInterface = $commonInterface;
    }
    public function saveMan(Request $request,ManRequest $manRequest){
        date_default_timezone_set('Asia/Manila');
        DB::beginTransaction();
        try {
            $manRequestValidated = $manRequest->validated();
            $this->resourceInterface->create(Man::class,$manRequestValidated);
            DB::commit();
            return response()->json(['is_success' => 'true']);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;

        }
    }
    public function loadManByEcrId(Request $request){
        try {
            $data = [];
            $relations = [
                'rapidx_user_qc_inspector_operator'
            ];
            $conditions = [
                // 'ecrs_id' => $request->ecr_id
            ];
            $ecrDetail = $this->resourceInterface->readWithRelationsConditionsActive(Man::class,$data,$relations,$conditions);
            return DataTables($ecrDetail)
            ->addColumn('get_actions',function ($row){
                $result = '';
                $result .= '<center>';
                $result .= "<button class='btn btn-outline-info btn-sm mr-1' man-details-id='".$row->id."' id='btnManDetailsId'> <i class='fa-solid fa-pen-to-square'></i></button>";
                $result .= '</center>';
                return $result;
            })
            ->addColumn('qc_inspector_operator',function ($row){
                $result = '';
                $result .= $row->rapidx_user_qc_inspector_operator->name;
                return $result;
            })
            ->rawColumns([
                'get_actions',
                'qc_inspector_operator',
            ])
            ->make(true);
        } catch (Exception $e) {
            throw $e;

        }
    }
    public function getManById(Request $request){
        try {
            $data = [];
            $relations = [
            ];
            $conditions = [
                'id' => $request->manId
            ];
            $man = $this->resourceInterface->readWithRelationsConditionsActive(Man::class,$data,$relations,$conditions);
            return response()->json(['is_success' => 'true','man'=>$man[0]]);
        } catch (Exception $e) {
            throw $e;
        }
    }

}

