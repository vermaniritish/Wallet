<?php
Route::get('/menu', '\App\Http\Controllers\Admin\MenuController@index')
    ->name('admin.menu');

Route::get('/menu/add', '\App\Http\Controllers\Admin\MenuController@add')
    ->name('admin.menu.add');

Route::post('/header/menu/add', '\App\Http\Controllers\Admin\MenuController@add')
    ->name('admin.hedaerMenu.add');

Route::get('/menu/getMenuItems', '\App\Http\Controllers\Admin\MenuController@getMenuItems')
    ->name('admin.menu.getMenuItems');

Route::post('/menu/{id}/view', '\App\Http\Controllers\Admin\MenuController@view')
    ->name('admin.menu.view');

Route::post('/footer-menu/add', '\App\Http\Controllers\Admin\MenuController@addFooterMenu')
    ->name('admin.footerMenu.add');

Route::delete('menu/delete/{id}', '\App\Http\Controllers\Admin\MenuController@deleteMenuItem')->name('menu.delete');



// hindi

Route::get('/menu-hindi/add', '\App\Http\Controllers\Admin\MenuHindiController@add')
    ->name('admin.menuHindi.add');

Route::post('/hi/header/menu/add', '\App\Http\Controllers\Admin\MenuHindiController@add')
    ->name('admin.hedaerMenu.add.hi');

Route::get('/hi/menu/getMenuItems', '\App\Http\Controllers\Admin\MenuHindiController@getMenuItems')
    ->name('admin.menu.getMenuItems.hi');

Route::post('/hi/menu/{id}/view', '\App\Http\Controllers\Admin\MenuHindiController@view')
    ->name('admin.menu.view.hi');

Route::post('/hi/footer-menu/add', '\App\Http\Controllers\Admin\MenuHindiController@addFooterMenu')
    ->name('admin.footerMenu.add.hi');

Route::delete('/hi/menu/delete/{id}', '\App\Http\Controllers\Admin\MenuHindiController@deleteMenuItem')->name('menu.delete.hi');



