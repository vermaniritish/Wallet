<?php
use App\Http\Controllers\Admin\CouponsController;

Route::get('/coupon', [CouponsController::class, 'index'])
    ->name('admin.coupons');

Route::get('/coupon/add', [CouponsController::class, 'add'])
    ->name('admin.coupons.add');

Route::post('/coupon/add', [CouponsController::class, 'add'])
    ->name('admin.coupons.add');

Route::get('/coupon/{id}/view', [CouponsController::class, 'view'])
    ->name('admin.coupons.view');

Route::get('/coupon/{id}/edit', [CouponsController::class, 'edit'])
    ->name('admin.coupons.edit');

Route::post('/coupon/{id}/edit', [CouponsController::class, 'edit'])
    ->name('admin.coupons.edit');

Route::post('/coupon/bulkActions/{action}', [CouponsController::class, 'bulkActions'])
    ->name('admin.coupons.bulkActions');

Route::get('/coupon/{id}/delete', [CouponsController::class, 'delete'])
    ->name('admin.coupons.delete');

Route::get('/coupon/{id}/download', [CouponsController::class, 'download'])
    ->name('admin.coupon.download');