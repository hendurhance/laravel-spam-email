<?php

namespace Martian\SpamMailChecker\Drivers;

use Martian\SpamMailChecker\Abstracts\Driver;
use Martian\SpamMailChecker\Exceptions\SpamMailCheckerException;
use Martian\SpamMailChecker\Exceptions\SpamMailCheckerValidationException;

class RemoteDriver extends Driver
{
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
            $checkDNS = $this->config->getRemoteDriverCheckDNS();
            $checkSMTP = $this->config->getRemoteDriverCheckSMTP();
            $checkMX = $this->config->getRemoteDriverCheckMX();

            if ($checkDNS && $this->isDNSValid($emailDomain)) {
                return false;
            }

            if ($checkSMTP && $this->isSMTPValid($emailDomain)) {
                return false;
            }

            if ($checkMX && $this->isMXValid($emailDomain)) {
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
        return checkdnsrr($domain, 'ANY');
    }

    /**
     * Check if the SMTP server is valid for the email domain.
     *
     * @param string $domain
     * @return bool
     */
    protected function isSMTPValid(string $domain): bool
    {
        $timeout = $this->config->getRemoteDriverTimeout();
        $smtp = fsockopen($domain, 25, $errno, $errstr, $timeout);
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
