<?php

namespace Martian\SpamMailChecker\Drivers;

use Martian\SpamMailChecker\Abstracts\Driver;
use Illuminate\Support\Facades\Cache;

class LocalDriver extends Driver
{
    /**
     * Validate the email using the local driver.
     * 
     * @return bool
     */
    public function validate(string $email): bool
    {
        $emailDomain = $this->getDomain($email);

        $cacheKey = $this->config->getLocalCacheKey();
        $cacheTTL = $this->config->getLocalCacheTTL();

        $domainList = Cache::remember($cacheKey, $cacheTTL, function () {
            return $this->config->getLocalDomainList();
        });

        return !in_array($emailDomain, $domainList);
    }
}