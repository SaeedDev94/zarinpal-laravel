<?php

use GuzzleHttp\Exception\RequestException;
use Zarinpal\Clients\GuzzleClient;
use Zarinpal\Zarinpal;

trait _Test
{
    private Zarinpal $zarinpal;

    function setZarinpal(): void
    {
        $sandbox = ZarinpalConfig::SANDBOX;
        $merchantID = ZarinpalConfig::MERCHANT_ID;
        $language = ZarinpalConfig::LANGUAGE;
        $zarinGate = ZarinpalConfig::ZARIN_GATE;

        $this->printLn('$sandbox: ' . ($sandbox ? 'true' : 'false'));
        $this->printLn('$merchantID: ' . $merchantID);
        $this->printLn('$merchantID length: ' . strlen($merchantID));
        $this->printLn('$language: ' . $language);
        $this->printLn('$zarinGate: ' . ($zarinGate ? 'true' : 'false'));

        $this->printLn('Creating $client: Zarinpal\Clients\GuzzleClient ...');

        $client = new GuzzleClient($sandbox);

        $this->printLn('$client created');
        $this->printLn('Creating $zarinpal: Zarinpal\Zarinpal ...');

        $zarinpal = new Zarinpal($merchantID, $client, $language, $sandbox, $zarinGate);

        $this->printLn('$zarinpal created');

        $this->zarinpal = $zarinpal;
    }

    function printObject(array $object): void
    {
        $this->printLn(json_encode($object, JSON_PRETTY_PRINT));
    }

    function printResponse(array $response): void
    {
        $code = $response['data']['code'];
        $message = $this->zarinpal->getCodeMessage($code);
        $this->printLn('========= Success =========');
        $this->printLn('$response:');
        $this->printObject($response);
        $this->printLn('$message: ' . $message);
    }

    function printRequestException(RequestException $exception): void
    {
        $this->printLn('========= Error =========');
        $this->printLn('Code: ' . $exception->getCode());
        $this->printLn('Message: ' . $exception->getMessage());
        if ($exception->hasResponse()) {
            $response = $exception->getResponse()->getBody()->getContents();
            $this->printLn('$response:');
            $this->printLn($response);
        }
        $this->printLn('File: ' . $exception->getFile());
        $this->printLn('Line: ' . $exception->getLine());
        $this->printLn('Trace:');
        $this->printLn($exception->getTraceAsString());
    }
}
