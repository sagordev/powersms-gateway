<?php

namespace Sagordev\PowersmsGateway\Providers;

use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application as LaravelApplication;
use Sagordev\PowersmsGateway\PowerSms;

class PowerSmsGatewayServiceProvider extends ServiceProvider
{
    public function boot(){
        $configFile = realpath(__DIR__ . '/../../config/powersms.php');

        if ($this->app instanceof LaravelApplication){
            $this->publishes([$configFile => config_path('powersms.php')]);
        }
        $this->mergeConfigFrom($configFile, 'powersms');
    }

    public function register()
    {
        $this->app->bind('powersms', function (Container $app) {
            return new PowerSms($app['config']->get('powersms'));
        });
    }
}