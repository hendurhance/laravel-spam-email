<?php

/**
 * This file is part of the Laravel Spam Mail Checker package.
 * 
 * (c) Josiah Endurance <hendurhance.dev@gmail.com>
 * 
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Martian\SpamMailChecker;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class SpamMailCheckerServiceProvider extends ServiceProvider
{
    /**
     * Indicate if loading of the provider is deferred.
     * 
     */
    protected $defer = false;


    /**
     * Default error message.
     * 
     * @var string
     */
    protected $errorMessage = 'The email address is a spam address, please try another one.';


    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('spammail', function ($attribute, $value, $parameters, $validator) {
            $path = realpath(__DIR__ . '/../resources/config/emails.txt');
            $cacheKey = 'spammailchecker_' . base64_encode($path);

            $data = Cache::rememberForever($cacheKey, function () use ($path) {
                return collect(explode("\n", file_get_contents($path)))
                    ->map(function ($item) {
                        return strtolower(trim($item));
                    });
            });
            // check if the email address domain has any of the spam domains
            $domain = strtolower(substr(strrchr($value, "@"), 1));
            return !$data->contains($domain);
        }, $this->errorMessage);
    }


    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }


    /**
     * Get the services provided by the provider.
     * 
     * @return array
     */
    public function provides()
    {
        return ['laravel-spam-email'];
    }
}
