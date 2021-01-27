<?php

require_once 'ZarinpalConfig.php';

class RequestPayload
{
    const AMOUNT = 1000;
    const DESCRIPTION = 'Test payment';
    const CALLBACK_URL = ZarinpalConfig::SERVER['SCHEME'] . '://' . ZarinpalConfig::SERVER['HOST'] . ':{PORT}/Verify.php';
    const METADATA = [
        'EMAIL' => 'saeedp47@gmail.com'
    ];
}
