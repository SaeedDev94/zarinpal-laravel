<?php

namespace Zarinpal\Drivers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class RestDriver implements DriverInterface
{
    /**
     * Request driver.
     *
     * @param array $input
     * @param bool  $debug
     *
     * @return array
     */
    public function request($input, $debug)
    {
        try {
            $client = new Client(['base_uri' => $this->mkurl($debug)]);
            $response = $client->request(
                'POST',
                'PaymentRequest.json',
                ['json' => $input]
            );
            $response = $response->getBody()->getContents();
            $response = json_decode($response);

            return ['Status' => (int) $response->Status, 'Authority' => (string) $response->Authority ?? ''];
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

            return ['Status' => (int) $response->Status, 'Authority' => (string) $response->Authority ?? ''];
        }
    }

    /**
     * Verify driver.
     *
     * @param array $input
     * @param bool  $debug
     *
     * @return array
     */
    public function verify($input, $debug)
    {
        try {
            $client = new Client(['base_uri' => $this->mkurl($debug)]);
            $response = $client->request(
                'POST',
                'PaymentVerification.json',
                ['json' => $input]
            );
            $response = $response->getBody()->getContents();
            $response = json_decode($response);

            return ['Status' => (int) $response->Status, 'RefID' => (int) $response->RefID ?? 0];
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

            return ['Status' => (int) $response->Status, 'RefID' => (int) $response->RefID ?? 0];
        }
    }

    /**
     * Generate proper URL for driver.
     *
     * @param bool $debug
     *
     * @return string
     */
    public function mkurl($debug)
    {
        $sub = ($debug) ? 'sandbox' : 'www';
        $url = 'https://'.$sub.'.zarinpal.com/pg/rest/WebGate/';

        return $url;
    }
}
