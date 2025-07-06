<?php

namespace App\Http\Controllers;

use App\Models\Ecr;
use App\Models\Material;
use App\Models\PmiApproval;
use Illuminate\Http\Request;
use App\Models\MaterialApproval;
use Illuminate\Support\Facades\DB;
use App\Interfaces\CommonInterface;
use App\Http\Controllers\Controller;
use App\Interfaces\ResourceInterface;
use App\Http\Requests\MaterialRequest;
use App\Http\Requests\MaterialApprovalRequest;

class MaterialController extends Controller
{ //TODO: Remove View / Approval if Status is OK
    protected $resourceInterface;
    protected $commonInterface;
    public function __construct(ResourceInterface $resourceInterface,CommonInterface $commonInterface) {
        $this->resourceInterface = $resourceInterface;
        $this->commonInterface = $commonInterface;
    }
    public function loadEcrMaterialByStatus(Request $request){
        try {

            $data = [];
            $relations = [
                'pmi_approvals_pending.rapidx_user',
                'material.material_approvals_pending.rapidx_user',
                'material',
            ];
            $conditions = [
                'status' => 'OK',
                'category' => $request->category
            ];
            $ecr = $this->resourceInterface->readWithRelationsConditionsActive(Ecr::class,$data,$relations,$conditions);
            return DataTables($ecr)
            ->addColumn('get_actions',function ($row) use ($request){
                // Dropdown menu links
                $result = "";
                $result .= '<center>';
                $result .= '<div class="btn-group dropstart mt-4">';
                $result .= '<button type="button" class="btn btn-secondary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-expanded="false">';
                $result .= '    Action';
                $result .= '</button>';
                $result .= '<ul class="dropdown-menu">';
                if($row->material->status === "FORAPP" || $row->material->status === "PMIAPP"){
                    $result .= '   <li><button class="dropdown-item" type="button" material-status= "'.$row->material->status.'" ecrs-id="'.$row->id.'" materials-id="'.$row->material->id.'"id="btnViewMaterialById"><i class="fa-solid fa-eye"></i> &nbsp;View/Approval</button></li>';
                }
                if($row->material->status === "RUP" && $row->created_by === session('rapidx_user_id')){
                    $result .= '   <li><button class="dropdown-item" type="button" material-status= "'.$row->material->status.'" ecrs-id="'.$row->id.'" id="btnGetEcrId"><i class="fa-solid fa-edit"></i> &nbsp;Edit</button></li>';
                    $result .= '   <li><button class="dropdown-item" type="button" material-status= "'.$row->material->status.'" ecrs-id="'.$row->id.'" id="btnDownloadMaterialRef"><i class="fa-solid fa-upload"></i> &nbsp;Upload File</button></li>';
                }
                $result .= '</ul>';
                $result .= '</div>';
                $result .= '</center>';
                return $result;
            })
            ->addColumn('get_status',function ($row) use($request){
                //TODO: Read Approval Status, Tab Based on Department
                $currentApprover = $row->material->material_approvals_pending[0]['rapidx_user']['name'] ?? '';
                $getStatus = $this->getStatus($row->material->status);
                $result = '';
                $result .= '<center>';
                $result .= '<span class="'.$getStatus['bgStatus'].'"> '.$getStatus['status'].' </span>';
                $result .= '<br>';
                if( $currentApprover != ''){
                    $result .= '<span class="badge rounded-pill bg-danger"> '.$currentApprover.' </span>';
                }
                if( $row->material->status === 'PMIAPP' ){ //TODO: Last Status PMI Internal
                    $currentApprover = $row->pmi_approvals_pending[0]['rapidx_user']['name'] ?? '';
                    $approvalStatus = $row->material->approval_status;
                    $getPmiApprovalStatus = $this->commonInterface->getPmiApprovalStatus($approvalStatus);
                    $result .= '<span class="badge rounded-pill bg-danger"> '.$getPmiApprovalStatus['approvalStatus'].' '.$currentApprover.' </span>';
                }
                $result .= '</center>';
                $result .= '</br>';
                return $result;
            })
            ->addColumn('get_attachment',function ($row) use ($request){
                $result = '';
                $result .= '<center>';
                $result .= "<a class='btn btn-outline-danger btn-sm mr-1 mt-3 btn-get-ecr-id' ecrs-id='".$row->id."' id='btnViewMaterialRef'><i class='fa-solid fa-file-pdf'></i></a>";
                $result .= '</center>';
                return $result;
            })
            ->rawColumns([
                'get_actions',
                'get_status',
                'get_attachment',
            ])
            ->make(true);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function loadMaterialApprovalByMeterialId(Request $request){
        try {
            $materialsId = $request->materialsId ?? "";
            $data = [];
            $relations = [
                'rapidx_user'
            ];
            $conditions = [
                'materials_id' => $materialsId
            ];
            $pmiApproval = $this->resourceInterface->readCustomEloquent(MaterialApproval::class,$data,$relations,$conditions);
            $pmiApproval = $pmiApproval
            ->whereNotNull('rapidx_user_id')
            ->orderBy('counter','asc')
            ->get();
            return DataTables($pmiApproval)
            ->addColumn('get_count',function ($row) use(&$ctr){
                $ctr++;
                $result = '';
                $result .= $ctr;
                $result .= '</br>';
                return $result;
            })
            ->addColumn('get_approver_name',function ($row){
                $result = '';
                $result .= $row->rapidx_user['name'];
                $result .= '</br>';
                return $result;
            })
            ->addColumn('get_role',function ($row){
                $getApprovalStatus = $this->getApprovalStatus($row->approval_status);
                $result = '';
                $result .= '<center>';
                $result .= '<span class="badge rounded-pill bg-primary"> '.$getApprovalStatus['approvalStatus'].'</span>';
                $result .= '<center>';
                $result .= '</br>';
                return $result;
            })
            ->addColumn('get_status',function ($row){
                switch ($row->status) {

                    case 'PEN':
                        $status = 'PENDING';
                        $bgColor = 'badge rounded-pill bg-warning';
                        break;
                    case 'APP':
                        $status = 'APPROVED';
                        $bgColor = 'badge rounded-pill bg-success';
                        break;
                    case 'DIS':
                        $status = 'DISAPPROVED';
                        $bgColor = 'badge rounded-pill bg-danger';
                        break;
                    default:
                        $status = '---';
                        $bgColor = '';
                        break;
                }

                $result = '';
                $result .= '<center>';
                $result .= '<span class="'.$bgColor.'"> '.$status.' </span>';
                $result .= '<br>';
                $result .= '</br>';
                return $result;
            })
            ->rawColumns(['get_count','get_status','get_approver_name','get_role'])
            ->make(true);
            return response()->json(['is_success' => 'true']);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function saveMaterial(Request $request,MaterialRequest  $materialRequest,MaterialApprovalRequest $materialApprovalRequest){
        date_default_timezone_set('Asia/Manila');
        try {
            DB::beginTransaction();
            $materialRequest->all();
            $currentEcrsId =  $request->ecrs_id;
            $materialsId = $request->material_id;
            $materialRequest = $materialRequest->validated();
            $ecr = Ecr::where('id',$currentEcrsId)->get('internal_external');

            if ( isset($request->material_id)){
                $conditions = [
                   'id' => $materialsId
                ];
                 $currentMaterialId = $materialsId;
                $this->resourceInterface->updateConditions(Material::class,$conditions,$materialRequest);
            }else{
                $materialRequest['created_at'] = now();
                $insertMaterialById = $this->resourceInterface->create(Material::class,$materialRequest);
                $currentMaterialId = $insertMaterialById['data_id'];
            }

            if($ecr[0]->internal_external === "Internal") {
                $enggMateriaApprovalInEx = [
                    'ENGPB' => $request->engg_prepared_by,
                    'ENGCB' => $request->engg_checked_by,
                    'ENGAB' => $request->engg_approved_by,
                ];
                $prdnMateriaApprovalInEx = [];
            }
            if($ecr[0]->internal_external === "External") {
                $enggMateriaApprovalInEx = [
                    'MENGPB' => $request->main_engg_prepared_by,
                    'MENGCB' => $request->main_engg_checked_by,
                    'MENGAB' => $request->main_engg_approved_by,
                    'PENGPB' => $request->pro_engg_prepared_by,
                    'PENGCB' => $request->pro_engg_checked_by,
                    'PENGAB' => $request->pro_engg_approved_by,
                ];
                $prdnMateriaApprovalInEx = [
                    'PRDNPB' => $request->prdn_prepared_by,
                    'PRDNCB' => $request->prdn_checked_by,
                    'PRDNAP' =>  $request->prdn_approved_by, //TODO; CHANGE CODE
                ];
            }

            $materialApprovalTypes = [
                'PURPB' => $materialApprovalRequest->pr_approved_by,
                'PURCB' => $materialApprovalRequest->pr_checked_by,
                'PURAB' => $materialApprovalRequest->pr_prepared_by,
                'PPCPB' => $materialApprovalRequest->ppc_approved_by,
                'PPCCB' => $materialApprovalRequest->ppc_checked_by,
                'PPCAB' => $materialApprovalRequest->ppc_prepared_by,
                'EMSPB' => $materialApprovalRequest->ems_prepared_by,
                'EMSCB' => $materialApprovalRequest->ems_checked_by,
                'EMSAB' => $materialApprovalRequest->ems_approved_by,
                'LQCPB' => $materialApprovalRequest->qc_prepared_by,
                'LQCCB' => $materialApprovalRequest->qc_checked_by,
                'LQCAB' => $materialApprovalRequest->qc_approved_by,
            ];
            $qaMaterialApprovalTypes = [
                'QAPB' => $materialApprovalRequest->qa_prepared_by,
                'QACB' => $materialApprovalRequest->qa_checked_by,
                'QAAB' => $materialApprovalRequest->qa_approved_by,
            ];
            //Validate if Internal or Extenal
            //Merge the Material Approval
            $mergeMaterialApprovalRequest = array_merge($prdnMateriaApprovalInEx,$materialApprovalTypes,$enggMateriaApprovalInEx,$qaMaterialApprovalTypes);

            $materialApprovalRequestCtr = 0; //assigned counter
            $materialApprovalValidated = collect($mergeMaterialApprovalRequest)->flatMap(function ($users,$approvalStatus)
            use ($request,&$materialApprovalRequestCtr,$currentEcrsId){
                return collect($users)->map(function ($userId) use ($request,$approvalStatus,&$materialApprovalRequestCtr,$currentEcrsId){
                    return [
                        'ecrs_id' => $request->ecrs_id,
                        'materials_id' => $request->material_id,
                        'rapidx_user_id' => $userId == 0 ? NULL : $userId,
                        'approval_status' => $approvalStatus,
                        'counter' => $materialApprovalRequestCtr++,
                        'created_at' => now(),
                    ];
                });

            })->toArray();
            MaterialApproval::where('materials_id',$currentMaterialId)->delete();
            MaterialApproval::insert($materialApprovalValidated);
            $materialApproval =  MaterialApproval::whereNotNull('rapidx_user_id')
            ->where('materials_id', $currentMaterialId)->first();
            if ($materialApproval) {
                $materialApproval->update(['status' => 'PEN']);
                Material::where('id', $currentMaterialId)->first()
                ->update([
                    'approval_status' => $materialApproval->approval_status,
                    'status' => 'FORAPP', //FOR APPROVAL
                ]);
            }
            //Reset the PMI Approval
            PmiApproval::whereNotNull('rapidx_user_id')
            ->where('ecrs_id', $currentEcrsId)
            ->update([
                'status' => '-',
                'remarks' => '',
            ]);
            //Update Pending PMI Approval
            $firstPmiApproval =  PmiApproval::whereNotNull('rapidx_user_id')
            ->where('ecrs_id', $currentEcrsId)
            ->first();
            if ($firstPmiApproval) {
                $firstPmiApproval->update(['status' => 'PEN']);
            }
            DB::commit();
            return response()->json(['is_success' => 'true']);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
    public function getMaterialEcrById(Request $request){
        try {
            // return 'dasads';
            $conditions = [
                'ecrs_id' => $request->ecrId,
            ];
            $relations = [
                'ecr',
                'material_approvals',
            ];
           $material = $this->resourceInterface->readWithRelationsConditionsActive(Material::class,[],$relations,$conditions);
           if( filled($material) ){
                $materialApprovalCollection = collect($material[0]->material_approvals)->groupBy('approval_status')->toArray();
                return response()->json([
                    'isSuccess' => 'true',
                    'material' => $material,
                    'internalExternal' => $material[0]->ecr['internal_external'],
                    'materialApprovalCollection' => $materialApprovalCollection,
                ]);
           }

        } catch (Exception $e) {
            throw $e;
        }
    }
    public function uploadMaterialRef(Request $request){
        try {
            date_default_timezone_set('Asia/Manila');
            DB::beginTransaction();
            if($request->hasfile('material_ref') ){
                $arrUploadFile = $this->commonInterface->uploadFile($request->material_ref,$request->ecrsId,'material');
                $impOriginalFilename = implode(' | ',$arrUploadFile['arr_original_filename']);
                $impFilteredDocumentName = implode(' | ',$arrUploadFile['arr_filtered_document_name']);

                $conditions = [
                   'ecrs_id' =>  $request->ecrsId
                ];
                // return 'true';
                $materialRequestValidated['original_filename'] = $impOriginalFilename;
                $materialRequestValidated['filtered_document_name'] = $impFilteredDocumentName;
                $this->resourceInterface->updateConditions(Material::class,$conditions,$materialRequestValidated);
            }
            DB::commit();
            return response()->json(['is_success' => 'true']);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
    public function getMaterialRefByEcrsId(Request $request){
        try {
            $ecrsId = $request->ecrsId;
            $conditions = [
                'ecrs_id' => $ecrsId,
            ];
            $data = $this->resourceInterface->readCustomEloquent(Material::class,[],[],$conditions);
            $materialRefByEcrsId = $data
            ->get([
                'id',
                'ecrs_id',
                'original_filename',
            ]);
            return response()->json([
                'isSuccess' => 'true',
                'originalFilename'=> explode(' | ',$materialRefByEcrsId[0]->original_filename),
                'ecrsId'=> encrypt($materialRefByEcrsId[0]->ecrs_id),
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function viewMaterialRef(Request $request){
        try {
            $ecrsId = decrypt($request->ecrsId);
            $conditions = [
                'ecrs_id' => $ecrsId,
            ];
            $data = $this->resourceInterface->readCustomEloquent(Material::class,[],[],$conditions);
            $materialRefByEcrsId = $data
            ->get([
                'filtered_document_name',
                'file_path',
            ]);
            if(count($materialRefByEcrsId) != 0){
                $arrFilteredDocumentName = explode(' | ' ,$materialRefByEcrsId[0]->filtered_document_name);
                $selectedFilteredDocumentName =  $arrFilteredDocumentName[$request->index];
                $filePathWithEcrsId = $materialRefByEcrsId[0]->file_path."/".$ecrsId."/".$selectedFilteredDocumentName;
                $pdfPath = storage_path("app/public/".$filePathWithEcrsId."");
                $this->commonInterface->viewPdfFile($pdfPath);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function saveMaterialApproval(Request $request){
        try {
            date_default_timezone_set('Asia/Manila');
            DB::beginTransaction();
            $selectedId = $request->selectedId;
            //Get Current Ecr Approval is equal to Current Session
            $materialApprovalCurrent = MaterialApproval::where('materials_id',$selectedId)
            ->whereNotNull('rapidx_user_id')
            ->where('status','PEN')
            ->first();
            if($materialApprovalCurrent->rapidx_user_id != session('rapidx_user_id')){
                return response()->json(['isSuccess' => 'false','msg' => 'You are not the current approver !'],500);
            }
            //Update the Material Approval Status
            $materialApprovalCurrent->update([
                'status' => $request->status,
                'remarks' => $request->remarks,
            ]);
            //Get the ECR Approval Status & Id, Update the Approval Status as PENDING
           $materialApproval = MaterialApproval::where('materials_id',$selectedId)
           ->whereNotNull('rapidx_user_id')
           ->where('status','-')
           ->limit(1)
           ->get(['id','approval_status']);
            if ( count($materialApproval) != 0){
                $materialApprovalValidated = [
                    'status' => 'PEN',
                ];
                $materialApprovalConditions = [
                    'id' => $materialApproval[0]->id,
                ];
                $this->resourceInterface->updateConditions(MaterialApproval::class,$materialApprovalConditions,$materialApprovalValidated);
                //Update the ECR Approval Status
                $enviromentConditions = [
                    'id' => $selectedId,
                ];
                $enviromentValidated = [
                    'approval_status' => $materialApproval[0]->approval_status,
                ];
                $this->resourceInterface->updateConditions(Material::class,$enviromentConditions,$enviromentValidated);
            }else{
                $enviromentConditions = [
                    'id' => $selectedId,
                ];
                $enviromentValidated = [
                    'status' => 'PMIAPP',
                    'approval_status' => 'CB',
                ];
                $this->resourceInterface->updateConditions(Material::class,$enviromentConditions,$enviromentValidated);
            }
             //DISAPPROVED ECR
             if($request->status === "DIS"){
                $enviromentConditions = [
                    'id' => $selectedId,
                ];
                $enviromentValidated = [
                    'status' => 'DIS',
                    'approval_status' => 'DIS', //Repeat the status
                ];
                $this->resourceInterface->updateConditions(Material::class,$enviromentConditions,$enviromentValidated);
            }
            DB::commit();
            return response()->json(['is_success' => 'true']);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
    //Common Function
    public function getStatus($status){

        try {
             switch ($status) {
                 case 'RUP':
                     $status = 'For Requestor Update';
                     $bgStatus = 'badge rounded-pill bg-primary';
                     break;
                case 'FORAPP':
                    $status = 'For Approval';
                    $bgStatus = 'badge rounded-pill bg-warning';
                    break;
                case 'PMIAPP':
                    $status = 'PMI Approval';
                    $bgStatus = 'badge rounded-pill bg-info';
                    break;
                case 'OK':
                    $status = 'Completed';
                    $bgStatus = 'badge rounded-pill bg-success';
                    break;
                 case 'DIS':
                     $status = 'DISAPPROVED';
                     $bgStatus = 'badge rounded-pill bg-danger';
                     break;
                 default:
                     $status = '';
                     $bgStatus = '';
                     break;
             }
             return [
                 'status' => $status,
                 'bgStatus' => $bgStatus,
             ];
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function getApprovalStatus($approval_status){
        try {
             switch ($approval_status) {
                case 'RUP':
                    $approvalStatus = 'Requestor ECR Update:';
                    break;
                case 'PRNDPB':
                    $approvalStatus = 'Production Prepared by:';
                    break;
                case 'PRNDCB':
                    $approvalStatus = 'Production Checked by:';
                    break;
                case 'PRNDAP':
                    $approvalStatus = 'Production Approved by:';
                    break;
                case 'PURPB':
                    $approvalStatus = 'Purchasing Prepared by:';
                    break;
                case 'PURCB':
                    $approvalStatus = 'Purchasing Checked by:';
                    break;
                case 'PURAB':
                    $approvalStatus = 'Purchasing Approved by:';
                    break;
                case 'PPCPB':
                    $approvalStatus = 'PPC Prepared by:';
                    break;
                case 'PPCCB':
                    $approvalStatus = 'PPC Checked by:';
                    break;
                case 'PPCAB':
                    $approvalStatus = 'PPC Approved by:';
                    break;
                case 'EMSPB':
                    $approvalStatus = 'EMS Prepared by:';
                    break;
                case 'EMSCB':
                    $approvalStatus = 'EMS Checked by:';
                    break;
                case 'EMSAB':
                    $approvalStatus = 'EMS Approved by:';
                    break;
                case 'LQCPB':
                    $approvalStatus = 'QC Prepared by';
                    break;
                case 'LQCCB':
                    $approvalStatus = 'QC Checked by';
                    break;
                case 'LQCAB':
                    $approvalStatus = 'QC Approved by';
                    break;
                case 'MENGPB':
                    $approvalStatus = 'Maintenance Engg Prepared by';
                    break;
                case 'MENGCB':
                    $approvalStatus = 'Maintenance Engg Checked by';
                    break;
                case 'MENGAB':
                    $approvalStatus = 'Maintenance Engg Approved by';
                    break;
                case 'PENGPB':
                    $approvalStatus = 'Process Engg Prepared by';
                    break;
                case 'PENGCB':
                    $approvalStatus = 'Process Engg Checked by';
                    break;
                case 'PENGAB':
                    $approvalStatus = 'Process Engg Approved by';
                    break;
                case 'QAPB':
                    $approvalStatus = 'QA Prepared by';
                    break;
                case 'QACB':
                    $approvalStatus = 'QA Checked by';
                    break;
                case 'QAAB':
                    $approvalStatus = 'QA Approved by';
                    break;
                 default:
                     $approvalStatus = '';
                     break;
             }
             return [
                 'approvalStatus' => $approvalStatus,
             ];

        } catch (Exception $e) {
            throw $e;
        }
    }

}
