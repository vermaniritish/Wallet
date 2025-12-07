<?php
use App\Http\Controllers\Admin\GiftVoucherController;
Route::get('/git-voucher', [GiftVoucherController::class, 'index'])
    ->name('admin.gift_voucher');
Route::get('/git-voucher/{id}/view', [GiftVoucherController::class, 'view'])
    ->name('admin.gift_voucher.view');
Route::get('/git-voucher/{id}/delete', [GiftVoucherController::class, 'delete'])
    ->name('admin.gift_voucher.delete');