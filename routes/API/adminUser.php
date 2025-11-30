<?php

Route::get('/users/listing', '\App\Http\Controllers\API\AdminUserController@index')
    ->name('api.users.index');