<?php

use App\Modules\MNO\Http\Controllers\MNOController;
use Illuminate\Support\Facades\Route;


Route::post('isp-license/store',[MNOController::class,'store'])->name('mno-license.store');
