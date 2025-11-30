<?php

Route::get('/supplier/listing', '\App\Http\Controllers\API\SupplierController@index')
    ->name('api.supplier.index');

Route::get('/supplier/search', '\App\Http\Controllers\API\SupplierController@getSupplierSearch')
    ->name('admin.supplier.getSupplierSearch');