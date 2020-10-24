<?php
use Zarinpal\Zarinpal;
use Zarinpal\Clients\GuzzleClient;

require_once '../vendor/autoload.php';

$merchantID = 'XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX';
$sandbox = true; // OR true
$zarinGate = false; // OR true
$client = new GuzzleClient($sandbox);
$lang = 'fa'; // OR en
$zarinpal = new Zarinpal($merchantID, $client, $lang, $sandbox, $zarinGate);

$payment = [
    'Authority' => $_GET['Authority'],
    'Status'    => $_GET['Status'],
    'Amount'    => 5000
];

$response = $zarinpal->verify($payment);

if($response['Status'] === 100) {
    echo 'Payment was successful,
        RefID: '.$response['RefID'].',
        Message: '.$response['Message'];
}
echo 'Error,
    Status: '.$response['Status'].',
    Message: '.$response['Message'];