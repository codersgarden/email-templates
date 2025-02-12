<?php

use Codersgarden\MultiLangMailer\Controller\Admin\PlaceholderController;
use Codersgarden\MultiLangMailer\Controller\Admin\TemplateController;
use Codersgarden\MultiLangMailer\Middleware\CheckPermission;
use Illuminate\Support\Facades\Route;



Route::middleware(['auth', 'web', CheckPermission::class])->prefix('/email-templates')->group(function () {

    Route::controller(PlaceholderController::class)->prefix('placeholders')->group(function () {
        Route::get('/', 'index')->name('admin.placeholders.index');
        Route::get('/create', 'create')->name('admin.placeholders.create');
        Route::post('/', 'store')->name('admin.placeholders.store');
        Route::get('/{id}', 'edit')->name('admin.placeholders.edit');
        Route::put('/{id}', 'update')->name('admin.placeholders.update');
        Route::delete('/{id}', 'destroy')->name('admin.placeholders.destroy');
    });

    Route::controller(TemplateController::class)->prefix('templates')->group(function () {
        Route::get('/', 'index')->name('admin.templates.index');
        Route::get('/create', 'create')->name('admin.templates.create');
        Route::post('/store', 'store')->name('admin.templates.store');
        Route::get('/edit/{id}', 'edit')->name('admin.templates.edit');
        Route::put('/update/{id}', 'update')->name('admin.templates.update');
        Route::delete('/destroy/{id}', 'destroy')->name('admin.templates.destroy');
    });
});
