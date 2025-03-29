<?php

use Illuminate\Support\Facades\Route;
Route:: group(['module'=>'Area','namespace' => 'App\Core\Area\Http\Controllers','middleware'=>['web']],function(){
    //Custome Route
    Route::get('/area/list', 'AreaController@list')->name('area.list');
    Route::get('/area/edit/{id}', 'AreaController@edit')->name('area.edit');
    Route::post('/area/update', 'AreaController@update')->name('area.update');
    Route::get('/districts/{divisionId}', 'AreaController@getDistricts');

    //Resource Route
    Route::resource('area', AreaController::class);
});
