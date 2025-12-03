<?php

Route::get('/purchase-orders/listing', '\App\Http\Controllers\API\PurchaseOrderController@index')
    ->name('api.purchaseOrders.index');

Route::match(['get','post'],'/purchase-orders/create', '\App\Http\Controllers\API\PurchaseOrderController@createPurchaseOrder')
    ->name('api.purchaseOrders.createPurchaseOrder');

Route::match(['get','post'],'/purchase-orders/update/{id}', '\App\Http\Controllers\API\PurchaseOrderController@updatePurchaseOrder')
    ->name('api.purchaseOrders.updatePurchaseOrder');

Route::match(['get','post'],'/purchase-orders/view/{id}', '\App\Http\Controllers\API\PurchaseOrderController@viewPurchaseOrder')
    ->name('api.purchaseOrders.viewPurchaseOrder');

Route::delete('/purchase-orders/delete/{id}', '\App\Http\Controllers\API\PurchaseOrderController@deletePurchaseOrder')
    ->name('api.purchaseOrders.deletePurchaseOrder');