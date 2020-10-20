<?php

use Zarinpal\Zarinpal;
use Zarinpal\Clients\GuzzleClient;

require_once '../vendor/autoload.php';

$merchantID = 'XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX';
$sandbox = true;
$zarinGate = false;
$client = new GuzzleClient($sandbox);
$lang = 'fa';
$zarinpal = new Zarinpal($merchantID, $client, $lang, $sandbox, $zarinGate);

$payment = [
    'CallbackURL' => 'http://127.0.0.1:800/callback.php',
    'Amount'      => 5000000,
    'Description' => 'a short description',
    'Email'       => 'saeedp47@gmail.com',
    'Mobile'      => '0933xxx7694'
];

$response = $zarinpal->request($payment);

if($response['Status'] === 100) {
    $authority = $response['Authority'];
    return $zarinpal->redirect($authority);
}
return 'Error,
    Status: '.$response['Status'].',
    Message: '.$response['Message'];