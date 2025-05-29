<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommonController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get('check_user', function (Request $request) {
    session_start();
    if($_SESSION){
        session([
            'rapidx_user_id' => $_SESSION["rapidx_user_id"],
            'rapidx_name' => $_SESSION["rapidx_name"],
            'rapidx_username' => $_SESSION["rapidx_username"],
            'rapidx_user_level_id' => $_SESSION["rapidx_user_level_id"],
            'rapidx_email' => $_SESSION["rapidx_email"],
            'rapidx_department_id' => $_SESSION["rapidx_department_id"],
            'rapidx_employee_number' => $_SESSION["rapidx_employee_number"],
        ]);
        return session()->all();
    }else{
        return false;
    }
});
Route::controller(CommonController::class)->group(function () {
    Route::get('get_current_approver_session', 'getCurrentApproverSession')->name('get_current_approver_session');
});
//NOTE This Query should place under the Route Get to avoid the Route Not Found !
Route::get('/{any}', function(){
    return view('welcome');
})->where('any','.*')->name('login');

