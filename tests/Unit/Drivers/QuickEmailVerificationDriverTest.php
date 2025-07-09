<?php

namespace Martian\SpamMailChecker\Tests\Unit\Drivers;

use Martian\SpamMailChecker\Drivers\QuickEmailVerificationDriver;
use Martian\SpamMailChecker\Exceptions\SpamMailCheckerException;
use Martian\SpamMailChecker\Tests\TestCase;

class QuickEmailVerificationDriverTest extends TestCase
{
    /**
     * @var QuickEmailVerificationDriver
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
        $apiKey = env('QUICKEMAILVERIFICATION_API_KEY');

        if (empty($apiKey)) {
            $this->markTestSkipped('QuickEmailVerification credentials not configured. Skipping test.');
        }
        
        $this->driver = new QuickEmailVerificationDriver();
        $this->assertFalse($this->driver->validate('invalid-domain@23456gg.com'));
        $this->assertTrue($this->driver->validate('jane@gmail.com'));
    }
}
