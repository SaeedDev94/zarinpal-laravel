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
     * @param array $input
     * @param bool  $debug
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
            if ($response->Status == 100) {
                return ['Authority' => $response->Authority];
            }
            return ['Error' => $response->Status];
        }
        catch(RequestException $e) {
            return ['Error' => -99];
        }
    }

    /**
     * Verify driver
     *
     * @param array $input
     * @param bool  $debug
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
            if ($response->Status == 100) {
                return ['Success' => true, 'RefID' => $response->RefID];
            }
            return ['Success' => false];
        }
        catch(RequestException $e) {
            return ['Success' => false];
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
