<?php

namespace Martian\SpamMailChecker\Contracts;

interface AuthenticateInterface
{
    /**
     * Authenticate the driver using the credentials.
     *
     * @return string
     */
    public function authenticate(): string;
}