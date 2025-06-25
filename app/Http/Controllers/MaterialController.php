<?php

namespace App\Http\Controllers;

use App\Models\Ecr;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Interfaces\CommonInterface;
use App\Http\Controllers\Controller;
use App\Interfaces\ResourceInterface;
use App\Http\Requests\MaterialRequest;

class MaterialController extends Controller
{
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
                if($row->created_by === session('rapidx_user_id')){
                    $result .= '   <li><button class="dropdown-item" type="button" ecr-id="'.$row->id.'" id="btnGetEcrId"><i class="fa-solid fa-edit"></i> &nbsp;Edit</button></li>';
                }
                if($row->pmi_approvals_pending[0]->rapidx_user->id === session('rapidx_user_id')){
                    $result .= '   <li><button class="dropdown-item" type="button" ecr-id="'.$row->id.'" id="btnViewEcrById"><i class="fa-solid fa-eye"></i> &nbsp;View/Approval</button></li>';
                }
                if($row->material[0]->status === "RUP"){
                    $result .= '   <li><button class="dropdown-item" type="button" ecr-id="'.$row->id.'" id="btnDownloadMaterialRef"><i class="fa-solid fa-upload"></i> &nbsp;Upload File</button></li>';
                }
                $result .= '</ul>';
                $result .= '</div>';
                $result .= '</center>';
                return $result;
            })
            ->addColumn('get_status',function ($row) use($request){
                $currentApprover = $row->pmi_approvals_pending[0]['rapidx_user']['name'] ?? '';
                $getStatus = $this->getStatus($row->material[0]->status);
                $result = '';
                $result .= '<center>';
                $result .= '<span class="'.$getStatus['bgStatus'].'"> '.$getStatus['status'].' </span>';
                $result .= '<br>';
                $result .= '<span class="badge rounded-pill bg-danger"> '.$currentApprover.' </span>';

                if( $row->material[0]->status === 'OK' ){ //TODO: Last Status before PMI Internal
                    $approvalStatus = $row->material[0]->approval_status;
                    $getApprovalStatus = $this->getPmiApprovalStatus($approvalStatus);
                    $result .= '<span class="badge rounded-pill bg-danger"> '.$getApprovalStatus['approvalStatus'].' '.$currentApprover.' </span>';
                }
                $result .= '</center>';
                $result .= '</br>';
                return $result;
            })
            ->addColumn('get_attachment',function ($row) use ($request){
                $result = '';
                $result .= '<center>';
                $result .= "<a class='btn btn-outline-danger btn-sm mr-1 mt-3 btn-get-ecr-id' ecr-id='".$row->id."' id='btnViewMaterialRef'><i class='fa-solid fa-file-pdf'></i></a>";
                $result .= '</center>';
                return $result;
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
    public function saveMaterial(Request $request,MaterialRequest  $materialRequest){
        date_default_timezone_set('Asia/Manila');
        try {
            return $materialRequest->all();
            $materialRequest = $materialRequest->validated();

            if ( isset($request->material_id)){
                $conditions = [
                   'id' => $request->material_id
                ];
                $this->resourceInterface->updateConditions(Material::class,$conditions,$materialRequest);
            }else{
                $materialRequest['created_at'] = now();
                $this->resourceInterface->create(Material::class,$materialRequest);
            }
            $request->ppcApprovedBy;
            $request->ppcCheckedBy;
            $request->ppcPreparedBy;
            $request->prApprovedBy;
            $request->prCheckedBy;
            $request->prPreparedBy;
            return response()->json(['is_success' => 'true']);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function getMaterialEcrById(Request $request){
        try {
            $conditions = [
                'ecrs_id' => $request->ecrId,
            ];
            $material = $this->resourceInterface->readWithRelationsConditionsActive(Material::class,[],[],$conditions);
            return response()->json([
                'is_success' => 'true',
                'material' => $material,
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function getPmiApprovalStatus($approval_status){
        try {
             switch ($approval_status) {
                 case 'PB':
                     $approvalStatus = 'Prepared by:';
                     break;
                 case 'CB':
                     $approvalStatus = 'Checked by:';
                     break;
                 case 'AP':
                     $approvalStatus = 'Approved by:';
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
    public function getStatus($status){

        try {
             switch ($status) {
                 case 'RUP':
                     $status = 'For Requestor Update';
                     $bgStatus = 'badge rounded-pill bg-primary';
                     break;
                 case 'QA':
                     $status = 'QA Approval';
                     $bgStatus = 'badge rounded-pill bg-warning';
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
                'filter.0ed_document_name',
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

}
