<?php

namespace Zarinpal\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class GuzzleClient implements IClient
{
    function __construct($sandbox)
    {
        $sub = ($sandbox) ? 'sandbox' : 'api';
        $this->baseUrl = 'https://' . $sub . '.zarinpal.com/pg/v4/payment/';
    }

    public string $baseUrl;

    /**
     * Send requests to zarinpal
     * and receive responses.
     *
     * @param string $method
     * @param array $payload
     * @param array $headers
     *
     * @throws RequestException
     * @return array
     */
    function sendRequest(string $method, array $payload, array $headers = [])
    {
        $client = new Client([
            'base_uri' => $this->baseUrl
        ]);
        $response = $client->request('POST', "${method}.json", [
            'headers' => array_merge([
                'user-agent' => 'ZarinPal Rest Api v4',
                'cache-control' => 'no-cache',
                'content-type' => 'application/json',
                'accept' => 'application/json'
            ], $headers),
            'json' => $payload
        ]);
        $response = $response->getBody()->getContents();
        return json_decode($response, true);
    }
}
