<?php

namespace Martian\SpamMailChecker\Providers;

use Composer\InstalledVersions;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Martian\SpamMailChecker\SpamMailChecker;
use Martian\SpamMailChecker\Builders\ConfigBuilder;
class SpamMailCheckerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Deprecation Notice
        if (version_compare(InstalledVersions::getVersion('martian/spammailchecker'), '2.0.0', '<'))
        {
            trigger_deprecation('martian/spammailchecker', '1.0.0', 'This package is deprecated and will no longer be maintained. Please use version 2.0.0 or higher.');
        }

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/laravel-spammail-checker.php' => config_path('laravel-spammail-checker.php'),
            ], 'config');
        }

        // Extend Validator
        $this->extendValidator();
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
     * Extend SpamMailChecker Validation Rule
     * 
     * @return void
     */
    protected function extendValidator()
    {
        $configBuilder = new ConfigBuilder;
        Validator::extend($configBuilder->getRuleName(), function ($attribute, $value, $parameters, $validator) {
            return app('spammailchecker')->validate($value);
        }, $configBuilder->getErrorMessage());
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides()
    {
        return ['spammailchecker'];
    }
}