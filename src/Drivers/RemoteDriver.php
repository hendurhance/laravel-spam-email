<?php

namespace Martian\SpamMailChecker\Drivers;

use Martian\SpamMailChecker\Abstracts\Driver;
use Martian\SpamMailChecker\Exceptions\SpamMailCheckerException;

class RemoteDriver extends Driver
{
    /**
     * @var bool
     */
    protected $checkDNS;

    /**
     * @var bool
     */
    protected $checkSMTP;

    /**
     * @var bool
     */
    protected $checkMX;

    /**
     * @var int
     */
    protected $timeout;

    /**
     * Remote Driver Constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->checkDNS = $this->config->getRemoteDriverCheckDNS();
        $this->checkSMTP = $this->config->getRemoteDriverCheckSMTP();
        $this->checkMX = $this->config->getRemoteDriverCheckMX();
        $this->timeout = $this->config->getRemoteDriverTimeout();
    }

    /**
     * Validate the email using the remote driver.
     *
     * @return bool
     * @throws SpamMailCheckerValidationException
     */
    public function validate(string $email): bool
    {
        $emailDomain = $this->getDomain($email);

        try {
            // Check DNS
        if ($this->checkDNS && !$this->isDNSValid($emailDomain)) {
            return false;
        }

        // Check SMTP
        if ($this->checkSMTP && !$this->isSMTPValid($emailDomain)) {
            return false;
        }

        // Check MX
        if ($this->checkMX && !$this->isMXValid($emailDomain)) {
            return false;
        }

            return true;
        } catch (\Exception $e) {
            throw new SpamMailCheckerException('Error occurred during email validation.');
        }
    }

    /**
     * Check if the DNS record is valid for the email domain.
     *
     * @param string $domain
     * @return bool
     */
    protected function isDNSValid(string $domain): bool
    {
        return checkdnsrr($domain, 'A');
    }

    /**
     * Check if the SMTP server is valid for the email domain.
     *
     * @param string $domain
     * @return bool
     */
    protected function isSMTPValid(string $domain): bool
    {
        $smtp = fsockopen($domain, 25, $errno, $errstr, $this->timeout);
        if ($smtp) {
            fclose($smtp);
            return true;
        }
        return false;
    }

    /**
     * Check if the MX record is valid for the email domain.
     *
     * @param string $domain
     * @return bool
     */
    protected function isMXValid(string $domain): bool
    {
        return getmxrr($domain, $mxhosts);
    }
}
