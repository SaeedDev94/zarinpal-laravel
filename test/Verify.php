<?php

require_once '../vendor/autoload.php';
require_once 'config/RequestPayload.php';
require_once '_Test.php';

use GuzzleHttp\Exception\RequestException;

class Verify
{
    use _Test;

    function run(): void
    {
        $this->setZarinpal();
        $payload = [
            'amount' => RequestPayload::AMOUNT,
            'authority' => $_GET['Authority'],
        ];

        $this->printLn('Verify::run()');
        $this->printLn('$payload:');
        $this->printObject($payload);
        $this->printLn('Verifying the payment ...');

        try {
            $response = $this->zarinpal->verify($payload);
            $this->printResponse($response);
        } catch (RequestException $exception) {
            $this->printRequestException($exception);
        }
    }

    function checkStatus(): bool
    {
        if (!isset($_GET['Status'])) {
            $this->printLn('No "Status" QueryString');
            return false;
        }
        if ($_GET['Status'] !== 'OK') {
            $this->printLn('"Status" QueryString is not equal to "OK"');
            return false;
        }
        return true;
    }

    function checkAuthority(): bool
    {
        if (!isset($_GET['Authority'])) {
            $this->printLn('No "Authority" QueryString');
            return false;
        }
        if (trim($_GET['Authority']) === '') {
            $this->printLn('"Authority" QueryString is empty');
            return false;
        }
        return true;
    }

    function printLn(string $message): void
    {
        echo "<pre>${message}</pre>\n";
    }
}

$verify = new Verify();
if ($verify->checkStatus() && $verify->checkAuthority()) {
    $verify->run();
}
