<?php

namespace Zarinpal;

use Illuminate\Support\ServiceProvider;
use Zarinpal\Drivers\RestDriver;
use Zarinpal\Drivers\SoapDriver;
use Zarinpal\Zarinpal;

class ZarinpalServiceProvider extends ServiceProvider
{
    /**
     * Register main class instance
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Zarinpal', function () {
            $merchantID = (string) config('zarinpal.merchantID', 'test');
            $driver = (string) config('zarinpal.driver', 'Rest');
            $debug = (bool) config('zarinpal.debug', '0');
            switch ($driver) {
                case 'Soap':
                    $driver = new SoapDriver();
                    break;
                default:
                    $driver = new RestDriver();
                    break;
            }
            return new Zarinpal($merchantID, $driver, $debug);
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
