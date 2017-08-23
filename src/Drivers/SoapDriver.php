<?php

namespace Zarinpal\Drivers;

use Exception;
use SoapClient;

class SoapDriver implements DriverInterface
{
    public $baseUrl;

    public function __construct($sandbox)
    {
        $sub = ($sandbox) ? 'sandbox' : 'www';
        $this->baseUrl = 'https://'.$sub.'.zarinpal.com/pg/services/WebGate/wsdl';
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
            $response = $this->client()->PaymentRequest($input);
            $response = (array) $response;
        } catch (Exception $error) {
            /**
             * Status -301 means request method of
             * Zarinpal\Drivers\SoapDriver class
             * had no response
             */
            $response = ['Status' => -301, 'Authority' => ''];
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
            $response = $this->client()->PaymentVerification($input);
            $response = (array) $response;
        } catch (Exception $error) {
            /**
             * Status -302 means verify method of
             * Zarinpal\Drivers\SoapDriver class
             * had no response
             */
            $response = ['Status' => -302, 'RefID' => 0];
        }

        return $response;
    }

    /**
     * Generate client object for driver.
     *
     * @return SoapClient $client
     */
    public function client()
    {
        $client = new SoapClient($this->baseUrl, ['encoding' => 'UTF-8']);

        return $client;
    }
}
