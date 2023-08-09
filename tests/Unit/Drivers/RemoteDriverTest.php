<?php

namespace Martian\SpamMailChecker\Tests\Unit\Drivers;

use Martian\SpamMailChecker\Drivers\RemoteDriver;
use Martian\SpamMailChecker\Exceptions\SpamMailCheckerException;
use Martian\SpamMailChecker\Tests\TestCase;

class RemoteDriverTest extends TestCase
{
    /**
     * @var RemoteDriver
     */
    protected $driver;

    public function setUp(): void
    {
        parent::setUp();
        $this->driver = new RemoteDriver();
    }

    /**
     * @test it can get default driver as remote
     */
    public function it_can_check_if_an_email_is_spam()
    {
        $this->expectException(SpamMailCheckerException::class);
        $this->assertFalse($this->driver->validate('hello@23456gg.com'));
        $this->assertTrue($this->driver->validate('jane@gmail.com'));
    }
}
