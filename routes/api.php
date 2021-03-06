<?php

use App\Http\Controllers\MovementController;
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

Route::group(['prefix' => '/v1'], function(){
    Route::post('/load-base-to-box', [MovementController::class, 'loadBaseToBox']);
    Route::post('/unload-base-to-box', [MovementController::class, 'unloadBaseToBox']);
    Route::get('/get-status-box', [MovementController::class, 'getStatusBox']);
    Route::get('/get-event-logs', [MovementController::class, 'getEventLogs']);
    Route::get('/get-custom-box-status', [MovementController::class, 'getCustomBoxStatus']);
    Route::post('/make-payment', [MovementController::class, 'makePayment']);
});
