<?php

namespace Zarinpal;

use Illuminate\Support\ServiceProvider;
use Zarinpal\Drivers\RestDriver;
use Zarinpal\Drivers\SoapDriver;

class ZarinpalServiceProvider extends ServiceProvider
{
    /**
     * Register default config
     * and main class instance.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/zarinpal.php', 'zarinpal');

        $this->app->singleton('Zarinpal\Zarinpal', function () {
            $merchantID = (string) config('zarinpal.merchantID', 'test');
            $driver = (string) config('zarinpal.driver', 'Rest');
            $lang = (string) config('zarinpal.lang', 'fa');
            $sandbox = (bool) config('zarinpal.sandbox', '0');
            switch ($driver) {
                case 'Soap':
                    $driver = new SoapDriver($sandbox);
                    break;
                default:
                    $driver = new RestDriver($sandbox);
                    break;
            }

            return new Zarinpal($merchantID, $driver, $lang, $sandbox);
        });
    }
}
