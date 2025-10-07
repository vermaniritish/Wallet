<?php
Route::get('/school-by-name', '\App\Http\Controllers\SchoolController@schoolByName')
    ->name('school.byName');

Route::get('/schools/{slug}', '\App\Http\Controllers\SchoolController@index')
    ->name('school.index');

Route::get('/school/{slug}/uniforms', '\App\Http\Controllers\SchoolController@uniforms')
    ->name('school.uniforms');