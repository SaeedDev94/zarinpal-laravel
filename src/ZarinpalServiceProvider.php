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
        $this->app->instance(Zarinpal::class, $this->getInstance());
    }

    private function getInstance()
    {
        $merchantID = (string) config('zarinpal.merchantID', 'test');
        $clientType = (string) config('zarinpal.client', 'Guzzle');
        $lang = (string) config('zarinpal.lang', 'fa');
        $sandbox = (bool) config('zarinpal.sandbox', '0');
        $zaringate = (bool) config('zarinpal.zaringate', '0');
        $zaringatePSP = (string) config('zarinpal.zaringate_psp', '');
        $client = ($clientType === 'Soap') ? new SoapClient($sandbox) : new GuzzleClient($sandbox);
        return new Zarinpal($merchantID, $client, $lang, $sandbox, $zaringate, $zaringatePSP, true);
    }
}
