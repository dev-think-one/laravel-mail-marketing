# Laravel: mail marketing

![Packagist License](https://img.shields.io/packagist/l/yaroslawww/laravel-mail-marketing?color=%234dc71f)
[![Packagist Version](https://img.shields.io/packagist/v/yaroslawww/laravel-mail-marketing)](https://packagist.org/packages/yaroslawww/laravel-mail-marketing)
[![Total Downloads](https://img.shields.io/packagist/dt/yaroslawww/laravel-mail-marketing)](https://packagist.org/packages/yaroslawww/laravel-mail-marketing)
[![Build Status](https://scrutinizer-ci.com/g/yaroslawww/laravel-mail-marketing/badges/build.png?b=master)](https://scrutinizer-ci.com/g/yaroslawww/laravel-mail-marketing/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/yaroslawww/laravel-mail-marketing/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/yaroslawww/laravel-mail-marketing/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/yaroslawww/laravel-mail-marketing/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/yaroslawww/laravel-mail-marketing/?branch=master)

Simple application layer where you can quickly change your mail marketing service

## Services

- Mailchimp

## Installation

Install the package via composer:

```bash
composer require yaroslawww/laravel-mail-marketing
```

If you use mailchimp than you need also install mailchimp package

```bash
composer require drewm/mailchimp-api
```

You can publish the config file with:

```bash
php artisan vendor:publish --provider="MailMarketing\ServiceProvider" --tag="config"
```

Configuration in *.env* (optional)

```dotenv
AILCHIMP_API_KEY=101....yj6-us15
```

## Usage

```php
\MailMarketing\Facades\MailMarketing::driver()
            ->addMembersToList(
                $this->listId,
                $this->members
            );
// or
MailMarketing::addList($name, $data);
// or
MailMarketing::driver('mailchimp')->addList($name, $data);
```

A complete list of methods can be found in the [interface](./src/Drivers/MailMarketingInterface.php)

## Credits

- [![Think Studio](https://yaroslawww.github.io/images/sponsors/packages/logo-think-studio.png)](https://think.studio/)
