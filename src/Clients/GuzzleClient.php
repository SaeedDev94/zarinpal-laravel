<?php

namespace Zarinpal\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class GuzzleClient implements IClient
{
    public function __construct($sandbox)
    {
        $sub = ($sandbox) ? 'sandbox' : 'api';
        $this->baseUrl = 'https://' . $sub . '.zarinpal.com/pg/v4/payment/';
    }

    public $baseUrl;

    use BaseClient;

    /**
     * Send requests to zarinpal
     * and receive responses.
     *
     * @param string $method
     * @param array $payload
     *
     * @throws GuzzleException
     * @return array
     */
    public function sendRequest(string $method, array $payload)
    {
        $client = new Client([
            'base_uri' => $this->baseUrl
        ]);
        $response = $client->request('POST', "${method}.json", [
            'json' => $payload
        ]);
        $response = $response->getBody()->getContents();
        $response = json_decode($response, true);
        return $response;
    }
}
