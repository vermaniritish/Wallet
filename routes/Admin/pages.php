<?php
Route::get('/pages', '\App\Http\Controllers\Admin\PagesController@index')
    ->name('admin.pages');

Route::get('/pages/add', '\App\Http\Controllers\Admin\PagesController@add')
    ->name('admin.pages.add');

Route::post('/pages/add', '\App\Http\Controllers\Admin\PagesController@add')
    ->name('admin.pages.add');

Route::get('/pages/home', '\App\Http\Controllers\Admin\PagesController@home')
    ->name('admin.pages.home');

Route::post('/pages/home', '\App\Http\Controllers\Admin\PagesController@home')
    ->name('admin.pages.home');

Route::get('/pages/{id}/view', '\App\Http\Controllers\Admin\PagesController@view')
    ->name('admin.pages.view');

Route::get('/pages/menu', '\App\Http\Controllers\Admin\PagesController@menu')
    ->name('admin.pages.menu');

Route::get('/pages/{id}/edit', '\App\Http\Controllers\Admin\PagesController@edit')
    ->name('admin.pages.edit');

Route::post('/pages/{id}/edit', '\App\Http\Controllers\Admin\PagesController@edit')
    ->name('admin.pages.edit');

Route::post('/pages/bulkActions/{action}', '\App\Http\Controllers\Admin\PagesController@bulkActions')
    ->name('admin.pages.bulkActions');

Route::get('/pages/{id}/delete', '\App\Http\Controllers\Admin\PagesController@delete')
    ->name('admin.pages.delete');

Route::get('/newsletter', '\App\Http\Controllers\Admin\NewsletterController@index')
    ->name('admin.newsletter');

Route::get('/newsletter/export', '\App\Http\Controllers\Admin\NewsletterController@export')
    ->name('admin.newsletter.export');

Route::post('/newsletter/bulkActions/{action}', '\App\Http\Controllers\Admin\NewsletterController@bulkActions')
    ->name('admin.newsletter.bulkActions');

Route::get('/newsletter/{id}/delete', '\App\Http\Controllers\Admin\PagesController@delete')
    ->name('admin.newsletter.delete');