<?php
Route::get('/profile', '\App\Http\Controllers\API\UsersController@profile')
    ->name('api.users.profile');

Route::put('/update-profile', '\App\Http\Controllers\API\UsersController@updateProfile')
    ->name('api.users.updateProfile');

Route::put('/update-social', '\App\Http\Controllers\API\UsersController@updateSocial')
    ->name('api.users.updateSocial');

Route::put('/unlink-social', '\App\Http\Controllers\API\UsersController@unlinkSocial')
    ->name('api.users.unlinkSocial');

Route::post('/upload-picture', '\App\Http\Controllers\API\UsersController@uploadPicture')
    ->name('api.users.uploadPicture');

Route::put('/update-email', '\App\Http\Controllers\API\UsersController@updateEmail')
    ->name('api.users.updateEmail');

Route::put('/change-password', '\App\Http\Controllers\API\UsersController@changePassword')
    ->name('api.users.changePassword');

Route::delete('/delete-account', '\App\Http\Controllers\API\UsersController@deleteAccount')
    ->name('api.users.deleteAccount');