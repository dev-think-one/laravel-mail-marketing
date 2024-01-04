# Laravel: mail marketing

![Packagist License](https://img.shields.io/packagist/l/think.studio/laravel-mail-marketing?color=%234dc71f)
[![Packagist Version](https://img.shields.io/packagist/v/think.studio/laravel-mail-marketing)](https://packagist.org/packages/think.studio/laravel-mail-marketing)
[![Total Downloads](https://img.shields.io/packagist/dt/think.studio/laravel-mail-marketing)](https://packagist.org/packages/think.studio/laravel-mail-marketing)
[![Build Status](https://scrutinizer-ci.com/g/dev-think-one/laravel-mail-marketing/badges/build.png?b=main)](https://scrutinizer-ci.com/g/dev-think-one/laravel-mail-marketing/build-status/main)
[![Code Coverage](https://scrutinizer-ci.com/g/dev-think-one/laravel-mail-marketing/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/dev-think-one/laravel-mail-marketing/?branch=main)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/dev-think-one/laravel-mail-marketing/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/dev-think-one/laravel-mail-marketing/?branch=main)

Simple application layer where you can quickly change your mail marketing service

## Services

- Mailchimp

## Installation

Install the package via composer:

```bash
composer require think.studio/laravel-mail-marketing
```

Also need install your driver package:

```bash
# mailchimp
composer require drewm/mailchimp-api
# campaignmonitor
composer require campaignmonitor/createsend-php
```

You can publish the config file with:

```bash
php artisan vendor:publish --provider="MailMarketing\ServiceProvider" --tag="config"
```

Configuration in *.env* (optional)

```dotenv
MAILCHIMP_API_KEY=101....yj6-us15
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
