# laravel-spam-email

[![Latest Stable Version](http://poser.pugx.org/martian/spammailchecker/v)](https://packagist.org/packages/martian/spammailchecker) [![Total Downloads](http://poser.pugx.org/martian/spammailchecker/downloads)](https://packagist.org/packages/martian/spammailchecker) [![Latest Unstable Version](http://poser.pugx.org/martian/spammailchecker/v/unstable)](https://packagist.org/packages/martian/spammailchecker) [![License](http://poser.pugx.org/martian/spammailchecker/license)](https://packagist.org/packages/martian/spammailchecker) [![PHP Version Require](http://poser.pugx.org/martian/spammailchecker/require/php)](https://packagist.org/packages/martian/spammailchecker)
[GitHub Actions](https://github.com/martian/spammailchecker/actions/workflows/main.yml/badge.svg)

This package is a Laravel package that checks if an email address is a spammer. It verifies your signups and form submissions to confirm that they are legitimate.

## Installation

You can install the package via composer:

```bash
composer require martian/spammailchecker
```
- If you are using Laravel 5.5 or higher, you can use the package directly: `composer require 'martian/spammailchecker'`.
- If you're using Laravel 5.4 or lower, you'll need to register the service provider. Open `config/app.php` and add the following line to the `providers` array:

```php
Martian\SpamMailChecker\SpamMailCheckerServiceProvider::class,
```

## Usage

Make use of `spammail` in your validation rules:

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
        'email' => 'required|email|spammail|max:255',
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
        'email' => 'required|email|spammail|max:255',
    ];
}
```

Error message shown when the email is a spam:
<img width="1095" alt="api screenshot error" src="https://res.cloudinary.com/dogediscuss/image/upload/v1643156715/api_uuovea.png">

By default, the error message is:
<pre>
    "email": [
        "The email address is a spam address, please try another one."
    ]
</pre>

You can customize the error message by opening `resources/lang/en/validation.php` and adding to the array like so:

```php
  'spammail' => 'You are using a spam email address nerd :-P',
```

<img width="1093" alt="screen shot 2016-07-02 at 2 12 14 pm" src="https://res.cloudinary.com/dogediscuss/image/upload/v1643157039/api2_e8fobx.png">


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
-   [All Contributors](../../contributors)

## How to appreciate the work

Here are some ways you can give back to the project:
-  [Star on GitHub](https://github.com/hendurhance/laravel-spam-email)
-  Follow me on Twitter [@hendurhance](https://twitter.com/hendurhance)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

