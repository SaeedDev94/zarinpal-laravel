<?php

class ZarinpalConfig {
    const SERVER = [
        'SCHEME' => 'http',
        'HOST' => 'localhost',
        'PORT' => 8000
    ];
    /**
     * @var bool SANDBOX
     * Since zarinpal sandbox isn't active, we can't use it at all
     */
    const SANDBOX = false;
    /**
     * @var string MERCHANT_ID
     * Also we must use a real merchantID here
     */
    const MERCHANT_ID = '1344b5d4-0048-11e8-94db-005056a205be';
    const LANGUAGE = 'fa';
    const ZARIN_GATE = false;
}
