<?php

use App\Http\Controllers\Admin\OffersController;

Route::get('/offers', [OffersController::class, 'index'])
    ->name('admin.offers');

Route::get('/offers/add', [OffersController::class, 'add'])
    ->name('admin.offers.add');

Route::post('/offers/add', [OffersController::class, 'add'])
    ->name('admin.offers.add');

Route::get('/offers/{id}/view', [OffersController::class, 'view'])
    ->name('admin.offers.view');

Route::get('/offers/{id}/edit', [OffersController::class, 'edit'])
    ->name('admin.offers.edit');

Route::post('/offers/{id}/edit', [OffersController::class, 'edit'])
    ->name('admin.offers.edit');

Route::post('/offers/bulkActions/{action}', [OffersController::class, 'bulkActions'])
    ->name('admin.offers.bulkActions');

Route::get('/offers/{id}/delete', [OffersController::class, 'delete'])
    ->name('admin.offers.delete');