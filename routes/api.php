<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EcrController;
use App\Http\Controllers\ManController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EdocsController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\SettingsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::controller(CommonController::class)->group(function () {
    Route::get('get_rapidx_user_by_id_opt', 'getRapidxUserByIdOpt')->name('get_rapidx_user_by_id_opt');
});
Route::controller(SettingsController::class)->group(function () {
    Route::get('get_user_master', 'getUserMaster')->name('get_user_master');
    Route::get('load_dropdown_master_details', 'loadDropdownMasterDetails')->name('load_dropdown_master_details');
    Route::get('get_dropdown_master', 'getDropdownMaster')->name('get_dropdown_master');
    Route::get('get_dropdown_master_details_id', 'getDropdownMasterDetailsId')->name('get_dropdown_master_details_id');

    Route::post('save_dropdown_master_details', 'saveDropdownMasterDetails')->name('save_dropdown_master_details');
});

Route::controller(EcrController::class)->group(function () {
    Route::post('save_ecr', 'saveEcr')->name('save_ecr');
    Route::post('save_ecr_details', 'saveEcrDetails')->name('save_ecr_details');
    Route::get('get_dropdown_master_by_opt', 'getDropdownMasterByOpt')->name('get_dropdown_master_by_opt');
    Route::get('load_ecr', 'loadEcr')->name('load_ecr');
    Route::get('load_ecr_by_status', 'loadEcrByStatus')->name('load_ecr_by_status');
    Route::get('load_ecr_details_by_ecr_id', 'loadEcrDetailsByEcrId')->name('load_ecr_details_by_ecr_id');
    Route::get('load_ecr_requirements', 'loadEcrRequirements')->name('load_ecr_requirements');
    Route::get('get_ecr_by_id', 'getEcrById')->name('get_ecr_by_id');
    Route::get('get_ecr_details_id', 'getEcrDetailsId')->name('get_ecr_details_id');
    Route::get('ecr_req_decision_change', 'ecrReqDecisionChange')->name('ecr_req_decision_change');
    Route::get('load_ecr_approval_summary', 'loadEcrApprovalSummary')->name('load_ecr_approval_summary');
});

Route::controller(ManController::class)->group(function () {
    Route::post('save_man', 'saveMan')->name('save_man');
    Route::get('load_man_by_ecr_id', 'loadManByEcrId')->name('load_man_by_ecr_id');
    Route::get('get_man_by_id', 'getManById')->name('get_man_by_id');
});

Route::controller(MaterialController::class)->group(function () {
    Route::post('save_material', 'saveMaterial')->name('save_material');
    Route::get('get_material_ecr_by_id', 'getMaterialEcrById')->name('get_material_ecr_by_id');
});
