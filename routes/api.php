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

Route::get('check_session', function (Request $request) {
    // return session('rapidx_name');
    session_start();
    if($_SESSION){
        return true;
    }else{
        return false;
    }
});
//session_start in Authenticate Middleware, then passed it to the queries to get session
Route::middleware('auth')->group(function(){

    Route::controller(EcrController::class)->group(function () {
        Route::post('save_ecr', 'saveEcr')->name('save_ecr');
        Route::post('save_ecr_details', 'saveEcrDetails')->name('save_ecr_details');
        Route::post('save_ecr_approval', 'saveEcrApproval')->name('save_ecr_approval');
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

    Route::controller(CommonController::class)->group(function (): void {
        Route::post('save_pmi_internal_approval', 'savePmiInternalApproval')->name('save_pmi_internal_approval');
        Route::get('get_rapidx_user_by_id_opt', 'getRapidxUserByIdOpt')->name('get_rapidx_user_by_id_opt');
        Route::get('get_current_approver_session', 'getCurrentApproverSession')->name('get_current_approver_session');
        Route::get('get_current_pmi_internal_approver', 'getCurrentPmiInternalApprover')->name('get_current_pmi_internal_approver');
        Route::get('load_pmi_internal_approval_summary', 'loadPmiInternalApprovalSummary')->name('load_pmi_internal_approval_summary');
    });

    Route::controller(SettingsController::class)->group(function () {
        Route::get('get_user_master', 'getUserMaster')->name('get_user_master');
        Route::get('load_dropdown_master_details', 'loadDropdownMasterDetails')->name('load_dropdown_master_details');
        Route::get('get_dropdown_master', 'getDropdownMaster')->name('get_dropdown_master');
        Route::get('get_dropdown_master_details_id', 'getDropdownMasterDetailsId')->name('get_dropdown_master_details_id');

        Route::post('save_dropdown_master_details', 'saveDropdownMasterDetails')->name('save_dropdown_master_details');
    });

    Route::controller(ManController::class)->group(function () {
        Route::post('save_man', 'saveMan')->name('save_man');
        Route::get('load_man_by_ecr_id', 'loadManByEcrId')->name('load_man_by_ecr_id');
        Route::get('load_man_checklist', 'loadManChecklist')->name('load_man_checklist');
        Route::get('get_man_by_id', 'getManById')->name('get_man_by_id');
        Route::get('man_checklist_decision_change', 'manChecklistDecisionChange')->name('man_checklist_decision_change');
    });

    Route::controller(MaterialController::class)->group(function () {
        Route::post('save_material', 'saveMaterial')->name('save_material');
        Route::get('get_material_ecr_by_id', 'getMaterialEcrById')->name('get_material_ecr_by_id');
    });
});
