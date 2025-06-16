<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Environment;
use App\Interfaces\CommonInterface;
use App\Interfaces\ResourceInterface;
use App\Http\Requests\EnvironmentRequest;

class EnvironmentController extends Controller
{
    protected $resourceInterface;
    protected $commonInterface;
    public function __construct(ResourceInterface $resourceInterface,CommonInterface $commonInterface) {
        $this->resourceInterface = $resourceInterface;
        $this->commonInterface = $commonInterface;
    }
    public function uploadEnvironmentRef(EnvironmentRequest $environmentRequest){
        date_default_timezone_set('Asia/Manila');
        DB::beginTransaction();
        try {
            if($environmentRequest->hasfile('environment_ref') ){
                $arrUploadFile = $this->commonInterface->uploadFile($environmentRequest->environment_ref,$environmentRequest->ecrsId,'environment');
                $impOriginalFilename = implode(' | ',$arrUploadFile['arr_original_filename']);
                $impFilteredDocumentName = implode(' | ',$arrUploadFile['arr_filtered_document_name']);

                $conditions = [
                   'ecrs_id' =>  $environmentRequest->ecrs_id
                ];
                $environmentRequestValidated['original_filename'] = $impOriginalFilename;
                $environmentRequestValidated['filtered_document_name'] = $impFilteredDocumentName;
                $this->resourceInterface->updateConditions(Environment::class,$conditions,$environmentRequestValidated);
            }
            DB::commit();
            return response()->json(['is_success' => 'true']);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
    public function viewEnvironmentRef(Request $request){
        try {
            $ecrsId = decrypt($request->ecrsId);
            $conditions = [
                'ecrs_id' => $ecrsId,
            ];
            $data = $this->resourceInterface->readCustomEloquent(Environment::class,[],[],$conditions);
            $environmentRefByEcrsId = $data
            ->get([
                'filtered_document_name',
                'file_path',
            ]);
            if(count($environmentRefByEcrsId) != 0){
                $arrFilteredDocumentName = explode(' | ' ,$environmentRefByEcrsId[0]->filtered_document_name);
                $selectedFilteredDocumentName =  $arrFilteredDocumentName[$request->index];
                $filePathWithEcrsId = $environmentRefByEcrsId[0]->file_path."/".$ecrsId."/".$selectedFilteredDocumentName;
                $pdfPath = storage_path("app/public/".$filePathWithEcrsId."");
                $this->commonInterface->viewPdfFile($pdfPath);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function getEnvironmentRefByEcrsId(Request $request){
        try {
            $ecrsId = $request->ecrsId;
            $conditions = [
                'ecrs_id' => $ecrsId,
            ];
            $data = $this->resourceInterface->readCustomEloquent(Environment::class,[],[],$conditions);
            $environmentRefByEcrsId = $data
            ->get([
                'id',
                'ecrs_id',
                'original_filename',
            ]);
            return response()->json([
                'isSuccess' => 'true',
                'originalFilename'=> explode(' | ',$environmentRefByEcrsId[0]->original_filename),
                'ecrsId'=> encrypt($environmentRefByEcrsId[0]->ecrs_id),
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }

}
