<?php

namespace Codersgarden\MultiLangMailer\Providers;

use Illuminate\Support\ServiceProvider;
use Codersgarden\MultiLangMailer\Services\EmailTemplateService;
use Codersgarden\MultiLangMailer\Services\PlaceholderService;
use Illuminate\Support\Facades\Route;

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


        $this->serveCssFile();
        $this->serveFontFile();

        $this->serveStaticFile('logo', '/../Resources/images/logo.svg');
        $this->serveStaticFile('back-icon', '/../Resources/images/back-icon.svg');
        $this->serveStaticFile('logout-icon', '/../Resources/images/logout-icon.svg');
        $this->serveStaticFile('close-icon', '/../Resources/images/close-icon.svg');
        $this->serveStaticFile('pervious-icon', '/../Resources/images/Vector.png');
        // $this->serveStaticFile('fonts', '/../Resources/fonts/Jost-VariableFont_wght.ttf');

        // Load translations
        if (is_dir(__DIR__ . '/../Lang')) {
            $this->loadTranslationsFrom(__DIR__ . '/../Lang', 'email-templates');
        }

        // Load migrations
        if (is_dir(__DIR__ . '/../database/migrations')) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }

        // Publish migrations
        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations'),
        ], 'email-templates-migrations');

        $this->publishes([
            __DIR__ . '/../Config/email-templates.php' => config_path('email-templates.php'),
        ], 'email-templates-config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Merge package config
        $this->mergeConfigFrom(__DIR__ . '/../Config/email-templates.php', 'email-templates');

        // Bind services
        $this->app->singleton(EmailTemplateService::class, function ($app) {
            return new EmailTemplateService($app->make(PlaceholderService::class));
        });

        $this->app->singleton(EmailTemplateService::class, function ($app) {
            return $app->make(EmailTemplateService::class);
        });

        $this->app->singleton(PlaceholderService::class, function () {
            return new PlaceholderService();
        });
    }

    protected function serveCssFile()
    {
        Route::middleware('web')->get('email-templates/css/style.css', function () {
            $path = __DIR__ . '/../Resources/css/style.css';

            if (file_exists($path)) {
                return response()->file($path, [
                    'Content-Type' => 'text/css',
                ]);
            }

            abort(404, 'CSS file not found.');
        });
    }
    protected function serveFontFile()
    {
        Route::middleware('web')->get('email-templates/fonts/Jost-VariableFont_wght.ttf', function () {
            $path = __DIR__ . '/../Resources/fonts/Jost-VariableFont_wght.ttf';

            if (file_exists($path)) {
                return response()->file($path, [
                    'Content-Type' => 'text/ttf',
                ]);
            }

            abort(404, 'Font file not found.');
        });
    }
    protected function serveStaticFile($routeName, $filePath)
    {
        Route::get($routeName, function () use ($filePath) {
            $path = __DIR__ . $filePath;
            return response()->file($path);
        });
    }
}
