<?php
Route::get('/wishlist/listing', '\App\Http\Controllers\API\WishlistController@listing')
    ->name('api.wishlist.listing');

Route::post('/wishlist/remove', '\App\Http\Controllers\API\WishlistController@remove')
    ->name('api.wishlist.remove');