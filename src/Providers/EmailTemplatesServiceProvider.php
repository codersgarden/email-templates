<?php

namespace Codersgarden\MultiLangMailer\Providers;

use Illuminate\Support\ServiceProvider;
use Codersgarden\MultiLangMailer\Services\EmailTemplateService;
use Codersgarden\MultiLangMailer\Services\PlaceholderService;

class EmailTemplatesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin.php');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'email-templates');

        // Load translations
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'email-templates');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        // Publish assets
        $this->publishes([
            __DIR__ . '/../../database/migrations/' => database_path('migrations'),
        ], 'migrations');

        $this->publishes([
            __DIR__ . '/../Resources/views' => resource_path('views/vendor/email-templates'),
        ], 'views');

        $this->publishes([
            __DIR__ . '/../Resources/lang' => resource_path('lang/vendor/email-templates'),
        ], 'lang');

        $this->publishes([
            __DIR__ . '/../config/email-templates.php' => config_path('email-templates.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Merge package config
        $this->mergeConfigFrom(__DIR__ . '/../config/email-templates.php', 'email-templates');

        // Bind services
        $this->app->singleton(EmailTemplateService::class, function ($app) {
            return new EmailTemplateService($app->make(PlaceholderService::class));
        });

        $this->app->singleton('email-template-service', function ($app) {
            return $app->make(EmailTemplateService::class);
        });

        $this->app->singleton(PlaceholderService::class, function ($app) {
            return new PlaceholderService();
        });
    }
}
