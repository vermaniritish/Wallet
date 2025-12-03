<?php

Route::get('/ware-houses/listing', '\App\Http\Controllers\API\WareHouseController@index')
    ->name('api.wareHouse.index');

Route::match(['get','post'],'/ware-houses/create', '\App\Http\Controllers\API\WareHouseController@createWareHouse')
    ->name('api.wareHouse.createWareHouse');

Route::match(['get','post'],'/ware-houses/update/{id}', '\App\Http\Controllers\API\WareHouseController@updateWareHouse')
    ->name('api.wareHouse.updateWareHouse');

Route::match(['get','post'],'/ware-houses/view/{id}', '\App\Http\Controllers\API\WareHouseController@viewWareHouse')
    ->name('api.wareHouse.viewWareHouse');

Route::delete('/ware-houses/delete/{id}', '\App\Http\Controllers\API\WareHouseController@deleteWareHouse')
    ->name('api.wareHouse.deleteWareHouse');