<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
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
    public function saveMaterial(Request $request,MaterialRequest  $materialRequest){
        date_default_timezone_set('Asia/Manila');
        try {

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

}
