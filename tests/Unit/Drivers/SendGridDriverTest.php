<?php

namespace Martian\SpamMailChecker\Tests\Unit\Drivers;

use Martian\SpamMailChecker\Drivers\SendGridDriver;
use Martian\SpamMailChecker\Exceptions\SpamMailCheckerException;
use Martian\SpamMailChecker\Tests\TestCase;

class SendGridDriverTest extends TestCase
{
    /**
     * @var SendGridDriver
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
        $apiKey = env('SENDGRID_API_KEY');

        if (empty($apiKey) || $apiKey === ':SENDGRID_API_KEY') {
            $this->markTestSkipped('SendGrid credentials not configured. Skipping test.');
        }
        
        $this->driver = new SendGridDriver();
        $this->assertFalse($this->driver->validate('hello@23456gg.com'));
        $this->assertTrue($this->driver->validate('jane@gmail.com'));
    }
}
