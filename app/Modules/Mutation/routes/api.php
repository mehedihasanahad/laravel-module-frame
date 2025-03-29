<?php

use App\Modules\Mutation\Http\Controllers\Api\KhotianController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::get('/khotian/{type}', KhotianController::class)->name('khotian.type');
});
