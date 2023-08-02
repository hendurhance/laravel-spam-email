<?php

namespace Martian\SpamMailChecker\Contracts;

interface DriverInterface
{
    /**
     * Validate the email.
     * 
     * @return bool
     */
    public function validate(string $email): bool;
}