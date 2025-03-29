<?php

use App\Core\FormBuilder\Http\Controllers\FormController;
use App\Core\FormBuilder\Http\Controllers\UserInterface\ComponentController;
use App\Core\FormBuilder\Http\Controllers\UserInterface\InputController;
use App\Core\FormBuilder\Http\Controllers\UserInterface\InputGroupController;
use App\Http\Middleware\UserApprovalChecker;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {

    Route::post('client/save/{process_type_id}/{form_type}', [FormController::class, 'store']);

    Route::patch('client/update/{process_type_id}/{app_id}/{form_type}', [FormController::class, 'update']);
});


Route::group(['prefix' => 'form-builder', 'middleware' => ['web']], function () {
    Route::resource('form', \App\Core\FormBuilder\Http\Controllers\UserInterface\FormController::class);
    Route::resource('component', ComponentController::class);
    Route::resource('input', InputController::class);
    Route::resource('input-group', InputGroupController::class);

    Route::get('input-groups/{formId}/{componentId}', [InputGroupController::class, 'inputGroups'])->name('input-groups');
})->withoutMiddleware([UserApprovalChecker::class]);
