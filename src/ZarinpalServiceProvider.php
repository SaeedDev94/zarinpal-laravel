<?php

namespace Zarinpal;

use Illuminate\Support\ServiceProvider;
use Zarinpal\Clients\GuzzleClient;
use Zarinpal\Clients\SoapClient;

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

        $this->app->bind('Zarinpal\Zarinpal', function () {
            $merchantID = (string) config('zarinpal.merchantID', 'test');
            $client = (string) config('zarinpal.client', 'Guzzle');
            $lang = (string) config('zarinpal.lang', 'fa');
            $sandbox = (bool) config('zarinpal.sandbox', '0');
            if ($client === 'Soap') {
                $client = new SoapClient($sandbox);
            } else {
                $client = new GuzzleClient($sandbox);
            }
            $zarinpal = new Zarinpal($merchantID, $client, $lang, $sandbox);

            return $zarinpal;
        });
    }
}
