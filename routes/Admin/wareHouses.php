<?php
Route::get('/ware-house', '\App\Http\Controllers\Admin\WareHouseController@index')
    ->name('admin.wareHouses');

Route::get('/ware-house/add', '\App\Http\Controllers\Admin\WareHouseController@add')
    ->name('admin.wareHouses.add');

Route::post('/ware-house/add', '\App\Http\Controllers\Admin\WareHouseController@add')
    ->name('admin.wareHouses.add');

Route::get('/ware-house/{id}/view', '\App\Http\Controllers\Admin\WareHouseController@view')
    ->name('admin.wareHouses.view');

Route::get('/ware-house/{id}/edit', '\App\Http\Controllers\Admin\WareHouseController@edit')
    ->name('admin.wareHouses.edit');

Route::post('/ware-house/{id}/edit', '\App\Http\Controllers\Admin\WareHouseController@edit')
    ->name('admin.wareHouses.edit');

Route::post('/ware-house/bulkActions/{action}', '\App\Http\Controllers\Admin\WareHouseController@bulkActions')
    ->name('admin.wareHouses.bulkActions');

Route::get('/ware-house/{id}/delete', '\App\Http\Controllers\Admin\WareHouseController@delete')
    ->name('admin.wareHouses.delete');