<?php

namespace Martian\SpamMailChecker\Tests;

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
        $this->configBuilder = new ConfigBuilder();
    }
}
