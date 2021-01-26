<?php

require_once 'vendor/autoload.php';
require_once 'config/RequestPayload.php';
require_once '_Test.php';

use GuzzleHttp\Exception\RequestException;

class Verify
{
    use _Test;

    function run()
    {
        $zarinpal = $this->getZarinpalInstance();
        $payload = [
            'amount' => RequestPayload::AMOUNT,
            'authority' => $_GET['Authority'],
        ];

        $this->printLn('Verify::run()');
        $this->printLn('$payload:');
        $this->printLn(json_encode($payload, JSON_PRETTY_PRINT));
        $this->printLn('Verifying the payment ...');

        try {
            $response = $zarinpal->verify($payload);

            $this->printLn('========= Success =========');
            $this->printLn('$response:');
            $this->printLn(json_encode($response, JSON_PRETTY_PRINT));
        } catch (RequestException $exception) {
            $this->handleRequestException($exception);
        }
    }

    function checkStatus()
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

    function checkAuthority()
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

    function printLn(string $message)
    {
        echo "<pre>${message}</pre>\n";
    }
}

$verify = new Verify();
if ($verify->checkStatus() && $verify->checkAuthority()) {
    $verify->run();
}
