<?php

namespace Martian\SpamMailChecker\Tests\Unit;

use Illuminate\Support\Facades\Config;
use Martian\SpamMailChecker\Exceptions\SpamMailCheckerException;
use Martian\SpamMailChecker\SpamMailChecker;
use Martian\SpamMailChecker\Tests\TestCase;

class SpamMailCheckerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @test it can get default driver
     */
    public function it_can_get_default_driver_as_remote()
    {
        $this->assertEquals('local', $this->configBuilder->getDefaultDriver());
    }

    /**
     * @test it can get default driver as remote
     */
    public function it_can_get_default_driver_as_remote_when_it_is_set()
    {
        $this->configBuilder->setDefaultDriver('remote');
        $this->assertEquals('remote', $this->configBuilder->getDefaultDriver());
    }

    /**
     * @test it can get absractapi driver has api key
     */
    public function it_can_get_absractapi_driver_has_api_key()
    {
        $this->configBuilder->setDefaultDriver('abstractapi');
        $this->assertEquals('abstractapi', $this->configBuilder->getDefaultDriver());
        $this->assertEquals(Config::get('laravel-spammail-checker.drivers.abstractapi.api_key'), $this->configBuilder->getApiKey());
    }


    /**
     * @test it can get sendgrid driver has api key
     */
    public function it_can_get_sendgrid_driver_has_api_key_when_it_is_set()
    {
        $this->configBuilder->setDefaultDriver('sendgrid');
        $this->assertEquals('sendgrid', $this->configBuilder->getDefaultDriver());
        $this->assertEquals(Config::get('laravel-spammail-checker.drivers.sendgrid.api_key'), $this->configBuilder->getApiKey());
    }

    /**
     * @test it can get validate email using verifalia as default driver
     */
    public function it_can_get_validate_email_using_verifalia_as_default_driver()
    {
        Config::set('laravel-spammail-checker.default', 'verifalia');
        $spammailchecker = new SpamMailChecker();
        $this->expectException(SpamMailCheckerException::class);
        $this->assertFalse($spammailchecker->validate('hello@random00address.com'));
        $this->assertTrue($spammailchecker->validate('jane@gmail.com'));
    }
}