<?php

use App\Http\Controllers\Admin\SchoolsController;

Route::get('/schools', [SchoolsController::class, 'index'])
    ->name('admin.schools');

Route::get('/schools/add', [SchoolsController::class, 'add'])
    ->name('admin.schools.add');

Route::post('/schools/add', [SchoolsController::class, 'add'])
    ->name('admin.schools.add');

Route::get('/schools/{id}/view', [SchoolsController::class, 'view'])
    ->name('admin.schools.view');

Route::get('/schools/{id}/edit', [SchoolsController::class, 'edit'])
    ->name('admin.schools.edit');

Route::post('/schools/{id}/edit', [SchoolsController::class, 'edit'])
    ->name('admin.schools.edit');

Route::post('/schools/bulkActions/{action}', [SchoolsController::class, 'bulkActions'])
    ->name('admin.schools.bulkActions');

Route::get('/schools/{id}/delete', [SchoolsController::class, 'delete'])
    ->name('admin.schools.delete');