<?php

namespace Martian\SpamMailChecker\Drivers;

use Martian\SpamMailChecker\Abstracts\Driver;
use Illuminate\Support\Facades\Cache;
use Martian\SpamMailChecker\Exceptions\SpamMailCheckerException;

class LocalDriver extends Driver
{
    /**
     * @var string
     */
    protected $cacheKey;

    /**
     * @var int
     */
    protected $cacheTTL;

    /**
     * Local Driver Constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->cacheKey = $this->config->getLocalCacheKey();
        $this->cacheTTL = $this->config->getLocalCacheTTL();
    }

    /**
     * Validate the email using the local driver.
     * 
     * @return bool
     */
    public function validate(string $email): bool
    {
        try {
            $emailDomain = $this->getDomain($email);

            $domainList = Cache::remember($this->cacheKey, $this->cacheTTL, function () {
                return $this->config->getLocalDomainList();
            });

            return !in_array($emailDomain, $domainList);
        } catch (\Exception $e) {
            throw new SpamMailCheckerException($e->getMessage());
        }
    }
}
