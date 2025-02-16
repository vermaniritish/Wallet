<?php

Route::post('/auth/signup', '\App\Http\Controllers\API\AuthController@signup')
    ->name('api.signup');

Route::post('/auth/login', '\App\Http\Controllers\API\AuthController@login')
    ->name('api.login');

Route::post('/auth/logout', '\App\Http\Controllers\API\AuthController@logout')
    ->name('api.logout');

Route::post('/auth/social-login', '\App\Http\Controllers\API\AuthController@socialLogin')
    ->name('api.socialLogin');

Route::post('/auth/auth-state', '\App\Http\Controllers\API\AuthController@authState')
    ->name('api.authState');

Route::post('/auth/forgot-password', '\App\Http\Controllers\API\AuthController@forgotPassword')
    ->name('api.forgotPassword');

Route::post('/auth/email-verification/{hash}', '\App\Http\Controllers\API\AuthController@emailVerification')
    ->name('api.emailVerification');

Route::post('/auth/email-verification/{hash}/{email}', '\App\Http\Controllers\API\AuthController@emailVerification')
    ->name('api.emailVerification');

Route::get('/auth/recover-password/{hash}', '\App\Http\Controllers\API\AuthController@recoverPassword')
    ->name('api.recoverPassword');

Route::post('/auth/recover-password/{hash}', '\App\Http\Controllers\API\AuthController@recoverPassword')
    ->name('api.recoverPassword');

Route::post('/auth/second-auth/{token}', '\App\Http\Controllers\API\AuthController@secondAuth')
    ->name('api.secondAuth');

Route::get('/auth/check-email-exist', '\App\Http\Controllers\API\AuthController@checkEmailExist')
    ->name('api.checkEmailExist');