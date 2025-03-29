<?php

use App\Core\ProcessEngine\Http\Controllers\Api\v1\GlobalApiController;
use App\Core\ProcessEngine\Http\Controllers\ProcessPathController;
use App\Core\ProcessEngine\Http\Controllers\ProcessStatusController;
use App\Core\ProcessEngine\Http\Controllers\ProcessTypeController;
use App\Core\ProcessEngine\Http\Controllers\ProcessUserDeskController;
use Illuminate\Support\Facades\Route;
use App\Core\ProcessEngine\Http\Controllers\ApplicationController;
use App\Core\ProcessEngine\Http\Controllers\ProcessEngineController;

Route::middleware(['web', 'auth'])->group(function () {

    Route::group(['prefix' => 'application'], function () {
        Route::get('/create/{process_type_id}/{form_type?}', [ApplicationController::class, 'create'])->name('application.create');
        Route::get('/list/{process_type_id}', [ApplicationController::class, 'list'])->name('application.list');
        Route::get('/view/{process_type_id}/{app_id}/{form_type?}', [ApplicationController::class, 'view'])->name('application.view');
        Route::get('/edit/{process_type_id}/{app_id}/{form_type?}', [ApplicationController::class, 'edit'])->name('application.edit');
        Route::post('/list/data', [ApplicationController::class, 'listData'])->name('application.list.data');
    });

    Route::group(['prefix' => 'process'], function () {
        Route::post('/process-update', [ProcessEngineController::class, 'processUpdate'])->name('process.update');
        Route::get('/get-status-list-by-process-list-id/{processListId}', [ProcessEngineController::class, 'getStatusListByProcessListId'])->name('process.status.list');
        Route::post('/get-desk-by-status', [ProcessEngineController::class, 'getDeskListByProcessStatusId'])->name('process.desk.list');
        Route::post('/get-user-by-desk', [ProcessEngineController::class, 'getUserListByUserDeskId'])->name('process.user.list');
        Route::get('/get-status-list-by-process-type-id', [ProcessEngineController::class, 'getStatusListByProcessTypeId'])->name('get-status-list-by-process-type-id');
    });

    Route::group([], function () {
        Route::get('/process-type/list', [ProcessTypeController::class, 'list'])->name('process-type.list');
        Route::get('/process-user-desk/list', [ProcessUserDeskController::class, 'list'])->name('process-user-desk.list');
        Route::get('/process-statuses/list', [ProcessStatusController::class, 'list'])->name('process-statuses.list');
        Route::get('/process-path/list', [ProcessPathController::class, 'list'])->name('process-path.list');
        Route::get('/process-status/{process_type}', [GlobalApiController::class,'processStatus'])->name('process-status.process-type');

        Route::resource('/process-type', ProcessTypeController::class);
        Route::resource('/process-user-desk', ProcessUserDeskController::class);
        Route::resource('/process-statuses', ProcessStatusController::class);
        Route::resource('/process-path', ProcessPathController::class);
    });
});


