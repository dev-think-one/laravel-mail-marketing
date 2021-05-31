<?php

namespace MailMarketing;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/mail-marketing.php' => config_path('mail-marketing.php'),
            ], 'config');


            $this->commands([]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/mail-marketing.php', 'mail-marketing');

        $this->app->bind('mail-marketing', function ($app) {
            return new MailMarketingManager($app);
        });
    }
}
