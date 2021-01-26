<?php

namespace Zarinpal\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class GuzzleClient implements IClient
{
    function __construct($sandbox)
    {
        $sub = ($sandbox) ? 'sandbox' : 'api';
        $this->baseUrl = 'https://' . $sub . '.zarinpal.com/pg/v4/payment/';
    }

    public $baseUrl;

    /**
     * Send requests to zarinpal
     * and receive responses.
     *
     * @param string $method
     * @param array $payload
     * @param array $headers
     *
     * @return array
     * @throws GuzzleException
     */
    function sendRequest(string $method, array $payload, array $headers = [])
    {
        $client = new Client([
            'base_uri' => $this->baseUrl
        ]);
        $response = $client->request('POST', "${method}.json", [
            'headers' => $headers,
            'json' => $payload
        ]);
        $response = $response->getBody()->getContents();
        return json_decode($response, true);
    }
}
