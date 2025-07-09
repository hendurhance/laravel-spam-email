<?php

namespace Martian\SpamMailChecker\Tests\Unit\Drivers;

use Martian\SpamMailChecker\Drivers\AbstractApiDriver;
use Martian\SpamMailChecker\Exceptions\SpamMailCheckerException;
use Martian\SpamMailChecker\Tests\TestCase;

class AbstractApiDriverTest extends TestCase
{
    /**
     * @var AbstractApiDriver
     */
    protected $driver;

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @test it can check if an email is spam
     */
    public function it_can_check_if_an_email_is_spam()
    {
        $apiKey = env('ABSTRACTAPI_API_KEY');

        if (empty($apiKey)) {
            $this->markTestSkipped('AbstractAPI credentials not configured. Skipping test.');
        }
        
        $this->driver = new AbstractApiDriver();
        $this->assertFalse($this->driver->validate('hello@23456gg.com'));
        $this->assertTrue($this->driver->validate('jane@gmail.com'));
    }
}
