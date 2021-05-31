# Laravel: mail marketing

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

```injectablephp
MailMarketing::driver()
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
