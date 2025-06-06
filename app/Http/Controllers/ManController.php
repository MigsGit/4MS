<?php

namespace App\Http\Controllers;

use App\Models\Man;
use App\Models\ManChecklist;
use Illuminate\Http\Request;
use App\Http\Requests\ManRequest;
use Illuminate\Support\Facades\DB;
use App\Interfaces\CommonInterface;
use App\Http\Controllers\Controller;
use App\Models\DropdownMasterDetail;
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
        try {
            date_default_timezone_set('Asia/Manila');
            DB::beginTransaction();
            $manModel = Man::class;
            $manRequestValidated = $manRequest->validated();
            if ( isset($request->man_id) ){ //Edit
                $manRequestValidated['trainer'] = $request->trainer;
                $manRequestValidated['trainer_sample_size'] = $request->trainer_sample_size;
                $manRequestValidated['trainer_result'] = $request->trainer_result;
                $manRequestValidated['lqc_supervisor'] = $request->lqc_supervisor;
                $manRequestValidated['lqc_sample_size'] = $request->lqc_sample_size;
                $manRequestValidated['lqc_result'] = $request->lqc_result;
                $manRequestValidated['process_change_factor'] = $request->process_change_factor;
                $conditions = [
                    'id' => $request->man_id
                ];
                $this->resourceInterface->updateConditions($manModel,$conditions,$manRequestValidated);
            }else{ //Add
                $this->resourceInterface->create($manModel,$manRequestValidated);
            }
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
                'rapidx_user_qc_inspector_operator',
                'rapidx_user_trainer',
                'rapidx_user_lqc_supervisor',
            ];
            $conditions = [
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
                $result .= $row->rapidx_user_qc_inspector_operator->name ?? null;
                return $result;
            })
            ->addColumn('trainer',function ($row){
                $result = '';
                $result .= $row->rapidx_user_trainer->name ?? null;
                return $result;
            })
            ->addColumn('lqc_supervisor',function ($row){
                $result = '';
                $result .= $row->rapidx_user_lqc_supervisor->name ?? null;
                return $result;
            })
            ->addColumn('trainer_result',function ($row){
                $result = '';
                switch ($row->trainer_result) {
                    case 'OK':
                        $bgColor = 'bg-success text-white';
                        break;
                    case 'NG':
                        $bgColor = 'bg-danger text-white';
                        break;
                    default:
                        $bgColor = 'bg-secondary text-white';
                        break;
                }
                $result .='<span class="badge '.$bgColor.'"> '.$row->trainer_result.' </span>';
                return $result;
            })
            ->addColumn('lqc_result',function ($row){
                $result = '';
                switch ($row->lqc_result) {
                    case 'PASSED':
                        $bgColor = 'bg-success text-white';
                        break;
                    case 'FAILED':
                        $bgColor = 'bg-danger text-white';
                        break;
                    default:
                        $bgColor = 'bg-secondary text-white';
                        break;
                }
                $result .='<span class="badge '.$bgColor.'"> '.$row->lqc_result.' </span>';
                return $result;
            })
            ->rawColumns([
                'get_actions',
                'qc_inspector_operator',
                'trainer',
                'trainer_result',
                'lqc_supervisor',
                'lqc_result',
            ])
            ->make(true);
        } catch (Exception $e) {
            throw $e;

        }
    }
    public function loadManChecklist(Request $request){
        try {
            $data = [];
            $relations = [];
            $conditions = [
                'dropdown_masters_id' => $request->dropdown_masters_id
            ];
            $man_checklist_data = [];
            $man_checklist_relations = [];
            $man_checklist_conditions = [
                'man_id' => 1,
            ];

            $dropdownMasterDetail = $this->resourceInterface->readCustomEloquent(DropdownMasterDetail::class,$data,$relations,$conditions);
            $dropdownMasterDetail->orderBy('dropdown_masters_details');
            // return $classificationRequirement; dropdown_masters_details
            $manChecklist = $this->resourceInterface->readWithRelationsConditionsActive(ManChecklist::class,$man_checklist_data,$man_checklist_relations,$man_checklist_conditions);
            return DataTables($dropdownMasterDetail)
            ->addColumn('get_actions',function ($row) use($manChecklist) {
                // return 'true';
                $manChecklistCollection = collect($manChecklist);
                $manChecklistMatch = $manChecklistCollection->firstWhere('dropdown_master_details_id', $row->id);
                $manChecklistId = $manChecklistMatch['id'] ?? '';
                $cSelected = $manChecklistMatch['decision'] === 'C' ? 'selected' : '';
                $xSelected = $manChecklistMatch['decision'] === 'X' ? 'selected' : '';
                if($manChecklistId === ''){
                    $isValid = "is-invalid";
                    $emptySelected = "selected";
                }else{
                    $isValid = "";
                    $emptySelected = "";
                }
                $result = '';
                $result .= '<center>';
                $result .= "<select id='btnChangeManChecklistDecision' class='form-select btn-change-ecr-req-decision ".$isValid."' ref=btnChangeManChecklistDecision man-checklists-id ='".$manChecklistId."' dropdown-master-details-id='".$row->id."'>";
                $result .=  "<option value='' ".$emptySelected." disabled> --Select-- </option>";
                $result .=  "<option value='C' ".$cSelected."> √ </option>";
                $result .=  "<option value='X' ".$xSelected."> X </option>";
                $result .=  "</select>";
                $result .= '</center>';
                return $result;
            })
            ->rawColumns([
                'get_actions',
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
    public function manChecklistDecisionChange(Request $request){
        try {
            if( isset($request->manChecklistsId) ){ //edit
                // return 'edit';
                $conditions = [
                    'id' => $request->manChecklistsId
                ];
                $data = [
                    // 'dropdown_master_details_id' => $request->dropdownMasterDetailsId,
                    'decision' => $request->manChecklistValue,
                ];

                $this->resourceInterface->updateConditions(ManChecklist::class,$conditions,$data);
            }else{ //add
                $data = [
                    'dropdown_master_details_id' => $request->dropdownMasterDetailsId,
                    'decision' => $request->manChecklistValue,
                    'man_id' => 1, //TODO: Get Man Id from Params
                ];
                $this->resourceInterface->create(ManChecklist::class,$data);
            }

            return response()->json(['is_success' => 'true']);
        } catch (Exception $e) {

            throw $e;
        }
    }

}

