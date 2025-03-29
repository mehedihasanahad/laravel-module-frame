<?php

use App\Core\User\Http\Controllers\PermissionController;
use App\Core\User\Http\Controllers\ProfileController;
use App\Core\User\Http\Controllers\RoleController;
use App\Core\User\Http\Controllers\UpdatePasswordController;
use App\Core\User\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

//Route::group(['module'=>'User','namespace' => 'App\Core\User\Controllers','middleware'=>['web']],function(){
//    Route::get('/users',[UserController::class,'index']);
//    Route::get('/user/add',[UserController::class,'addUser']);
//    Route::post('/user/add',[UserController::class,'add']);
//    Route::post('/user/update',[UserController::class,'profileUpdate']);
//    Route::get('/users/two-step', [UserController::class, 'twoStep']);
//    Route::get('/user/profileinfo', [UserController::class, 'profileinfo']);
//    Route::get('/user/view/{userId}', [UserController::class, 'getUserDetails']);
//    Route::get('/user/edit/{edituserId}', [UserController::class, 'editUserDetails']);
//    Route::post('/user/updateUserDetails', [UserController::class, 'updateUserDetails']);
//    Route::get('/user/delete/{userId}', [UserController::class, 'deleteUserDetails'])->name('users.destroy');
//
//
//    Route::post('/update-pass', [UserController::class, 'updatePass']);
//});

Route::group(['module' => 'User', 'middleware' => ['web', 'auth']], function () {
    // List routes
    Route::get('/permissions/list', [PermissionController::class, 'list'])->name('permissions.list');
    Route::get('/roles/list', [RoleController::class, 'list'])->name('roles.list');
    Route::get('/users/list', [UserController::class, 'list'])->name('users.list');

    // Resource routes
    Route::resource('permissions', PermissionController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('profile', ProfileController::class)->only('index', 'update');

    Route::patch('/user/change-password', UpdatePasswordController::class)->name('user.change-password');

});
