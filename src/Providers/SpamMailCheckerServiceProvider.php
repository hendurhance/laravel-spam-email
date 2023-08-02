<?php

namespace Martian\SpamMailChecker\Providers;

use Illuminate\Support\ServiceProvider;
use Martian\SpamMailChecker\SpamMailChecker;

class SpamMailCheckerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/laravel-spammail-checker.php' => config_path('laravel-spammail-checker.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../../config/laravel-spammail-checker.php', 'laravel-spammail-checker');

        // Register the main class to use with the facade
        $this->app->singleton('spammailchecker', function () {
            return new SpamMailChecker;
        });
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides()
    {
        return ['spammailchecker'];
    }
}