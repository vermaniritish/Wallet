<?php

use App\Http\Controllers\PayPalController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\OrdersController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('admin')->middleware(['adminAuth'])->group(function () {	
	include "Admin/dashboard.php";
	include "Admin/searchSugessions.php";
	include "Admin/admins.php";
	include "Admin/users.php";
	include "Admin/shops.php";
	include "Admin/products.php";
	include "Admin/pages.php";
	include "Admin/profile.php";
	include "Admin/blogs.php";
	include "Admin/emailTemplates.php";
	include "Admin/actions.php";
	include "Admin/activities.php";
	include "Admin/settings.php";
	include "Admin/brand.php";
	include "Admin/coupon.php";
	include "Admin/orders.php";
	include "Admin/staff.php";
	include "Admin/colours.php";
	include "Admin/size.php";
	include "Admin/ratings.php";
	include "Admin/sliders.php";
	include "Admin/contactUs.php";
	include "Admin/logoPrices.php";
	include "Admin/offers.php";
});

Route::middleware(['guest'])->group(function () {

	Route::post('/paypal/create-order', [PayPalController::class, 'createOrder']);
	Route::post('/paypal/capture-order', [PayPalController::class, 'captureOrder']);
	Route::get('/paypal/success', [PayPalController::class, 'successMsg']);
	Route::get('/paypal/error', [PayPalController::class, 'errorMsg']);

	//Admin public
	include "Admin/auth.php";
	include "Frontend/auth.php";
	include "Frontend/home.php";
	
	Route::get('/admin/order/{id}/download', [OrdersController::class, 'download'])
    ->name('admin.orders.download');
	
});
// Frontend Routes
// Route::middleware(['frontendauth'])->group(function () {
	
	// Auth Routes

// });
