<?php

namespace Zarinpal;

use Illuminate\Support\ServiceProvider;
use Zarinpal\Drivers\RestDriver;
use Zarinpal\Drivers\SoapDriver;
use Zarinpal\Zarinpal;

class ZarinpalServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return Zarinpal\Zarinpal
     */
    public function register()
    {
        $this->app->singleton('Zarinpal', function () {
            $merchantID = (string) config('zarinpal.merchantID', 'test');
            $driver = (string) config('zarinpal.driver', 'Rest');
            $debug = (bool) config('zarinpal.debug', '0');
            if($debug) {
                $merchantID = 'test';
                $driver = new SoapDriver();
            }
            else {
                switch ($driver) {
                    case 'Soap':
                        $driver = new SoapDriver();
                        break;
                    default:
                        $driver = new RestDriver();
                        break;
                }
            }
            return new Zarinpal($merchantID, $driver, $debug);
        });
    }

    /**
     * Publish the plugin configuration.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/zarinpal.php' => config_path('zarinpal.php'),
        ]);
    }
}
