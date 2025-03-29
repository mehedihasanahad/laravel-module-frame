<?php

use App\Core\Auth\Http\Controllers\SignupController;
use App\Http\Middleware\UserApprovalChecker;
use Illuminate\Support\Facades\Route;
use App\Core\Auth\Http\Controllers\AuthController;

Route::group(['module' => 'Auth', 'namespace' => 'App\Core\Auth\Http\Controllers', 'middleware' => ['web', 'guest']], function () {
//    Route::get('/login', [AuthenticationController::class, 'index'])->name('login.form');
//    Route::post('/login', [AuthenticationController::class, 'login'])->name('login.submit');
//    Route::get('/registration', [AuthenticationController::class, 'registration']);

//    Route::post('/verify-email', [AuthenticationController::class, 'verifyEmail']);
//    Route::post('/verify-otp', [AuthenticationController::class, 'verifyOTP']);
//    Route::get('/forget-password', [AuthenticationController::class, 'forgetPassword']);
//    Route::post('/change-pass', [AuthenticationController::class, 'changePass']);

//    Route::get('/two-step', [AuthenticationController::class, 'twoStep']);

    Route::get('signup/identity-verify', 'SignupController@identityVerify');

    Route::get('signup/identity-verify/nid-tin-verify', 'SignupController@nidTinVerify')->name('nidTinVerify');

    Route::get('signup/getPassportData', 'SignupController@getPassportData');
    Route::post('signup/identity-verify', 'SignupController@identityVerifyConfirm');
    Route::post('signup/identity-verify-previous/{id}', 'SignupController@identityVerifyConfirmWithPreviousData');
    Route::post('signup/getPassportData', [
        'as' => 'getPassportData',
        'uses' => 'SignupController@getPassportData'
    ]);

    Route::get('signup/identity-verify-mobile', 'SignupController@identityVerifyMobile');

});


Route::get('/logout', [AuthController::class, 'logout'])->middleware('web');
Route::group(['module' => 'Auth', 'middleware' => ['web', 'guest']], function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::get('/registration', [AuthController::class, 'registration'])->name('register.form');
    Route::post('/register', [SignupController::class, 'register'])->name('login.register');
    Route::get('/forget-password', [AuthController::class, 'forgetPassword']);
    // Route::post('/verify-otp', [AuthController::class, 'verifyOTP']);
    // Route::post('/verify-email', [AuthController::class, 'verifyEmail']);

});


Route::get('/set-password/{user}', [AuthController::class, 'setPassword'])
    ->name('set-password')
    ->middleware(['signed','web'])
    ->withoutMiddleware([UserApprovalChecker::class]);

Route::post('/set-password/{user}', [AuthController::class, 'updatePassword'])
    ->name('update-password')
    ->middleware(['web'])
    ->withoutMiddleware([UserApprovalChecker::class]);

Route::get('qrcode', function () {
    echo \App\Libraries\CommonFunction::qrCodeGenerate('This is text', 200);
});
Route::get('barcode', function () {
    echo \App\Libraries\CommonFunction::barCodeGenerate('123123123');
});
Route::post('/verify-email', [AuthController::class, 'verifyEmail']);
Route::post('/verify-otp', [AuthController::class, 'verifyOTP']);
Route::post('/change-pass', [AuthController::class, 'changePass']);