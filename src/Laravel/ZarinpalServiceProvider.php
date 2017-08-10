<?php

namespace Zarinpal\Laravel;

use Illuminate\Support\ServiceProvider;
use Zarinpal\Drivers\SoapDriver;
use Zarinpal\Zarinpal;

class ZarinpalServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return \Zarinpal\Zarinpal
     */
    public function register()
    {
        $this->app->singleton('Zarinpal', function () {
            $merchantID = config('Zarinpal.merchantID', 'XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX');
            $driver = new SoapDriver();

            return new Zarinpal($merchantID, $driver);
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
