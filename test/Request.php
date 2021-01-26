<?php

require_once '../vendor/autoload.php';
require_once 'config/RequestPayload.php';
require_once '_Test.php';

use GuzzleHttp\Exception\RequestException;

class Request
{
    use _Test;

    function run(): void
    {
        $this->setZarinpal();
        $payload = [
            'amount' => RequestPayload::AMOUNT,
            'description' => RequestPayload::DESCRIPTION,
            'callback_url' => RequestPayload::CALLBACK_URL,
            'metadata' => [
                'email' => RequestPayload::METADATA['EMAIL']
            ]
        ];

        $this->warning();

        $this->printLn('Request::run()');
        $this->printLn('$payload:');
        $this->printLn(json_encode($payload, JSON_PRETTY_PRINT));
        $this->printLn('Requesting for payment ...');

        try {
            $response = $this->zarinpal->request($payload);
            $this->printResponse($response);

            $authority = $response['data']['authority'];
            $paymentLink = $this->zarinpal->getRedirectUrl($authority);

            $this->printLn('$paymentLink: ' . $paymentLink);
            $this->printLn('Starting server ...');

            $server = ZarinpalConfig::SERVER['HOST'] . ':' . ZarinpalConfig::SERVER['PORT'];
            exec("php -S ${server}");
        } catch (RequestException $exception) {
            $this->handleRequestException($exception);
        }
    }

    function warning(): void
    {
        $this->printLn('========= WARNING =========');
        $this->printLn("Since sandbox disabled by zarinpal team,");
        $this->printLn("This test is a real payment and you must spend real money!");
        $this->printLn("However the amount is minimum as possible");
        $this->printLn("Do you want to continue?");
        $this->printLn("1- Yes, 2- No, 3- Enter a custom merchantID [2]");
        $option = trim(fgets(STDIN));
        switch ($option) {
            case '1':
                // Accepted
                break;
            case '3':
                $this->printLn('Enter merchantID:');
                $merchantID = trim(fgets(STDIN));
                if (strlen($merchantID) !== 36) {
                    $this->printLn('merchantID length must be 36');
                    $this->printLn('ABORTING ...');
                    exit;
                }
                $this->zarinpal->merchantID = $merchantID;
                break;
            default:
                $this->printLn('ABORTING ...');
                exit;
        }
    }

    function printLn(string $message): void
    {
        echo "${message}\n";
    }
}

$request = new Request();
$request->run();
