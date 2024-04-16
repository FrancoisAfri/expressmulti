<?php

use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::post('/auth/register', [App\Http\Controllers\Api\AuthController::class, 'register']);

Route::post('/auth/login', [App\Http\Controllers\Api\AuthController::class, 'authenticateUser']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/me', function(Request $request) {
        return auth()->user();
    });
	

});

    Route::post('/auth/logout', [ApiAuthController::class, 'logout']);
	Route::prefix('/restaurant')->group(function(){
		Route::post('table/close/{table}', [DashboardController::class, 'closeTableAPI']);
		Route::post('service/close/{service}', [DashboardController::class, 'closeServiceAPI']);
		Route::post('request/close/{close}', [DashboardController::class, 'closeRequestAPI']);
		Route::post('order/close/{order}', [DashboardController::class, 'closeOrderAPI']);
		Route::post('request-denied/close/{close}', [DashboardController::class, 'closeDeniedRequestAPI']);
		Route::post('delete-order/{order}', [DashboardController::class, 'deleteOrderAPI']);
		Route::get('/get-services/{waiter}',[DashboardController::class, 'getOpenServicesPerWaiter']);
		Route::get('get-tables/{waiter}',[DashboardController::class, 'getTablesWaiter']);
		Route::get('get-table-status/{table}',[DashboardController::class, 'getTableStatus']);
		Route::get('get-table-nickname/{table}',[DashboardController::class, 'getTableNickname']);
		Route::post('change-waiter-status/{user}',[DashboardController::class, 'changeWaiterStatus']);
	});


