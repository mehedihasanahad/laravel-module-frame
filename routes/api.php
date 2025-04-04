<?php

use App\Core\Auth\Http\Controllers\Api\v1\GlobalApiController;
use App\Core\Auth\Http\Controllers\Api\v1\SignUpApiController;
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

Route::group(['prefix' => 'v1'], function () {
    Route::get('/districts/{divisionId}', [GlobalApiController::class, 'getDistricts'])->name('get-districts');
    Route::get('/upzilas/{districtId}', [GlobalApiController::class, 'getUpzilas'])->name('get-upzilas');
});

Route::get('/get-division', [SignUpApiController::class, 'getDivision']);