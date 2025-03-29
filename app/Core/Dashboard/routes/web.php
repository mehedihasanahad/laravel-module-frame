<?php

use App\Core\Dashboard\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::group(['module'=>'Dashboard', 'namespace'=>'App\Core\Dashboard\Controllers','middleware'=>['web','auth']],function(){
    Route::get('dashboard/',[DashboardController::class, 'index']);

    Route::post('/dashboard/get-chart-data',[DashboardController::class,'getChartData']);
});
