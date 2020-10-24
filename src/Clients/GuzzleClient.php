<?php

namespace Zarinpal\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

class GuzzleClient implements IBaseClient
{
    public function __construct($sandbox)
    {
        $sub = ($sandbox) ? 'sandbox' : 'www';
        $this->baseUrl = 'https://' . $sub . '.zarinpal.com/pg/rest/WebGate/';
    }

    public $baseUrl;

    use BaseClient;

    /**
     * Send requests to zarinpal
     * and receive responses.
     *
     * @param string $uri
     * @param array $input
     *
     * @return array
     * @throws RequestException|GuzzleException
     */
    public function sendRequest(string $uri, array $input)
    {
        $uri .= '.json';
        try {
            $client = new Client(['base_uri' => $this->baseUrl]);
            $response = $client->request('POST', $uri, ['json' => $input]);
            $response = $response->getBody()->getContents();
        } catch (RequestException $request) {
            $response = '{"Status":-202}';
            if ($request->hasResponse()) {
                $response = $request->getResponse();
                $response = $response->getBody()->getContents();
            }
        }
        $response = json_decode($response, true);

        return $response;
    }
}
