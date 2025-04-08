<?php

Route::post('/actions/uploadFile', '\App\Http\Controllers\Admin\ActionsController@uploadFile')
    ->name('actions.uploadFile');

Route::get('/actions/logo-prices', '\App\Http\Controllers\Admin\ActionsController@logoPrices')
    ->name('actions.logoPrices');