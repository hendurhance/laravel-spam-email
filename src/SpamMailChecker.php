<?php

namespace Martian\SpamMailChecker;

use Martian\SpamMailChecker\Builders\ConfigBuilder;
use Martian\SpamMailChecker\Contracts\DriverInterface;
use Martian\SpamMailChecker\Drivers\AbstractApiDriver;
use Martian\SpamMailChecker\Drivers\LocalDriver;
use Martian\SpamMailChecker\Drivers\QuickEmailVerificationDriver;
use Martian\SpamMailChecker\Drivers\RemoteDriver;
use Martian\SpamMailChecker\Drivers\SendGridDriver;
use Martian\SpamMailChecker\Drivers\VerifaliaDriver;
use Martian\SpamMailChecker\Exceptions\SpamMailCheckerException;

class SpamMailChecker implements DriverInterface
{
    /**
     * @var string
     */
    protected $defaultDriver;

    /**
     * @var array
     */
    protected $drivers = [];

    /**
     * SpamMailChecker constructor.
     */
    public function __construct()
    {
        $this->defaultDriver = (new ConfigBuilder())->getDefaultDriver();
        $this->initializeDrivers();
    }

    /**
     * Initialize the drivers.
     * 
     * @return void
     */
    protected function initializeDrivers()
    {
        $this->drivers = [
            'local' => new LocalDriver(),
            'remote' => new RemoteDriver(),
            'abstractapi' => new AbstractApiDriver(),
            'quickemailverification' => new QuickEmailVerificationDriver(),
            'verifalia' => new VerifaliaDriver(),
            'sendgrid' => new SendGridDriver(),
        ];
    }

    /**
     * Validate the email using the default driver.
     *
     * @param string $email
     * @return bool
     */
    public function validate(string $email): bool
    {
        if (!isset($this->drivers[$this->defaultDriver])) {
            throw new SpamMailCheckerException("Default driver '{$this->defaultDriver}' not found.");
        }
        return $this->drivers[$this->defaultDriver]->validate(filter_var($email, FILTER_SANITIZE_EMAIL));
    }
    /**
     * Set the default driver to use for email validation.
     *
     * @param string $driverName
     * @throws SpamMailCheckerException
     */
    public function setDefaultDriver(string $driverName)
    {
        if (!isset($this->drivers[$driverName])) {
            throw new SpamMailCheckerException("Driver '{$driverName}' not found.");
        }

        $this->defaultDriver = $driverName;
    }

    /**
     * Get the list of available drivers.
     *
     * @return array
     */
    public function getAvailableDrivers(): array
    {
        return array_keys($this->drivers);
    }
}
