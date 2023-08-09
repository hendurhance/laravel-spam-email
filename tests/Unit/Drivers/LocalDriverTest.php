<?php

namespace Martian\SpamMailChecker\Tests\Unit\Drivers;

use Illuminate\Support\Facades\Config;
use Martian\SpamMailChecker\Drivers\LocalDriver;
use Martian\SpamMailChecker\Tests\TestCase;

class LocalDriverTest extends TestCase
{
    /**
     * @var LocalDriver
     */
    protected $driver;

    public function setUp(): void
    {
        parent::setUp();
        Config::set('laravel-spammail-checker.drivers.local.whitelist', ['12345gmail.com']);
        Config::set('laravel-spammail-checker.drivers.local.blacklist', ['yahoo.com']);
        $this->driver = new LocalDriver();
    }

    /**
     * @test it can check if an email is spam
     */
    public function it_can_check_if_an_email_is_spam()
    {
        $this->assertFalse($this->driver->validate('hello@example.com'));
        $this->assertTrue($this->driver->validate('hello@gmail.com'));
    }

    /**
     * @test it cannot validate an email in whitelist
     */
    public function it_cannot_validate_an_email_in_whitelist()
    {
        $this->assertTrue($this->driver->validate('hello@12345gmail.com'));
    }

    /**
     * @test it can validate an email in blacklist
     */
    public function it_can_validate_an_email_in_blacklist()
    {
        $this->assertFalse($this->driver->validate('hello@yahoo.com'));
    }
}
