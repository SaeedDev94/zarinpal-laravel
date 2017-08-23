<?php

namespace Zarinpal;

use Illuminate\Support\ServiceProvider;
use Zarinpal\Drivers\RestDriver;
use Zarinpal\Drivers\SoapDriver;

class ZarinpalServiceProvider extends ServiceProvider
{
    /**
     * Register main class object.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Zarinpal', function () {
            $merchantID = (string) config('zarinpal.merchantID', 'test');
            $driver = (string) config('zarinpal.driver', 'Rest');
            $sandbox = (bool) config('zarinpal.sandbox', '0');
            switch ($driver) {
                case 'Soap':
                    $driver = new SoapDriver($sandbox);
                    break;
                default:
                    $driver = new RestDriver($sandbox);
                    break;
            }

            return new Zarinpal($merchantID, $driver, $sandbox);
        });
    }

    /**
     * Publish the package config file.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/zarinpal.php' => config_path('zarinpal.php'),
        ]);
    }
}
