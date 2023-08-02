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
    protected $diverName = 'verifalia';

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
    protected $allApiUrls;

    /**
     * @var string
     */
    protected $selectedApiUrl;

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

    /**
     * Verifalia Driver Constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->credential = $this->config->getCredentialsForDriver($this->diverName);
        $this->apiBaseUris = $this->config->getBaseUrisForDriver($this->diverName);
        $this->apiUrl = $this->config->getApiUrlForDriver($this->diverName);
        $this->allApiUrls = array_merge($this->apiBaseUris, [$this->apiUrl]);
        $this->selectedApiUrl = $this->allApiUrls[array_rand($this->allApiUrls)];
        $this->version = $this->config->getApiVersionForDriver($this->diverName);
        $this->bearerToken = $this->authenticate();
        $this->acceptDisposable = $this->config->getAcceptsDisposableEmailForDriver($this->diverName);
    }

    /**
     * Authenticate the driver using the credentials.
     *
     * @return string
     */
    public function authenticate(): string
    {
        try {
            $response = $this->client->request('POST', $this->selectedApiUrl . $this->version . '/auth/tokens', [
                'json' => [
                    'username' => $this->credential['username'],
                    'password' => $this->credential['password'],
                ],
            ]);

            $response = json_decode($response->getBody()->getContents(), true);

            return $response['accessToken'];
        } catch (RequestException $e) {
            throw new SpamMailCheckerException($e->getMessage());
        }
    }

    /**
     * Validate the email using the verifalia driver.
     *
     * @return bool
     * @throws SpamMailCheckerValidationException
     */
    public function validate(string $email): bool
    {
        try {
            $response = $this->getVerificationResponse($email);

            if ($response['status'] !== 'Success') {
                return false;
            }

            if ($response['classification'] !== 'Deliverable') {
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

    /**
     * Get the verification response from the verifalia api.
     *
     * @param string $email
     * @return array
     */
    public function getVerificationResponse(string $email): array
    {
        $response = $this->client->request('POST', $this->selectedApiUrl . $this->version . '/email-validations', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->bearerToken,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'entries' => [
                    [
                        'inputData' => $email,
                    ],
                ],
            ],
        ]);

        $response = json_decode($response->getBody()->getContents(), true);

        return $response['entries']['data'][0];
    }
}
