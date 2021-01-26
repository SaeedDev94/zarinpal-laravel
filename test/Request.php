<?php

require_once 'vendor/autoload.php';
require_once 'config/ZarinpalConfig.php';
require_once 'config/RequestPayload.php';
require_once '_Test.php';

use GuzzleHttp\Exception\RequestException;

class Request
{
    use _Test;

    function run()
    {
        $zarinpal = $this->getZarinpalInstance();
        $payload = [
            'amount' => RequestPayload::AMOUNT,
            'description' => RequestPayload::DESCRIPTION,
            'callback_url' => RequestPayload::CALLBACK_URL,
            'metadata' => [
                'email' => 'saeedp47@gmail.com'
            ]
        ];

        $this->printLn('Request::run()');
        $this->printLn('$payload:');
        $this->printLn(json_encode($payload, JSON_PRETTY_PRINT));
        $this->printLn('Requesting for payment ...');

        try {
            $response = $zarinpal->request($payload);

            $this->printLn('========= Success =========');
            $this->printLn('$response:');
            $this->printLn(json_encode($response, JSON_PRETTY_PRINT));

            $code = $response['data']['code'];
            $authority = $response['data']['authority'];
            $message = $zarinpal->getCodeMessage($code);
            $paymentLink = $zarinpal->getRedirectUrl($authority);

            $this->printLn('$message: ' . $message);
            $this->printLn('$paymentLink: ' . $paymentLink);
            $this->printLn('Starting server ...');

            $server = ZarinpalConfig::SERVER['HOST'] . ':' . ZarinpalConfig::SERVER['PORT'];
            exec("php -S ${server}");
        } catch (RequestException $exception) {
            $this->handleRequestException($exception);
        }
    }

    function printLn(string $message)
    {
        echo "${message}\n";
    }
}

$request = new Request();
$request->run();
