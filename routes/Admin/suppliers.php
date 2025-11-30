<?php
Route::get('/suppliers', '\App\Http\Controllers\Admin\SupplierController@index')
    ->name('admin.suppliers');

Route::get('/suppliers/add', '\App\Http\Controllers\Admin\SupplierController@add')
    ->name('admin.suppliers.add');

Route::post('/suppliers/add', '\App\Http\Controllers\Admin\SupplierController@add')
    ->name('admin.suppliers.add');

Route::get('/suppliers/{id}/view', '\App\Http\Controllers\Admin\SupplierController@view')
    ->name('admin.suppliers.view');

Route::get('/suppliers/{id}/edit', '\App\Http\Controllers\Admin\SupplierController@edit')
    ->name('admin.suppliers.edit');

Route::post('/suppliers/{id}/edit', '\App\Http\Controllers\Admin\SupplierController@edit')
    ->name('admin.suppliers.edit');

Route::post('/suppliers/bulkActions/{action}', '\App\Http\Controllers\Admin\SupplierController@bulkActions')
    ->name('admin.suppliers.bulkActions');

Route::get('/suppliers/{id}/delete', '\App\Http\Controllers\Admin\SupplierController@delete')
    ->name('admin.suppliers.delete');