<?php

use App\Http\Controllers\Admin\OrderCommentsController;
use App\Http\Controllers\Admin\OrdersController;

Route::get('/order', [OrdersController::class, 'index'])
    ->name('admin.orders');

Route::get('/order/add', [OrdersController::class, 'add'])
    ->name('admin.orders.add');

Route::post('/order/add', [OrdersController::class, 'add'])
    ->name('admin.orders.add');

Route::get('/order/{id}/view', [OrdersController::class, 'view'])
    ->name('admin.orders.view');

Route::get('/order/{id}/edit', [OrdersController::class, 'edit'])
    ->name('admin.orders.edit');

Route::post('/order/{id}/edit', [OrdersController::class, 'edit'])
    ->name('admin.orders.edit');

Route::post('/order/{id}/select-staff', [OrdersController::class, 'selectStaff'])
        ->name('admin.orders.selectStaff');

Route::post('/order/bulkActions/{action}', [OrdersController::class, 'bulkActions'])
    ->name('admin.orders.bulkActions');

Route::get('/order/{id}/delete', [OrdersController::class, 'delete'])
    ->name('admin.orders.delete');

Route::post('/order/{id}/updateField', [OrdersController::class, 'updateField'])->name('admin.order.updateField');
Route::post('/order/switch-status/{field}/{id}', [OrdersController::class, 'switchStatus'])->name('admin.order.switchStatus');
Route::get('/order/getStatus', [OrdersController::class, 'getStatuses'])->name('admin.order.getStatus');

Route::get('/order/getAddress/customer/{id}', [OrdersController::class, 'getAddress'])->name('admin.order.getAddress');

Route::get('/order/{id}/comments', [OrderCommentsController::class, 'index'])->name('admin.orderComments');
Route::post('/order/{id}/comments', [OrderCommentsController::class, 'add'])->name('admin.orderComments.add');
Route::post('/order/{id}/update-comments', [OrderCommentsController::class, 'edit'])->name('admin.orderComments.edit');
Route::post('/order/{id}/delete-comment', [OrderCommentsController::class, 'delete'])->name('admin.orderComments.delete');

Route::post('/orders/{id}/ship', [OrdersController::class, 'ship'])->name('admin.orders.ship');

Route::get('/orders/export', [OrdersController::class, 'exportOrders'])
    ->name('admin.orders.exportOrders');
Route::get('/orders/export/logos', [OrdersController::class, 'exportLogos'])
    ->name('admin.orders.exportLogos');

Route::post('/orders/export-data', [OrdersController::class, 'export'])
    ->name('admin.orders.export');


Route::post('/orders/export-logos', [OrdersController::class, 'exportLogoReport'])
    ->name('admin.report.exportLogo');


Route::get('/orders/bulk-export', [OrdersController::class, 'bulkExport'])
    ->name('admin.ordes.bulkExport');
