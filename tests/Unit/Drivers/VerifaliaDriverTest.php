<?php

namespace Martian\SpamMailChecker\Tests\Unit\Drivers;

use Martian\SpamMailChecker\Drivers\VerifaliaDriver;
use Martian\SpamMailChecker\Exceptions\SpamMailCheckerException;
use Martian\SpamMailChecker\Tests\TestCase;

class VerifaliaDriverTest extends TestCase
{
    /**
     * @var VerifaliaDriver
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
        $username = env('VERIFALIA_USERNAME');
        $password = env('VERIFALIA_PASSWORD');
        
        if (empty($username) || empty($password)) {
            $this->markTestSkipped('Verifalia credentials not configured. Skipping test.');
        }
        
        $this->driver = new VerifaliaDriver();
        $this->assertFalse($this->driver->validate('hello@23456gg.com'));
        $this->assertTrue($this->driver->validate('jane@gmail.com'));
    }
}
