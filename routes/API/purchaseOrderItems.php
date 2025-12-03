<?php

Route::get('/purchase-orders-item/listing', '\App\Http\Controllers\API\PurchaseOrderItemController@index')
    ->name('api.purchaseOrderItems.index');

Route::match(['get','post'],'/purchase-orders-item/create', '\App\Http\Controllers\API\PurchaseOrderItemController@createPurchaseOrderItem')
    ->name('api.purchaseOrderItems.createPurchaseOrderItem');

Route::match(['get','post'],'/purchase-orders-item/update/{id}', '\App\Http\Controllers\API\PurchaseOrderItemController@updatePurchaseOrderItem')
    ->name('api.purchaseOrderItems.updatePurchaseOrderItem');

Route::match(['get','post'],'/purchase-orders-item/view/{id}', '\App\Http\Controllers\API\PurchaseOrderItemController@viewPurchaseOrderItem')
    ->name('api.purchaseOrderItems.viewPurchaseOrderItem');

Route::delete('/purchase-orders-item/delete/{id}', '\App\Http\Controllers\API\PurchaseOrderItemController@deletePurchaseOrderItem')
    ->name('api.purchaseOrderItems.deletePurchaseOrderItem');