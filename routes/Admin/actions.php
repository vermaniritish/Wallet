<?php
Route::post('/actions/uploadFile', '\App\Http\Controllers\Admin\ActionsController@uploadFile')
    ->name('admin.actions.uploadFile');

Route::post('/actions/removeFile', '\App\Http\Controllers\Admin\ActionsController@removeFile')
    ->name('admin.actions.removeFile');

Route::post('/actions/{relation}/switchUpdate/{field}/{id}', '\App\Http\Controllers\Admin\ActionsController@switchUpdate')
    ->name('admin.actions.switchUpdate');
Route::post('/actions/{relation}/fieldUpdate/{field}/{id}', '\App\Http\Controllers\Admin\ActionsController@fieldUpdate')->name('admin.actions.fieldUpdate');
