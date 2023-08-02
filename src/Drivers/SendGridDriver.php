<?php

namespace Martian\SpamMailChecker\Drivers;

use Martian\SpamMailChecker\Abstracts\Driver;
use Martian\SpamMailChecker\Exceptions\SpamMailCheckerException;

class SendGridDriver extends Driver
{
    /**
     * @var string
     */
    protected $diverName = 'sendgrid';

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $apiUrl;

    /**
     * @var float
     */
    protected $score;

    /**
     * @var bool
     */
    protected $acceptDisposable;

    /**
     * @var string
     */
    protected $source;

    /**
     * AbstractApi Driver Constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->apiKey = $this->config->getApiKeyForDriver($this->diverName);
        $this->apiUrl = $this->config->getApiUrlForDriver($this->diverName);
        $this->score = $this->config->getScoreForDriver($this->diverName);
        $this->acceptDisposable = $this->config->getAcceptsDisposableEmailForDriver($this->diverName);
        $this->source = $this->config->getSourceForDriver($this->diverName);
    }

    /**
     * Validate the email using the sendgrid driver.
     * 
     * @return bool
     */
    public function validate(string $email): bool
    {
        try {
            $response = $this->getVerificationResponse($email);

            if ($response['result']['verdict'] !== 'Valid' || $response['result']['score'] > $this->score) {
                return false;
            }

            if ($response['result']['checks']['domain']['is_suspected_disposable_address'] && $this->acceptDisposable) {
                return false;
            }

            return true;
        } catch (\Exception $e) {
            throw new SpamMailCheckerException($e->getMessage());
        }
    }

    /**
     * Get the response from the sendgrid API.
     * 
     * @return array
     */
    protected function getVerificationResponse(string $email): array
    {
        $response = $this->client->request('GET', $this->apiUrl, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'email' => $email,
                'source' => $this->source
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
