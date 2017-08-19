<?php

namespace Zarinpal\Drivers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class RestDriver implements DriverInterface
{
    private $debug;

    /**
     * Request driver
     *
     * @param  array $input
     * @param  bool  $debug
     *
     * @return array
     */
    public function request($input, $debug)
    {
        $this->debug = $debug;
        try {
            $client = new Client(['base_uri' => $this->mkurl()]);
            $response = $client->request(
                'POST',
                'PaymentRequest.json',
                ['json' => $input]
            );
            $response = $response->getBody()->getContents();
            $response = json_decode($response);
            return ['Status' => (int) $response->Status, 'Authority' => (string) $response->Authority ?? ''];
        }
        catch (RequestException $e) {
            $response = $e->getResponse();
            $response = is_null($response)? '{"Status":-99,"Authority":""}':$response->getBody()->getContents();
            $response = json_decode($response);
            return ['Status' => (int) $response->Status, 'Authority' => (string) $response->Authority ?? ''];
        }
    }

    /**
     * Verify driver
     *
     * @param  array $input
     * @param  bool  $debug
     *
     * @return array
     */
    public function verify($input, $debug)
    {
        $this->debug = $debug;
        try {
            $client = new Client(['base_uri' => $this->mkurl()]);
            $response = $client->request(
                'POST',
                'PaymentVerification.json',
                ['json' => $input]
            );
            $response = $response->getBody()->getContents();
            $response = json_decode($response);
            return ['Status' => (int) $response->Status, 'RefID' => (int) $response->RefID ?? 0];
        }
        catch (RequestException $e) {
            $response = $e->getResponse();
            $response = is_null($response)? '{"Status":-99,"RefID":0}':$response->getBody()->getContents();
            $response = json_decode($response);
            return ['Status' => (int) $response->Status, 'RefID' => (int) $response->RefID ?? 0];
        }
    }

    /**
     * Generate proper URL for driver
     *
     * @return string
     */
    public function mkurl()
    {
        $sub = ($this->debug)? 'sandbox':'www';
        $url = 'https://'.$sub.'.zarinpal.com/pg/rest/WebGate/';
        return $url;
    }
}
