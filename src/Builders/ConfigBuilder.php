<?php

namespace Martian\SpamMailChecker\Builders;

use Illuminate\Support\Facades\Config;
use Martian\SpamMailChecker\Exceptions\SpamMailCheckerApiKeyNotSetException;
use Martian\SpamMailChecker\Exceptions\SpamMailCheckerCredentialsNotSetException;
use Martian\SpamMailChecker\Exceptions\SpamMailCheckerException;
use Martian\SpamMailChecker\Exceptions\SpamMailCheckerNotFoundException;

class ConfigBuilder
{
    /**
     * @var string
     */
    protected $defaultDriver;

    /**
     * @var array
     */
    protected $drivers;

    /**
     * @var string
     */
    protected $ruleName;

    /**
     * @var string
     */
    protected $errorMessage;

    /**
     * @var array
     */
    protected $verifaliaCredentials;

    /**
     * ConfigBuilder constructor.
     */
    public function __construct()
    {
        $this->defaultDriver = Config::get('laravel-spammail-checker.default');
        $this->drivers = Config::get('laravel-spammail-checker.drivers');
        $this->ruleName = Config::get('laravel-spammail-checker.rule');
        $this->errorMessage = Config::get('laravel-spammail-checker.error_message');
    }

    /**
     * Get the default driver.
     * 
     * @return string
     */
    public function getDefaultDriver(): string
    {
        return $this->defaultDriver;
    }

    /**
     * Set the default driver.
     * 
     * @param string $defaultDriver
     * @return ConfigBuilder
     */
    public function setDefaultDriver(string $defaultDriver): ConfigBuilder
    {
        $this->defaultDriver = $defaultDriver;
        return $this;
    }

    /**
     * Get the drivers supported by the package.
     * 
     * @return array
     */
    public function getDrivers(): array
    {
        return array_keys($this->drivers);
    }

    /**
     * Get the api key for the selected driver.
     * 
     * @param string $driver
     * @return string
     */
    public function getApiKeyForDriver(string $driver): string
    {
        if (!array_key_exists('api_key', $this->drivers[$driver])) {
            throw new SpamMailCheckerApiKeyNotSetException(
                'The selected driver ' . $driver . ' does not use an API key.'
            );
        }
        return $this->drivers[$driver]['api_key'];
    }

    /**
     * Get the api url for the selected driver.
     * 
     * @param string $driver
     * @return string
     */
    public function getApiUrlForDriver(string $driver): string
    {
        if (!array_key_exists('api_url', $this->drivers[$driver])) {
            throw new SpamMailCheckerApiKeyNotSetException(
                'The selected driver ' . $driver . ' does not use an API url.'
            );
        }
        return $this->drivers[$driver]['api_url'];
    }

    /**
     * Get the credentials for the selected driver.
     * 
     * @param string $driver
     * @return array
     */
    public function getCredentialsForDriver(string $driver): array
    {
        if (!array_key_exists('username', $this->drivers[$driver]) || !array_key_exists('password', $this->drivers[$driver])) {
            throw new SpamMailCheckerCredentialsNotSetException(
                'The selected driver ' . $driver . ' does not use credentials.'
            );
        }
        return [
            'username' => $this->drivers[$driver]['username'],
            'password' => $this->drivers[$driver]['password']
        ];
    }

    /**
     * Get the score for the selected driver.
     * 
     * @param string $driver
     * @return float
     */
    public function getScoreForDriver(string $driver): float
    {
        if (!array_key_exists('score', $this->drivers[$driver])) {
            throw new SpamMailCheckerException(
                'The selected driver ' . $driver . ' does not use a score.'
            );
        }
        return $this->drivers[$driver]['score'];
    }

    /**
     * Get the accepts disposable email flag for the selected driver.
     * 
     * @param string $driver
     * @return bool
     */
    public function getAcceptsDisposableEmailForDriver(string $driver): bool
    {
        if (!array_key_exists('accept_disposable_email', $this->drivers[$driver])) {
            throw new SpamMailCheckerException(
                'The selected driver ' . $driver . ' does not use a score.'
            );
        }
        return $this->drivers[$driver]['accept_disposable_email'];
    }

    /**
     * Get the api version for the selected driver.
     * 
     * @param string $driver
     * @return string
     */
    public function getApiVersionForDriver(string $driver): string
    {
        if (!array_key_exists('version', $this->drivers[$driver])) {
            throw new SpamMailCheckerException(
                'The selected driver ' . $driver . ' does not use an api version.'
            );
        }
        return $this->drivers[$driver]['version'];
    }

    /**
     * Get the source for the selected driver.
     * 
     * @return string
     */
    public function getSourceForDriver(string $driver): string
    {
        if (!array_key_exists('source', $this->drivers[$driver])) {
            throw new SpamMailCheckerException(
                'The selected driver ' . $driver . ' does not use a source.'
            );
        }
        return $this->drivers[$driver]['source'];
    }

    /**
     * Get the base uris for the selected driver.
     * 
     * @param string $driver
     * @return array
     */
    public function getBaseUrisForDriver(string $driver): array
    {
        if (!array_key_exists('base_uris', $this->drivers[$driver])) {
            throw new SpamMailCheckerException(
                'The selected driver ' . $driver . ' does not use base uris.'
            );
        }
        return $this->drivers[$driver]['base_uris'];
    }

