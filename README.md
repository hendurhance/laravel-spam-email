# Laravel SpamMailChecker 

[![Latest Version on Packagist](https://img.shields.io/packagist/v/martian/spammailchecker.svg?style=flat-square)](https://packagist.org/packages/martian/laracaptcha) [![Total Downloads](http://poser.pugx.org/martian/spammailchecker/downloads)](https://packagist.org/packages/martian/spammailchecker) [![Latest Unstable Version](http://poser.pugx.org/martian/spammailchecker/v/unstable)](https://packagist.org/packages/martian/spammailchecker) [![License](http://poser.pugx.org/martian/spammailchecker/license)](https://packagist.org/packages/martian/spammailchecker) [![PHP Version Require](http://poser.pugx.org/martian/spammailchecker/require/php)](https://packagist.org/packages/martian/spammailchecker)
[![Made in Nigeria](https://img.shields.io/badge/made%20in-nigeria-008751.svg?style=flat-square)](https://github.com/acekyd/made-in-nigeria)


 A powerful Laravel package designed to effortlessly validate email addresses against various spam mail providers using a diverse range of drivers. Seamlessly integrated with Laravel's validation system, this package offers comprehensive support for validating email inputs in forms and RESTful APIs

## Supported Email Validation Services
| Service                    | Descriptions                                                                                                | Driver                   | Documentation                                                                       | Supported |
| -------------------------- | ----------------------------------------------------------------------------------------------------------- | ------------------------ | ----------------------------------------------------------------------------------- | --------- |
| **Local**                  | A local text list of spam email domains.                                                                    | *local*                  | [Read More](/resources/config/emails.txt)                                           | ✅ Yes     |
| **Remote**                 | Using PHP In-built functions `getmxrr()`, `checkdnsrr()`. `fsockopen()` to validate email domain            | *remote*                 | [Read More](https://www.php.net/manual/en/function.getmxrr.php)                     | ✅ Yes     |
| **AbstractApi**            | Using Abstract's suite of API to validate email domains                                                     | *abstractapi*            | [Read More](https://www.abstractapi.com/email-verification-validation-api)          | ✅ Yes     |
| **QuickEmailVerification** | A reliable, accurate, affordable, and advanced email verification service                                   | *quickemailverification* | [Read More](https://quickemailverification.com/)                                    | ✅ Yes     |
| **Verifalia**              | A web-based email validation service which allows to upload and validate lists of email addresses with ease | *verifalia*              | [Read More](https://verifalia.com/)                                                 | ✅ Yes     |
| **SendGrid**               | A cloud-based SMTP provider that allows you to validate email addresses before you send.                    | *sendgrid*               | [Read More](https://sendgrid.com/solutions/email-api/email-address-validation-api/) | ✅ Yes     |

> **NOTE:** More services will be added soon. (You can also contribute to this project by adding more services - ZeroBounce, Mailboxlayer, EmailListVerify, Emailable, etc)

## Installation

You can install the package via composer:

```bash
composer require martian/spammailchecker
```
- If you are using Laravel 5.5 or higher, you can use the package directly: `composer require 'martian/spammailchecker`. Check [Laravel 5.5 Package Discovery](https://laravel.com/docs/5.5/packages#package-discovery) for more information
- If you're using Laravel 5.4 or lower, you'll need to register the service provider. Open `config/app.php` and add the following line to the `providers` array:

```php
Martian\SpamMailChecker\SpamMailCheckerServiceProvider::class,
```
- If you are going to be using the facade, you'll need to register it as well. Open `config/app.php` and add the following line to the `aliases` array:

```php
'SpamMailChecker' => Martian\SpamMailChecker\Facades\SpamMailChecker::class,
```

## Publish Configuration File
Publish the configuration file using the following command:

```bash
php artisan vendor:publish --provider="Martian\SpamMailChecker\Providers\SpamMailCheckerServiceProvider"
```

## Configuration
The configuration file is located at `config/laravel-spammail-checker.php`. You may configure the package to use any of the supported drivers. The default driver is `local` which uses a local text list of spam email domains.

#### Local Driver Configuration
- In order to use `local`as your driver of choice, you need to set the `default` key in the `config/laravel-spammail-checker.php` configuration file to `local`:

    ```php
        'default' => 'local',
    ```
- Or you can set the `SPAM_MAIL_CHECKER_DEFAULT_DRIVER` environment variable to `local` in your `.env` file.

    ```env
        SPAM_MAIL_CHECKER_DEFAULT_DRIVER=local
    ```
- The local driver uses a local text list of spam email domains. The file is located at `resources/config/emails.txt`. You can include more domains by adding them to the `blacklist` array or exclude domains by adding them to the `whitelist` array.

    ```php
        'drivers' => [
            'local' => [
                ...
                'whitelist' => [
                    // Add domains you want the local driver to ignore here
                    'gmail.com',
                    'yahoo.com',
                ],
                'blacklist' => [
                    // Add domains you want the local driver to validate against here
                    'mailinator.com',
                    'spam.com',
                ],
            ]
        ]
    ```
- Clear the config and cache using the following commands after making changes to the configuration file:

    ```bash
        php artisan optimize:clear
    ```
> **NOTE:** The local driver is case-insensitive. So, you don't need to worry about the case of the email domain.

#### Remote Driver Configuration
- In order to use `remote` as your driver of choice, you need to set the `default` key in the `config/laravel-spammail-checker.php` configuration file to `remote`:

    ```php
        'driver' => 'remote',
    ```
- Or you can set the `SPAM_MAIL_CHECKER_DEFAULT_DRIVER` environment variable to `remote` in your `.env` file.

    ```env
        SPAM_MAIL_CHECKER_DEFAULT_DRIVER=remote
    ```
- The remote driver uses PHP In-built functions `getmxrr()`, `checkdnsrr()`. `fsockopen()` to validate email domain. You can configure the remote driver on whether to check for MX - `getmxrr()`, DNS - `checkdnsrr()`, and SMTP -`fsockopen` or validate email domain. You can also configure the timeout value in seconds.

    ```php
        'drivers' => [
            ...
            'remote' => [
                ...
                'check_dns' => true, // When set to true, it will check for DNS
                'check_smtp' => false, // When set to true, it will check for SMTP
                'check_mx' => false, // When set to true, it will check for MX record
                'timeout' => 60 * 5, // 5 minutes
            ]
        ]
    ```

#### AbstractApi Driver Configuration
- In order to use `abstractapi` as your driver of choice, you need to set the `default` key in the `config/laravel-spammail-checker.php` configuration file to `abstractapi`:

    ```env
        'default' => 'abstractapi',
    ```
- Or you can set the `SPAM_MAIL_CHECKER_DEFAULT_DRIVER` environment variable to `abstractapi` in your `.env` file.

    ```env
        SPAM_MAIL_CHECKER_DEFAULT_DRIVER=abstractapi
    ```
- Add your `ABSTRACTAPI_API_KEY` AbstractAPI key you got from [here](https://app.abstractapi.com/dashboard) to your `env` file.

  ```
  ABSTRACTAPI_API_KEY=abstractapi_api_key
  ```
- You can configure the `score` to determine the threshold for a valid email address. The score ranges from 0 to 1. The higher the score, the more likely the email address is valid. You can also accept disposable email addresses by setting `accept_disposable` to `true`.

    ```php
        'drivers' => [
            ...
            'abstractapi' => [
                ...
                'score' => 0.5, // 0.5 is the default score
                'accept_disposable_email' => true // When set to true, it will accept disposable email addresses
            ]
        ]
    ```

#### QuickEmailVerification Driver Configuration
- In order to use `quickemailverification` as your driver of choice, you need to set the `default` key in the `config/laravel-spammail-checker.php` configuration file to `quickemailverification`:

    ```env
        'default' => 'quickemailverification',
    ```
- Or you can set the `SPAM_MAIL_CHECKER_DEFAULT_DRIVER` environment variable to `quickemailverification` in your `.env` file.

    ```env
        SPAM_MAIL_CHECKER_DEFAULT_DRIVER=quickemailverification
    ```
- Add your `QUICKEMAILVERIFICATION_API_KEY` QuickEmailVerification key you got from [here](https://quickemailverification.com/dashboard) to your `env` file.
  ```env
  QUICKEMAILVERIFICATION_API_KEY=quickemailverification_api_key
  ```
- You can configure the driver to accept disposable email addresses by setting `accept_disposable` to `true`.

    ```php
        'drivers' => [
            ...
            'quickemailverification' => [
                ...
                'accept_disposable' => true, // When set to true, it will accept disposable email addresses
            ]
        ]
    ```

#### Verifalia Driver Configuration
- In order to use `verifalia` as your driver of choice, you need to set the `default` key in the `config/laravel-spammail-checker.php` configuration file to `verifalia`:

    ```env
        'default' => 'verifalia',
    ```
- Or you can set the `SPAM_MAIL_CHECKER_DEFAULT_DRIVER` environment variable to `verifalia` in your `.env` file.

    ```env
        SPAM_MAIL_CHECKER_DEFAULT_DRIVER=verifalia
    ```
- In order to use verifalia service, you need to set login credentials in your `env` file. You can get your credentials from after you create a user [here](https://verifalia.com/client-area#/users).

  ```env
  VERIFALIA_USERNAME=verifalia_username
  VERIFALIA_PASSWORD=verifalia_password
  ```

- You can configure the driver to accept disposable email addresses by setting `accept_disposable` to `true`.

    ```php
        'drivers' => [
            ...
            'verifalia' => [
                ...
                'accept_disposable' => true, // When set to true, it will accept disposable email addresses
            ]
        ]
    ```
> **NOTE:** A user on verifalia needs to be granted permission to use the API. You can do this by going to [here](https://verifalia.com/client-area#/users) and clicking on the edit user you want to grant permission to. Then click on the `Permissions` tab and check the appropriate permissions on `Email validations` section.

#### SendGrid Driver Configuration
- In order to use `sendgrid` as your driver of choice, you need to set the `default` key in the `config/laravel-spammail-checker.php` configuration file to `sendgrid`:

    ```env
        'default' => 'sendgrid',
    ```
- Or you can set the `SPAM_MAIL_CHECKER_DEFAULT_DRIVER` environment variable to `sendgrid` in your `.env` file.

    ```env
        SPAM_MAIL_CHECKER_DEFAULT_DRIVER=sendgrid
    ```
- Add your `SENDGRID_API_KEY` Sendgrid key you got from [here](https://app.sendgrid.com/settings/api_keys) to your `env` file.
  ```env
    SENDGRID_API_KEY=sendgrid_api_key
    ```
- You can configure the driver to accept disposable email addresses by setting `accept_disposable` to `true`. Score can also be configured to determine the threshold for a valid email address. The score ranges from 0 to 1. The higher the score, the more likely the email address is valid. Source can also be configured to determine the source of the email address. The source can be `signup` or `contact`.

    ```php
        'drivers' => [
            ...
            'sendgrid' => [
                ...
                'score' => 0.5, // 0.5 is the default score
                'accept_disposable' => true, // When set to true, it will accept disposable email addresses
                'source' => 'signup' // The source is signup by default
            ]
        ]
    ```

## Usage

In order to use the package, you need to call the `spammail` validation rule in your validation rules. You can also change the rule name to whatever you want in the `config/laravel-spammail-checker.php` configuration file under the `rule` key, likewise the error message under the `error_message` key.

```php
/**
 * Get a validator for an incoming registration request.
 *
 * @param  array  $data
 * @return \Illuminate\Contracts\Validation\Validator
 */
protected function validator(array $data)
{
    return Validator::make($data, [
        'email' => 'required|spammail|max:255',
    ]);
}
```
Or make use of `spammail` in your Requests file like this:
```php
 /**
 * Get the validation rules that apply to the request.
 *
 * @return array
 */
public function rules()
{
    return [
        'email' => 'required|spammail|max:255',
    ];
}
```

#### Adding Custom Error Message
A custom error message can be added to the `spammail` validation rule.
    
```php
    'email.spammail' => 'This email address is invalid.', // Custom error message
```
Or you can change the error message in the `config/laravel-spammail-checker.php` configuration file under the `error_message` key.

```php
    'error_message' => 'This email address is invalid.', // Custom error message
```

#### Using Classes Directly
You can also use the classes directly without using the `spammail` validation rule. This is useful when you want to use the package in your own custom validation rule or your own custom class.

```php
use Martian\SpamMailChecker\SpamMailChecker;

public function checkEmail($email)
{
    $spamMailChecker = new SpamMailChecker();
    $spamMailChecker->validate($email);
}
```

#### Using Facade

You can also use the `SpamMailChecker` class directly without instantiating it.

```php
use Martian\SpamMailChecker\Facades\SpamMailChecker;

public function checkEmail($email)
{
    SpamMailChecker::validate($email);
}
```

#### Using Each Driver Individually
You can also use each driver individually without using the `spammail` validation rule. This is useful when a certain driver is needed in a particular situation.
##### Using VerifaliaDriver
```php
use Martian\SpamMailChecker\Drivers\VerifaliaDriver;

public function checkEmail($email)
{
    $verifaliaDriver = new VerifaliaDriver();
    $verifaliaDriver->validate($email);
}
```
##### Using SendGridDriver
```php
use Martian\SpamMailChecker\Drivers\SendGridDriver;

public function checkEmail($email)
{
    $sendGridDriver = new SendGridDriver();
    $sendGridDriver->validate($email);
}
```
##### Using AbstractApiDriver
```php
use Martian\SpamMailChecker\Drivers\AbstractApiDriver;

public function checkEmail($email)
{
    $abstractApiDriver = new AbstractApiDriver();
    $abstractApiDriver->validate($email);
}
```
##### Using RemoteDriver
```php
use Martian\SpamMailChecker\Drivers\RemoteDriver;

public function checkEmail($email)
{
    $remoteDriver = new RemoteDriver();
    $remoteDriver->validate($email);
}
```
##### Using LocalDriver
```php
use Martian\SpamMailChecker\Drivers\LocalDriver;

public function checkEmail($email)
{
    $localDriver = new LocalDriver();
    $localDriver->validate($email);
}
```
##### Using QuickEmailVerificationDriver
```php
use Martian\SpamMailChecker\Drivers\QuickEmailVerificationDriver;

public function checkEmail($email)
{
    $quickEmailVerificationDriver = new QuickEmailVerificationDriver();
    $quickEmailVerificationDriver->validate($email);
}
```


### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please feel free to fork this project and make a pull request. For more information check [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email hendurhance.dev@gmail.com instead of using the issue tracker.

## Credits

-   [Josiah Endurance](https://github.com/hendurhance)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

