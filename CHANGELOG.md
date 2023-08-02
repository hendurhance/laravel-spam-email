# Changelog

All notable changes to `spammailchecker` will be documented in this file

## 1.0.0 - 2022-01-25 (Deprecated)

- initial release
- [DEPRECATED] This package is deprecated and will no longer be maintained. Please use version 2.0.0 or higher.
  
## 2.0.0 - 2023-07-02
- [ADDED] Added Laravel 10 support.
- [IMPROVED] Improved compatibility with Laravel versions 5 and above.
- [IMPROVED] Improved the SpamMailChecker class to support multiple API drivers.
- [ADDED] Added driver support for the following email validation services:
  - [x] [Local](/resources/config/emails.txt)
  - [x] [Remote](https://www.php.net/manual/en/function.getmxrr.php)
  - [x] [QuickEmailVerification](https://quickemailverification.com/)
  - [x] [Verifalia](https://verifalia.com/)
  - [x] [AbstractApi](https://www.abstractapi.com/api/email-verification-validation-api)
  - [x] [SendGrid](https://sendgrid.com/solutions/email-validation-api/)
  - [ ] [ZeroBounce](https://www.zerobounce.net/) 
  - [ ] [MailboxValidator](https://www.mailboxvalidator.com/)
  - [ ] [EmailListVerify](https://www.emaillistverify.com/)
  - [ ] [EmailChecker](https://www.emailchecker.com/)
  
- [ADDED] Added the config file `config/laravel-spammail-checker.php` to handle package configuration.
- [ADDED] Added Abstract Driver class `Driver.php` to handle API driver configuration and validation using the `DriverInterface.php` interface.
- [ADDED] Added Config Builder class `ConfigBuilder.php` to handle package configuration building.
- [ADDED] Added Exception classes to handle package exceptions.
- [ADDED] Added SpamMailCheckerServiceProvider to handle package registration and validation rule extension.
- [CHANGED] The SpamMailChecker class now implements the DriverInterface.
- [DEPRECATED] Deprecated package versions less than 2.0.0. The package will no longer receive updates for versions 1.0.0 and below.
- [ADDED] Add tests for all supported email validation services.

## 2.1.0 (Upcoming)
- [ADDED] Added support for Laravel ^10x.
- [ADDED] Added support for the rest of the email validation services.
  - [ ] [ZeroBounce](https://www.zerobounce.net/) 
  - [ ] [MailboxValidator](https://www.mailboxvalidator.com/)
  - [ ] [EmailListVerify](https://www.emaillistverify.com/)
  - [ ] [Emailable](https://emailable.com/)
- [IMPROVED] Enhanced error handling and exception messages for better debugging.
- [IMPROVED] Improved package documentation with more examples and usage guidelines.
- [FIXED] Addressed reported issues and bugs from previous versions.
- [IMPROVED] Optimized package code for reduced memory usage and increased speed.