<?php

namespace Martian\SpamMailChecker;

use Martian\SpamMailChecker\Contracts\DriverInterface;
use Martian\SpamMailChecker\Exceptions\SpamMailCheckerException;
use Martian\SpamMailChecker\Builders\ConfigBuilder;
use Martian\SpamMailChecker\Drivers\AbstractApiDriver;
use Martian\SpamMailChecker\Drivers\LocalDriver;
use Martian\SpamMailChecker\Drivers\QuickEmailVerificationDriver;
use Martian\SpamMailChecker\Drivers\RemoteDriver;
use Martian\SpamMailChecker\Drivers\SendGridDriver;
use Martian\SpamMailChecker\Drivers\VerifaliaDriver;

class SpamMailChecker implements DriverInterface
{
    /**
     * @var DriverInterface
     */
    protected $driver;

    /**
     * SpamMailChecker constructor.
     *
     * @throws SpamMailCheckerException
     */
    public function __construct()
    {
        $configBuilder = new ConfigBuilder();
        $defaultDriver = $configBuilder->getDefaultDriver();
        $this->driver = $this->initializeDriver($defaultDriver);
    }

    /**
     * Initialize the driver based on the default driver configuration.
     *
     * @param string $defaultDriver
     * @return DriverInterface
     * @throws SpamMailCheckerException
     */
    protected function initializeDriver(string $defaultDriver): DriverInterface
    {
        switch ($defaultDriver) {
            case 'abstractapi':
                return new AbstractApiDriver();
            case 'quickemailverification':
                return new QuickEmailVerificationDriver();
            case 'verifalia':
                return new VerifaliaDriver();
            case 'sendgrid':
                return new SendGridDriver();
            case 'local':
                return new LocalDriver();
            case 'remote':
                return new RemoteDriver();
            default:
                throw new SpamMailCheckerException("Driver '{$defaultDriver}' not found.");
        }
    }

    /**
     * Validate the email using the default driver.
     *
     * @param string $email
     * @return bool
     */
    public function validate(string $email): bool
    {
        return $this->driver->validate(filter_var(strtolower($email), FILTER_SANITIZE_EMAIL));
    }
}
