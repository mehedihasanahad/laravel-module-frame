<?php

use App\Modules\ISP\Http\Controllers\ISPController;
use Illuminate\Support\Facades\Route;

Route::get('i-s-p', 'ISPController@welcome');

Route::post('isp-license/store',[ISPController::class,'store'])->name('isp-license.store');
Route::put('isp-license/{id}',[ISPController::class,'update'])->name('isp-license.update');
