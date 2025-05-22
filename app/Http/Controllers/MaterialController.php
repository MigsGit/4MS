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
    public function saveMaterial(Request $request, MaterialRequest $materialRequest){
        date_default_timezone_set('Asia/Manila');
        try {
            $materialRequest = $materialRequest->validated();
            $materialRequest ['material_supplier'] = 1;
            $materialRequest ['material_color'] = 1;
            $materialRequest ['material_sample'] = 1;
            return $materialRequest;
            $this->resourceInterface->create(Material::class,$materialRequest);
            /*
                 "ecrs_id" => 'required',
                "pd_material" => 'required',
                "msds" => 'required',
                "icp" => 'required',
                "qoutation" => 'required',
                // "material_supplier" => 'required',
                // "material_color" => 'required',
                // "material_sample" => 'required',
                "coc" => 'required',
                // "rohs" => 'required',
                // "remarks" => 'required',
            */
            return response()->json(['is_success' => 'true']);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
