<?php

use Illuminate\Support\Facades\Route;
use App\Core\Document\Http\Controllers\DocumentControllers;
use App\Core\Document\Http\Controllers\RoleController;
use League\CommonMark\Node\Block\Document;

Route:: group(['module'=>'Document','namespace' => 'App\Core\Document\Controllers','middleware'=>['web']],function(){
    Route::get('/document',[DocumentControllers::class,'index'])->name('document');
    Route::post('/document/update', [DocumentControllers::class, 'updateDocumentDetails']);
    Route::post('/document/add', [DocumentControllers::class, 'add']);
    Route::post('/process-document/add', [DocumentControllers::class, 'addProcess']);
    Route::get('/process-document/view', [DocumentControllers::class, 'processView']);
    Route::get('/fetch-document-data', [DocumentControllers::class, 'fetchDocumentData']);
    Route::post('/process-document/update', [DocumentControllers::class, 'updateProcessDocumentDetails']);

    //Server site data rendering route
    Route::get('/document-data', [RoleController::class, 'documentlist']);
    Route::get('/process-data', [RoleController::class, 'processlist']);
    //Edit Document and process document route
    Route::get('/edit-document/{id}', [DocumentControllers::class, 'editDocument'])->name('edit-document');
    Route::get('/edit-process/{id}', [DocumentControllers::class, 'editprocess'])->name('edit-process');
});
