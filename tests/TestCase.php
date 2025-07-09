<?php

namespace Martian\SpamMailChecker\Tests;

use Dotenv\Dotenv;
use Martian\SpamMailChecker\Builders\ConfigBuilder;
use Martian\SpamMailChecker\Facades\SpamMailCheckerFacade;
use Martian\SpamMailChecker\Providers\SpamMailCheckerServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /**
     * @var ConfigBuilder
     */
    protected $configBuilder;

    /**
     * Load package service provider
     * 
     * @param  \Illuminate\Foundation\Application $app
     * @return \Martian\SpamMailChecker\Providers\SpamMailCheckerServiceProvider
     */
    protected function getPackageProviders($app)
    {
        return [SpamMailCheckerServiceProvider::class];
    }

    /**
     * Load package alias
     * 
     * @param  \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'SpamMailChecker' => SpamMailCheckerFacade::class,
        ];
    }

    public function setUp(): void
    {
        parent::setUp();
        
        // Load environment variables from .env file
        $this->loadEnvironmentVariables();
        
        $this->configBuilder = new ConfigBuilder();
    }
    
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function defineEnvironment($app)
    {
        // Load environment variables before config
        $this->loadEnvironmentVariables();
        
        // Set config values using environment variables
        $app['config']->set('laravel-spammail-checker.drivers.abstractapi.api_key', env('ABSTRACTAPI_API_KEY'));
        $app['config']->set('laravel-spammail-checker.drivers.quickemailverification.api_key', env('QUICKEMAILVERIFICATION_API_KEY'));
        $app['config']->set('laravel-spammail-checker.drivers.verifalia.username', env('VERIFALIA_USERNAME'));
        $app['config']->set('laravel-spammail-checker.drivers.verifalia.password', env('VERIFALIA_PASSWORD'));
        $app['config']->set('laravel-spammail-checker.drivers.sendgrid.api_key', env('SENDGRID_API_KEY'));
    }
    
    /**
     * Load environment variables from .env file for testing
     */
    protected function loadEnvironmentVariables()
    {
        $envFile = dirname(__DIR__) . '/.env';
        
        if (file_exists($envFile)) {
            $dotenv = Dotenv::createImmutable(dirname(__DIR__));
            $dotenv->safeLoad(); // Use safeLoad to avoid overriding existing variables
        }
    }
}
