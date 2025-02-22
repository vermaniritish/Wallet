<?php
Route::get('/', '\App\Http\Controllers\HomeController@index')
    ->name('home');

Route::get('/login', '\App\Http\Controllers\Auth\AuthController@register')->name('login');

Route::get('/about-us', '\App\Http\Controllers\PagesController@aboutUs')
    ->name('aboutUs');

Route::get('/faqs', '\App\Http\Controllers\PagesController@faqs')
    ->name('faqs');

Route::get('/contact-us', '\App\Http\Controllers\PagesController@contactUs')
    ->name('contactUs');

Route::post('/contact-us', '\App\Http\Controllers\PagesController@contactUs')
    ->name('contactUs');

Route::get('/privacy-policy', '\App\Http\Controllers\PagesController@privacyPolicy')
    ->name('privacyPolicy');

Route::get('/terms-conditions', '\App\Http\Controllers\PagesController@termsConditions')
    ->name('termsConditions');

Route::get('/delivery-information', '\App\Http\Controllers\PagesController@deliveryInformation')
    ->name('deliveryInformation');

Route::get('/return-policy', '\App\Http\Controllers\PagesController@returnPolicy')
    ->name('returnPolicy');

Route::get('/cart', '\App\Http\Controllers\PagesController@cart')
    ->name('cart');

Route::get('/my-account', '\App\Http\Controllers\PagesController@myAccount')
    ->name('myAccount')->middleware('userAuth');

Route::get('/edit-account', '\App\Http\Controllers\PagesController@editAccount')
    ->name('editAccount')->middleware('userAuth');

Route::post('/edit-account', '\App\Http\Controllers\PagesController@editAccount')
    ->name('editAccount')->middleware('userAuth');

Route::get('/my-orders', '\App\Http\Controllers\PagesController@myOrders')
    ->name('myOrders')->middleware('userAuth');

Route::get('/my-orders/{id}', '\App\Http\Controllers\PagesController@invoice')
    ->name('invoice')->middleware('userAuth');

Route::get('/checkout', '\App\Http\Controllers\PagesController@checkout')
    ->name('checkout');

Route::post('/newsletter-subscribe', '\App\Http\Controllers\HomeController@newsletter')
    ->name('home.newsletter');

Route::get('/search', '\App\Http\Controllers\HomeController@search')
    ->name('home.search');


Route::get('/sale', '\App\Http\Controllers\HomeController@sale')
    ->name('home.sale');

Route::get('/{category}', '\App\Http\Controllers\HomeController@listing')
    ->name('home.listing');

Route::get('/{category}/{subCategory}', '\App\Http\Controllers\HomeController@listing')
    ->name('home.listing');