    /**
     * Get the API key for the selected driver.
     * 
     * @return string
     * @throws \Martian\SpamMailChecker\Exceptions\SpamMailCheckerApiKeyNotSetException
     */
    public function getApiKey(): string
    {
        if (!array_key_exists('api_key', $this->drivers[$this->defaultDriver])) {
            throw new SpamMailCheckerApiKeyNotSetException('The selected driver does not use an API key.');
        }

        $apiKey = $this->drivers[$this->defaultDriver]['api_key'];

        if (empty($apiKey)) {
            throw new SpamMailCheckerApiKeyNotSetException('The API key for the selected driver' . $this->defaultDriver . ' is not set.');
        }

        return $apiKey;
    }

    /**
     * Get the Credentials (Username & Password) for Verifalia driver.
     * 
     * @return array
     * @throws \Martian\SpamMailChecker\Exceptions\SpamMailCheckerCredentialsNotSetException
     */
    public function getVerifaliaCredentials(): array
    {
        if ($this->defaultDriver === 'verifalia') {
            $username = $this->drivers[$this->defaultDriver]['username'];
            $password = $this->drivers[$this->defaultDriver]['password'];

            if (empty($username) || empty($password)) {
                throw new SpamMailCheckerCredentialsNotSetException('The credentials for the selected driver' . $this->defaultDriver . ' is not set.');
            }

            return [
                'username' => $username,
                'password' => $password
            ];
        }

        throw new SpamMailCheckerCredentialsNotSetException('The selected driver does not use credentials.');
    }

    /**
     * Get the rule name.
     * 
     * @return string
     */
    public function getRuleName(): string
    {
        return $this->ruleName;
    }

    /**
     * Set the rule name.
     * 
     * @param string $ruleName
     * @return ConfigBuilder
     */
    public function setRuleName(string $ruleName): ConfigBuilder
    {
        $this->ruleName = $ruleName;
        return $this;
    }

    /**
     * Get the error message.
     * 
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    /**
     * Set the error message.
     * 
     * @param string $errorMessage
     * @return ConfigBuilder
     */
    public function setErrorMessage(string $errorMessage): ConfigBuilder
    {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    /**
     * Get Local Cache Key.
     * 
     * @return string
     */
    public function getLocalCacheKey(): string
    {
        return $this->drivers['local']['cache_key'];
    }

    /**
     * Get Local Cache TTL.
     * 
     * @return int
     */
    public function getLocalCacheTTL(): int
    {
        return $this->drivers['local']['cache_ttl'];
    }

    /**
     * Get Local Whitelist.
     * 
     * @return array
     */
    public function getLocalWhitelist(): array
    {
        return array_map('strtolower', $this->drivers['local']['whitelist']);
    }

    /**
     * Get Local Blacklist.
     * 
     * @return array
     */
    public function getLocalBlacklist(): array
    {
        return array_map('strtolower', $this->drivers['local']['blacklist']);
    }

    /**
     * Get Local Resource Path
     * 
     * @return string
     */
    public function getLocalResourcePath(): string
    {
        return realpath(__DIR__ . '/../../' . $this->drivers['local']['path']);
    }

    /**
     * Get Local Resource File Content.
     * 
     * @return string
     */
    public function getLocalResourceFileContent(): string
    {
        if (!file_exists($this->getLocalResourcePath())) {
            throw new SpamMailCheckerNotFoundException(
                'The local resource file ' . $this->getLocalResourcePath() . ' was not found.'
            );
        }
        return file_get_contents($this->getLocalResourcePath());
    }

    /**
     * Get Local Resource File Content as Array.
     * 
     * @return array
     */
    public function getLocalResourceFileContentAsArray(): array
    {
        return array_filter(
            array_map('trim', explode("\n", $this->getLocalResourceFileContent()))
        );
    }

    /**
     * Get Local Domain List
     * 
     * @return array
     */
    public function getLocalDomainList(): array
    {
        return collect($this->getLocalResourceFileContentAsArray())
            ->reject(function ($domain) {
                return in_array(strtolower($domain), $this->getLocalWhitelist());
            })
            ->merge($this->getLocalBlacklist())
            ->toArray();
    }

    /**
     * Get Remote Driver Timeout.
     * 
     * @return int
     */
    public function getRemoteDriverTimeout(): int
    {
        return $this->drivers['remote']['timeout'];
    }

    /**
     * Get Remote Driver Check DNS.
     * 
     * @return bool
     */
    public function getRemoteDriverCheckDNS(): bool
    {
        return $this->drivers['remote']['check_dns'];
    }

    /**
     * Get Remote Driver Check SMTP.
     * 
     * @return bool
     */
    public function getRemoteDriverCheckSMTP(): bool
    {
        return $this->drivers['remote']['check_smtp'];
    }

    /**
     * Get Remote Driver Check MX
     * 
     * @return bool
     */
    public function getRemoteDriverCheckMX(): bool
    {
        return $this->drivers['remote']['check_mx'];
    }

}
