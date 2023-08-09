<?php

namespace Martian\SpamMailChecker\Tests\Unit\Builder;

use Martian\SpamMailChecker\Tests\TestCase;

class ConfigBuilderTest extends TestCase
{
    /**
     * @test it can get default driver
     */
    public function it_can_get_default_driver()
    {
        $this->assertEquals('local', $this->configBuilder->getDefaultDriver());
    }

    /**
     * @test it can set default driver
     */
    public function it_can_set_default_driver()
    {
        $this->configBuilder->setDefaultDriver('test');
        $this->assertEquals('test', $this->configBuilder->getDefaultDriver());
    }

    /**
     * @test it can get all available drivers
     */
    public function it_can_get_all_available_drivers()
    {
        $this->assertEquals(
            [
                "local",
                "remote",
                "abstractapi",
                "quickemailverification",
                "verifalia",
                "sendgrid",
            ],
            $this->configBuilder->getDrivers()
        );
    }

    /**
     * @test it can get api key for a driver (abstractapi)
     */
    public function it_can_get_api_key_for_a_driver()
    {
        $this->assertEquals(
            env('ABSTRACTAPI_API_KEY'),
            $this->configBuilder->getApiKeyForDriver('abstractapi')
        );
    }

    /**
     * @test it can get api url for a driver (abstractapi)
     */
    public function it_can_get_api_url_for_a_driver()
    {
        $this->assertEquals(
            'https://emailvalidation.abstractapi.com/v1',
            $this->configBuilder->getApiUrlForDriver('abstractapi')
        );
    }

    /**
     * @test it can get credentials for a driver (verifalia)
     */
    public function it_can_get_credentials_for_a_driver()
    {
        $this->assertEquals(
            [
                'username' => env('VERIFALIA_USERNAME'),
                'password' => env('VERIFALIA_PASSWORD'),
            ],
            $this->configBuilder->getCredentialsForDriver('verifalia')
        );
    }

    /**
     * @test it can get score for a driver (sendgrid)
     */
    public function it_can_get_score_for_a_driver()
    {
        $this->assertEquals(
            0.5,
            $this->configBuilder->getScoreForDriver('sendgrid')
        );
    }

    /**
     * @test it can get accept disposable email for a driver (sendgrid)
     */
    public function it_can_get_accept_disposable_email_for_a_driver()
    {
        $this->assertTrue(
            $this->configBuilder->getAcceptsDisposableEmailForDriver('sendgrid')
        );
    }

    /**
     * @test it can get api version for a driver (verifalia)
     */
    public function it_can_get_api_version_for_a_driver()
    {
        $this->assertEquals(
            'v2.4',
            $this->configBuilder->getApiVersionForDriver('verifalia')
        );
    }

    /**
     * @test it can get source for a driver (sendgrid)
     */
    public function it_can_get_source_for_a_driver()
    {
        $this->assertEquals(
            'signup',
            $this->configBuilder->getSourceForDriver('sendgrid')
        );
    }

    /**
     * @test it can get base uris for a driver (verifalia)
     */
    public function it_can_get_base_uris_for_a_driver()
    {
        $this->assertEquals(
            [
                'https://api-1.verifalia.com/',
                'https://api-2.verifalia.com/',
                'https://api-3.verifalia.com/'
            ],
            $this->configBuilder->getBaseUrisForDriver('verifalia')
        );
    }

    /**
     * @test it can get timeout for a driver (remote)
     */
    public function it_can_get_timeout_for_a_driver()
    {
        $this->assertEquals(
            5,
            $this->configBuilder->getRemoteDriverTimeout()
        );
    }
}