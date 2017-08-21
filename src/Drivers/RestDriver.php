<?php

namespace Zarinpal\Drivers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class RestDriver implements DriverInterface
{
    public $baseUrl;

    public function __construct($sandbox)
    {
        $sub = ($sandbox) ? 'sandbox' : 'www';
        $this->baseUrl = 'https://'.$sub.'.zarinpal.com/pg/rest/WebGate/';
    }

    /**
     * Request driver.
     *
     * @param array $input
     *
     * @return array
     */
    public function request($input)
    {
        try {
            $response = $this->client()->request(
                'POST',
                'PaymentRequest.json',
                ['json' => $input]
            );
            $response = $response->getBody()->getContents();
            $response = json_decode($response);
            $response = ['Status' => (int) $response->Status, 'Authority' => (string) $response->Authority ?? ''];
        } catch (RequestException $request) {
            /**
             * Status -201 means request method
             * of Zarinpal\Drivers\RestDriver class
             * had no response.
             */
            $response = '{"Status":-201,"Authority":""}';
            if ($request->hasResponse()) {
                $response = $request->getResponse();
                $response = $response->getBody()->getContents();
            }
            $response = json_decode($response);
            $response = ['Status' => (int) $response->Status, 'Authority' => (string) $response->Authority ?? ''];
        }

        return $response;
    }

    /**
     * Verify driver.
     *
     * @param array $input
     *
     * @return array
     */
    public function verify($input)
    {
        try {
            $response = $this->client()->request(
                'POST',
                'PaymentVerification.json',
                ['json' => $input]
            );
            $response = $response->getBody()->getContents();
            $response = json_decode($response);
            $response = ['Status' => (int) $response->Status, 'RefID' => (int) $response->RefID ?? 0];
        } catch (RequestException $request) {
            /**
             * Status -202 means verify method
             * of Zarinpal\Drivers\RestDriver class
             * had no response.
             */
            $response = '{"Status":-202,"RefID":0}';
            if ($request->hasResponse()) {
                $response = $request->getResponse();
                $response = $response->getBody()->getContents();
            }
            $response = json_decode($response);
            $response = ['Status' => (int) $response->Status, 'RefID' => (int) $response->RefID ?? 0];
        }

        return $response;
    }

    /**
     * Generate client object for driver.
     *
     * @return GuzzleHttp\Client $client
     */
    public function client()
    {
        $client = new Client(['base_uri' => $this->baseUrl]);

        return $client;
    }
}
