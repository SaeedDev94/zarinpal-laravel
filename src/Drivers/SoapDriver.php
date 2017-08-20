<?php

namespace Zarinpal\Drivers;

use Exception;
use SoapClient;

class SoapDriver implements DriverInterface
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
            $client = new SoapClient($this->mkurl($debug), ['encoding' => 'UTF-8']);
            $response = $client->PaymentRequest($input);

            return ['Status' => (int) $response->Status, 'Authority' => (string) $response->Authority ?? ''];
        } catch (Exception $e) {
            /*
             * Status -301 means request method
             * of Zarinpal\Drivers\SoapDriver class
             * had no response
             */
            return ['Status' => -301, 'Authority' => ''];
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
            $client = new SoapClient($this->mkurl($debug), ['encoding' => 'UTF-8']);
            $response = $client->PaymentVerification($input);

            return ['Status' => (int) $response->Status, 'RefID' => (int) $response->RefID ?? 0];
        } catch (Exception $e) {
            /*
             * Status -302 means verify method
             * of Zarinpal\Drivers\SoapDriver class
             * had no response
             */
            return ['Status' => -302, 'RefID' => 0];
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
        $url = 'https://'.$sub.'.zarinpal.com/pg/services/WebGate/wsdl';

        return $url;
    }
}
