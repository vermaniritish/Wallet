<?php
Route::get('/messages/friends', '\App\Http\Controllers\API\MessagesController@friends')
    ->name('api.messages.friends');

Route::get('/messages/listing/{toId}/{productId}', '\App\Http\Controllers\API\MessagesController@listing')
    ->name('api.messages.listing');

Route::post('/messages/send', '\App\Http\Controllers\API\MessagesController@send')
    ->name('api.messages.send');

Route::post('/messages/mark-read/{id}', '\App\Http\Controllers\API\MessagesController@markRead')
    ->name('api.messages.markRead');

Route::post('/messages/delete', '\App\Http\Controllers\API\MessagesController@deleteMessage')
    ->name('api.messages.deleteMessage');

Route::get('/messages/counts', '\App\Http\Controllers\API\MessagesController@getCounts')
    ->name('api.messages.getCounts');

Route::delete('/messages/delete-chat', '\App\Http\Controllers\API\MessagesController@deleteChat')
    ->name('api.messages.deleteChat');

Route::post('/messages/mute-chat', '\App\Http\Controllers\API\MessagesController@muteChat')
    ->name('api.messages.muteChat');

Route::post('/messages/upload-file', '\App\Http\Controllers\API\MessagesController@uploadFile')
    ->name('api.messages.uploadFile');

Route::post('/messages/remove-file', '\App\Http\Controllers\API\MessagesController@removeFile')
    ->name('api.messages.removeFile');