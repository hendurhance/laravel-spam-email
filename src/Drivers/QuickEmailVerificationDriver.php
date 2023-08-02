<?php

namespace Martian\SpamMailChecker\Drivers;

use Martian\SpamMailChecker\Abstracts\Driver;
use Martian\SpamMailChecker\Exceptions\SpamMailCheckerException;
use QuickEmailVerification\Client as QuickEmailVerificationClient;

class QuickEmailVerificationDriver extends Driver
{
    /**
     * @var string
     */
    protected $diverName = 'quickemailverification';

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var bool
     */
    protected $acceptDisposable;
    
    /**
     * @var QuickEmailVerificationClient
     */
    protected $client;

    /**
     * QuickEmailVerification Driver Constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->apiKey = $this->config->getApiKeyForDriver($this->diverName);
        $this->acceptDisposable = $this->config->getAcceptsDisposableEmailForDriver($this->diverName);
        $this->client = new QuickEmailVerificationClient($this->apiKey);
    }

    /**
     * Validate the email using the quickemailverification driver.
     *
     * @return bool
     * @throws SpamMailCheckerValidationException
     */
    public function validate(string $email): bool
    {
        try {
            $response = $this->getVerificationResponse($email);

            if($response['result'] !== 'valid') {
                return false;
            }

            if(!$this->acceptDisposable && $response['disposable']) {
                return false;
            }
            
            return true;
        } catch (\Exception $e) {
            throw new SpamMailCheckerException($e->getMessage());
        }
    }


    /**
     * Get the email verification response from the client.
     *
     * @param string $email
     * @return array|\stdClass
     */
    protected function getVerificationResponse(string $email): array|\stdClass
    {
        if (function_exists('app') && app()->environment() !== 'production') {
            return $this->client->quickemailverification()->sandbox($email)->body;
        } else {
            return $this->client->quickemailverification()->verify($email)->body;
        }
    }
}