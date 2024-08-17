<?php

namespace Rpagency\LaravelPaysera;

use Illuminate\Support\ServiceProvider;

class PayseraServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/paysera.php', 'paysera');

        $this->app->singleton('paysera', function () {
            return new PayseraClient();
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/paysera.php' => config_path('paysera.php'),
        ]);
    }
}
