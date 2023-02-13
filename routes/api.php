<?php

use App\Http\Controllers\Api\V1\OfferController;
use App\Http\Controllers\Api\V1\ReservationController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1/reservations', 'namespace' => 'App\Http\Controllers\Api\V1'], function () {
    Route::get('/', 'ReservationController@index');
    Route::post('/', 'ReservationController@store');
    Route::delete('/', 'ReservationController@destroy');
});


Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\V1'], function() {
    Route::get('roomoffers', [OfferController::class, 'index']);
});
