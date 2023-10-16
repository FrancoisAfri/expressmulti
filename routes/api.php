<?php

use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\Api\BookingsController;
use App\Http\Controllers\Dashboard;
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

Route::post('/auth/register', [ApiAuthController::class, 'register']);

Route::post('/auth/login', [ApiAuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/me', function(Request $request) {
        return auth()->user();
    });

    Route::post('/auth/logout', [ApiAuthController::class, 'logout']);
});

Route::get('/booking',[BookingsController::class, 'index']);

//Route::get('/calculateMonthlyProfit',[App\Http\Controllers\Dashboard\::class, 'calculateMonthlyProfit']);
Route::get('getBookingsDash', [DashboardController::class, 'calculateMonthlyProfit']);

Route::prefix('/booking')->group(function(){
    Route::post('/store',[BookingsController::class, 'store']);
    Route::get('/{id}',[BookingsController::class, 'show']);
    Route::put('/{id}',[BookingsController::class, 'update']);
    Route::delete('/{id}',[BookingsController::class, 'destroy']);
});


