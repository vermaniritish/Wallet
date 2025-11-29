<?php

Route::get('/inventory-products/index', '\App\Http\Controllers\API\InventoryProductController@index')
    ->name('api.inventoryProduct.index');

Route::get('/inventory-products/{id}/view', '\App\Http\Controllers\API\InventoryProductController@view')
    ->name('api.inventoryProduct.view');

Route::delete('/inventory-products/{id}/delete', '\App\Http\Controllers\API\InventoryProductController@delete')
    ->name('api.inventoryProduct.delete');