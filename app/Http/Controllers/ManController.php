<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManController extends Controller
{
    public function saveMan(Request $request){
        return 'true' ;
        try {
            return response()->json(['is_success' => 'true']);
        } catch (Exception $e) {
            return response()->json(['is_success' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }
}
