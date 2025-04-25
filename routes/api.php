<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EcrController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EdocsController;
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

Route::controller(SettingsController::class)->group(function () {
    Route::get('get_user_master', 'getUserMaster')->name('get_user_master');
});
Route::controller(EcrController::class)->group(function () {
    Route::get('get_dropdown_master_by_opt', 'getDropdownMasterByOpt')->name('get_dropdown_master_by_opt');
});
