<?php
use App\Http\Controllers\Admin\Products\UniformsController;

Route::get('/uniforms', '\App\Http\Controllers\Admin\Products\UniformsController@index')
    ->name('admin.uniforms');

Route::get('/uniforms/add', '\App\Http\Controllers\Admin\Products\UniformsController@add')
    ->name('admin.uniforms.add');

Route::post('/uniforms/add', '\App\Http\Controllers\Admin\Products\UniformsController@add')
    ->name('admin.uniforms.add');

Route::get('/uniforms/{id}/view', '\App\Http\Controllers\Admin\Products\UniformsController@view')
    ->name('admin.uniforms.view');

Route::get('/uniforms/{id}/edit', '\App\Http\Controllers\Admin\Products\UniformsController@add')
    ->name('admin.uniforms.edit');

Route::post('/uniforms/{id}/edit', '\App\Http\Controllers\Admin\Products\UniformsController@add')
    ->name('admin.uniforms.edit');

Route::post('/uniforms/bulkActions/{action}', '\App\Http\Controllers\Admin\Products\UniformsController@bulkActions')
    ->name('admin.uniforms.bulkActions');

Route::get('/uniforms/{id}/delete', '\App\Http\Controllers\Admin\Products\UniformsController@delete')
    ->name('admin.uniforms.delete');