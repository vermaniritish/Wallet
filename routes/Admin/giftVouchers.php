<?php
use App\Http\Controllers\Admin\GiftVoucherController;
Route::get('/gift-voucher', [GiftVoucherController::class, 'index'])
    ->name('admin.gift_voucher');
Route::get('/gift-voucher/{id}/view', [GiftVoucherController::class, 'view'])
    ->name('admin.gift_voucher.view');
Route::get('/gift-voucher/{id}/delete', [GiftVoucherController::class, 'delete'])
    ->name('admin.gift_voucher.delete');