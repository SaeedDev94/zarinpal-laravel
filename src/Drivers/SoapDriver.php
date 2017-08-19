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
     * @param  array $input
     * @param  bool  $debug
     *
     * @return array
     */
    public function request($input, $debug)
    {
        $this->debug = $debug;
        try {
            $client = new SoapClient($this->mkurl(), ['encoding' => 'UTF-8']);
            $response = $client->PaymentRequest($input);
            return ['Status' => (int) $response->Status, 'Authority' => (string) $response->Authority ?? ''];
        }
        catch (Exception $e) {
            return ['Status' => -99, 'Authority' => ''];
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
            $client = new SoapClient($this->mkurl(), ['encoding' => 'UTF-8']);
            $response = $client->PaymentVerification($input);
            return ['Status' => (int) $response->Status, 'RefID' => (int) $response->RefID ?? 0];
        }
        catch (Exception $e) {
            return ['Status' => -99, 'RefID' => 0];
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
