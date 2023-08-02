<?php

namespace Martian\SpamMailChecker\Abstracts;

use Martian\SpamMailChecker\Builders\ConfigBuilder;
use Martian\SpamMailChecker\Contracts\DriverInterface;

abstract class Driver implements DriverInterface
{
    /**
     * @var ConfigBuilder
     */
    protected $config;

    /**
     * Driver constructor.
     */
    public function __construct()
    {
        $this->config = new ConfigBuilder();
    }

    /**
     * Validate the email.
     * 
     * @return bool
     */
    abstract public function validate(string $email): bool;

    /**
     * Get the email domain.
     * 
     * @param string $email
     * @return string
     */
    protected function getDomain(string $email): string
    {
        return strtolower(substr(strrchr($email, "@"), 1));
    }
}