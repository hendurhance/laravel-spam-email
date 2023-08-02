<?php

/**
 * This file is part of the Laravel Spam Mail Checker package.
 * 
 * (c) Josiah Endurance <hendurhance.dev@gmail.com
 * 
 * @package Laravel Spam Mail Checker
 * @license MIT
 * 
 * For the full license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @since 2.0.0
 * @see https://github.com/hendurhance/laravel-spam-email
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Laravel Spam Email Default Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the default email validation driver that will be
    | used to check if an email address is a spam address. This option is
    | set to 'local' by default. 
    |
    | Supported: "local", "remote", "abstractapi", "quickemailverification", "verifalia", "sendgrid"
    |
    | - Local: This driver uses a local file `resources/config/emails.txt` to check if an email address is a spam address.
    | - Remote: This driver uses php in-built functions to check if an email address is a spam address.
    | - AbstractAPI: This driver uses AbstractAPI to check if an email address is a spam address.
    |               @see https://www.abstractapi.com/email-verification-validation-api
    | - QuickEmailVerification: This driver uses QuickEmailVerification to check if an email address is a spam address.
    |               @see https://quickemailverification.com/
    | - Verifalia: This driver uses Verifalia to check if an email address is a spam address.
    |               @see https://verifalia.com/
    | - SendGrid: This driver uses SendGrid to check if an email address is a spam address.
    |               @see https://sendgrid.com/solutions/email-api/email-address-validation-api/
    |
    */
    
    'default' => env('SPAM_MAIL_CHECKER_DEFAULT_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Laravel Spam Email Drivers
    |--------------------------------------------------------------------------
    |
    | Here you may configure the settings for each driver that is used to check
    | if an email address is a spam address. You can add as many drivers as
    | you want.
    |
    */

    'drivers' => [
        'local' => [
            'path' => 'resources/config/emails.txt',
            'cache_key' => 'spammailchecker_' . base64_encode('resources/config/emails.txt'),
            'cache_ttl' => 60 * 60 * 24 * 7, // 1 week
            'whitelist' => [
                // Email domains that should not be considered as spam, this excludes the domain from the spam check.
                // Example: 'gmail.com', 'yahoo.com'
            ],
            'blacklist' => [
                // Email domains that should be considered as spam, this includes the domain in the spam check.
                // Example: 'mail.ru', 'yandex.ru'
            ]
        ],
        'remote' => [
            'timeout' => 5,
            'check_dns' => true,
            'check_smtp' => true,
            'check_mx' => true,
        ],
        'abstractapi' => [
            'api_url' => 'https://emailvalidation.abstractapi.com/v1',
            'api_key' => env('ABSTRACTAPI_API_KEY'),
            'score' => 0.5, // The score threshold to consider an email address as spam.
            'accept_disposable_email' => true, // This option is to be used with caution, as it may block legitimate email addresses.
        ],
        'quickemailverification' => [
            'api_url' => 'https://api.quickemailverification.com/v1',
            'api_key' => env('QUICKEMAILVERIFICATION_API_KEY'),
            'accept_disposable_email' => true, // This option is to be used with caution, as it may block legitimate email addresses.
        ],
        'verifalia' => [
            'api_url' => 'https://api.verifalia.com/', // The base URL of the Verifalia API.
            'base_uris' => [
                'https://api-1.verifalia.com/',
                'https://api-2.verifalia.com/',
                'https://api-3.verifalia.com/'
            ],
            'version' => 'v2.4', // The version of the Verifalia API to use.
            'username' => env('VERIFALIA_USERNAME'),
            'password' => env('VERIFALIA_PASSWORD'),
            'accept_disposable_email' => true, // This option is to be used with caution, as it may block legitimate email addresses.
        ],
        'sendgrid' => [
            'api_url' => 'https://api.sendgrid.com/v3/validations/email',
            'api_key' => env('SENDGRID_API_KEY'),
            'score' => 0.5, // The score threshold to consider an email address as spam.
            'accept_disposable_email' => true, // This option is to be used with caution, as it may block legitimate email addresses.
            'source' => 'signup', // The source of the email address to be validated.
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Laravel Spam Email Rule
    |--------------------------------------------------------------------------
    |
    | Here you may configure the settings for each rule that is used to check
    | if an email address is a spam address. You can change the rule to be
    | used to check if an email address is a spam address.
    |
    */

    'rule' => 'spammail', // The rule to be used to check if an email address is a spam address.

    /*
    |--------------------------------------------------------------------------
    | Laravel Spam Email Error Message
    |--------------------------------------------------------------------------
    |
    | Here you may configure the error message that is returned when an email
    | address is considered as a spam address.
    |
    */

    'error_message' => 'The :attribute is considered as a spam address.',
];
