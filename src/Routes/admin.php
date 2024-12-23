<?php

use Codersgarden\MultiLangMailer\Controller\Admin\PlaceholderController;
use Codersgarden\MultiLangMailer\Controller\Admin\TemplateController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/email-templates', 'middleware' => ['web', 'auth']], function () {
    // Template Routes
    Route::resource('templates', TemplateController::class)->names('email-templates.admin.templates');

    // Placeholder Routes
    Route::resource('placeholders', PlaceholderController::class)->names('email-templates.admin.placeholders');
});
