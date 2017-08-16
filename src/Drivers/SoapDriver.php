<?php

namespace Zarinpal\Drivers;

use SoapClient;
use Exception;

class SoapDriver implements DriverInterface
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
            $client = new SoapClient($this->mkurl(), ['encoding' => 'UTF-8']);
            $response = $client->PaymentRequest($input);
            if ($response->Status == 100) {
                return ['Authority' => $response->Authority];
            }
            return ['Error' => $response->Status];
        }
        catch (Exception $e) {
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
            $client = new SoapClient($this->mkurl(), ['encoding' => 'UTF-8']);
            $response = $client->PaymentVerification($input);
            if ($response->Status == 100) {
                return ['Success' => true, 'RefID' => $response->RefID];
            }
            return ['Success' => false];    
        }
        catch (Exception $e) {
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
        $url = 'https://'.$sub.'.zarinpal.com/pg/services/WebGate/wsdl';
        return $url;
    }
}
