<?php

namespace Zarinpal\Laravel;

use Illuminate\Support\ServiceProvider;

class ZarinpalServiceProvider extends ServiceProvider
{
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
