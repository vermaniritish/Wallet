<?php

Route::post('/products/make-wish-list', '\App\Http\Controllers\API\ProductsController@makeWishList')
    ->name('api.products.makeWishList');

Route::post('/products/upload-file', '\App\Http\Controllers\API\ProductsController@uploadFile')
    ->name('admin.products.uploadFile');

Route::delete('/products/remove-file', '\App\Http\Controllers\API\ProductsController@removeFile')
    ->name('admin.products.removeFile');

Route::post('/products/create', '\App\Http\Controllers\API\ProductsController@create')
    ->name('admin.products.create');

Route::put('/products/change-status', '\App\Http\Controllers\API\ProductsController@changeStatus')
    ->name('admin.products.changeStatus');

Route::get('/products/my-listing', '\App\Http\Controllers\API\ProductsController@myListing')
    ->name('admin.products.myListing');

Route::put('/products/update-pricing', '\App\Http\Controllers\API\ProductsController@updatePricing')
    ->name('admin.products.updatePricing');

Route::delete('/products/delete', '\App\Http\Controllers\API\ProductsController@delete')
    ->name('api.proucts.delete');