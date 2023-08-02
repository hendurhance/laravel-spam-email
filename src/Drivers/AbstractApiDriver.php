<?php

namespace Martian\SpamMailChecker\Drivers;

use GuzzleHttp\Exception\RequestException;
use Martian\SpamMailChecker\Abstracts\Driver;
use Martian\SpamMailChecker\Exceptions\SpamMailCheckerException;

class AbstractApiDriver extends Driver
{
    /**
     * @var string
     */
    protected $diverName = 'abstractapi';

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
     * AbstractApi Driver Constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->apiKey = $this->config->getApiKeyForDriver($this->diverName);
        $this->apiUrl = $this->config->getApiUrlForDriver($this->diverName);
        $this->score = $this->config->getScoreForDriver($this->diverName);
        $this->acceptDisposable = $this->config->getAcceptsDisposableEmailForDriver($this->diverName);
    }

    /**
     * Validate the email using the remote driver.
     *
     * @return bool
     * @throws SpamMailCheckerValidationException
     */
    public function validate(string $email): bool
    {
        try {
            $response = $this->client->request('GET', $this->apiUrl, [
                'query' => [
                    'email' => $email,
                    'api_key' => $this->apiKey,
                ],
            ]);

            $response = json_decode($response->getBody()->getContents(), true);

            if (is_numeric($response['quality_score']) && floatval($response['quality_score']) < $this->score) {
                return false;
            }

            if (!$this->acceptDisposable && $response['is_disposable_email']['value']) {
                return false;
            }

            return true;
        } catch (RequestException $e) {
            throw new SpamMailCheckerException($e->getMessage());
        }
    }
}
