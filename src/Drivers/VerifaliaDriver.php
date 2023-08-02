<?php

namespace Martian\SpamMailChecker\Drivers;

use GuzzleHttp\Exception\RequestException;
use Martian\SpamMailChecker\Abstracts\Driver;
use Martian\SpamMailChecker\Contracts\AuthenticateInterface as IAuthenticator;
use Martian\SpamMailChecker\Exceptions\SpamMailCheckerException;

class VerifaliaDriver extends Driver implements IAuthenticator
{
    /**
     * @var string
     */
    protected $driverName = 'verifalia';

    /**
     * @var string
     */
    protected $credential;

    /**
     * @var array
     */
    protected $apiBaseUris;

    /**
     * @var string
     */
    protected $apiUrl;

    /**
     * @var string
     */
    protected $version;

    /**
     * @var string
     */
    protected $bearerToken;

    /**
     * @var bool
     */
    protected $acceptDisposable;

    public function __construct()
    {
        parent::__construct();
        $this->initializeConfig();
        $this->bearerToken = $this->authenticate();
        $this->acceptDisposable = $this->config->getAcceptsDisposableEmailForDriver($this->driverName);
    }

    protected function initializeConfig()
    {
        $this->credential = $this->config->getCredentialsForDriver($this->driverName);
        $this->apiBaseUris = $this->config->getBaseUrisForDriver($this->driverName);
        $this->apiUrl = $this->config->getApiUrlForDriver($this->driverName);
        $this->version = $this->config->getApiVersionForDriver($this->driverName);
    }

    public function authenticate(): string
    {
        try {
            $selectedApiUrl = $this->getRandomApiUrl();
            $response = $this->client->request('POST', $selectedApiUrl . $this->version . '/auth/tokens', [
                'json' => [
                    'username' => $this->credential['username'],
                    'password' => $this->credential['password'],
                ],
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            return $responseData['accessToken'];
        } catch (RequestException $e) {
            throw new SpamMailCheckerException($e->getMessage());
        }
    }

    protected function getRandomApiUrl(): string
    {
        $allApiUrls = array_merge($this->apiBaseUris, [$this->apiUrl]);
        return $allApiUrls[array_rand($allApiUrls)];
    }

    public function validate(string $email): bool
    {
        try {
            $response = $this->getVerificationResponse($email);

            if ($response['status'] !== 'Success' || $response['classification'] !== 'Deliverable') {
                return false;
            }

            if (!$this->acceptDisposable && $response['isDisposableEmailAddress']) {
                return false;
            }

            return true;
        } catch (\Exception $e) {
            throw new SpamMailCheckerException($e->getMessage());
        }
    }

    protected function getVerificationResponse(string $email): array
    {
        $selectedApiUrl = $this->getRandomApiUrl();

        $response = $this->client->request('POST', $selectedApiUrl . $this->version . '/email-validations', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->bearerToken,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'entries' => [
                    ['inputData' => $email],
                ],
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true)['entries']['data'][0];
    }
}
