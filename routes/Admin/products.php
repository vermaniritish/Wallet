<?php

use App\Http\Controllers\Admin\Products\ProductsController;

Route::get('/products', '\App\Http\Controllers\Admin\Products\ProductsController@index')
    ->name('admin.products');

Route::get('/products/add', '\App\Http\Controllers\Admin\Products\ProductsController@add')
    ->name('admin.products.add');

Route::get('/products/search', '\App\Http\Controllers\Admin\Products\ProductsController@search')
    ->name('admin.products.search');

Route::get('/products/{id}/fetch', '\App\Http\Controllers\Admin\Products\ProductsController@fetch')
    ->name('admin.products.fetch');

Route::post('/products/add', '\App\Http\Controllers\Admin\Products\ProductsController@add')
    ->name('admin.products.add');

Route::get('/products/{id}/view', '\App\Http\Controllers\Admin\Products\ProductsController@view')
    ->name('admin.products.view');

Route::get('/products/{id}/edit', '\App\Http\Controllers\Admin\Products\ProductsController@edit')
    ->name('admin.products.edit');

Route::post('/products/{id}/edit', '\App\Http\Controllers\Admin\Products\ProductsController@edit')
    ->name('admin.products.edit');

Route::post('/products/bulkActions/{action}', '\App\Http\Controllers\Admin\Products\ProductsController@bulkActions')
    ->name('admin.products.bulkActions');

Route::get('/products/{id}/delete', '\App\Http\Controllers\Admin\Products\ProductsController@delete')
    ->name('admin.products.delete');

Route::get('/products/getSize/{gender}', [ProductsController::class, 'getSize'])->name('admin.products.getSize');
Route::get('/products/getSubCategory/{category}', [ProductsController::class, 'getSubCategory'])->name('admin.products.getSubCategory');


/*** Categories **/
Route::get('/products/categories', '\App\Http\Controllers\Admin\Products\ProductCategoriesController@index')
    ->name('admin.products.categories');

Route::get('/products/categories/add', '\App\Http\Controllers\Admin\Products\ProductCategoriesController@add')
    ->name('admin.products.categories.add');

Route::post('/products/categories/add', '\App\Http\Controllers\Admin\Products\ProductCategoriesController@add')
    ->name('admin.products.categories.add');

Route::get('/products/categories/{id}/edit', '\App\Http\Controllers\Admin\Products\ProductCategoriesController@edit')
    ->name('admin.products.categories.edit');

Route::post('/products/categories/{id}/edit', '\App\Http\Controllers\Admin\Products\ProductCategoriesController@edit')
    ->name('admin.products.categories.edit');

Route::post('/products/categories/bulkActions/{action}', '\App\Http\Controllers\Admin\Products\ProductCategoriesController@bulkActions')
    ->name('admin.products.categories.bulkActions');

Route::get('/products/categories/{id}/delete', '\App\Http\Controllers\Admin\Products\ProductCategoriesController@delete')
    ->name('admin.products.categories.delete');

Route::get('/products/categories/{id}/view', '\App\Http\Controllers\Admin\Products\ProductCategoriesController@view')
    ->name('admin.products.categories.view');

/*** Sub Categories **/
Route::get('/products/subCategories', '\App\Http\Controllers\Admin\Products\ProductSubCategoriesController@index')
    ->name('admin.products.subCategories');

Route::get('/products/subCategories/add', '\App\Http\Controllers\Admin\Products\ProductSubCategoriesController@add')
    ->name('admin.products.subCategories.add');

Route::post('/products/subCategories/add', '\App\Http\Controllers\Admin\Products\ProductSubCategoriesController@add')
    ->name('admin.products.subCategories.add');

Route::get('/products/subCategories/{id}/edit', '\App\Http\Controllers\Admin\Products\ProductSubCategoriesController@edit')
    ->name('admin.products.subCategories.edit');

Route::post('/products/subCategories/{id}/edit', '\App\Http\Controllers\Admin\Products\ProductSubCategoriesController@edit')
    ->name('admin.products.subCategories.edit');

Route::post('/products/subCategories/bulkActions/{action}', '\App\Http\Controllers\Admin\Products\ProductSubCategoriesController@bulkActions')
    ->name('admin.products.subCategories.bulkActions');

Route::get('/products/subCategories/{id}/delete', '\App\Http\Controllers\Admin\Products\ProductSubCategoriesController@delete')
    ->name('admin.products.subCategories.delete');
  
Route::get('/products/subCategories/{id}/view', '\App\Http\Controllers\Admin\Products\ProductSubCategoriesController@view')
    ->name('admin.products.subCategories.view');

/** Report  **/
Route::get('/products/reports', '\App\Http\Controllers\Admin\Products\ReportsController@index')
    ->name('admin.products.reports');
Route::post('/products/reports/bulkActions/{action}', '\App\Http\Controllers\Admin\Products\ReportsController@bulkActions')
    ->name('admin.products.reports.bulkActions');
Route::get('/products/reports/{id}/delete', '\App\Http\Controllers\Admin\Products\ReportsController@delete')
    ->name('admin.products.reports.delete');