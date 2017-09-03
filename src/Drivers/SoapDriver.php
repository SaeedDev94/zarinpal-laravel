<?php

namespace Zarinpal\Drivers;

use SoapClient;
use SoapFault;

class SoapDriver
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
     * @param bool  $extra
     *
     * @return array
     */
    public function request($input, $extra)
    {
        $uri = ($extra) ? 'PaymentRequestWithExtra' : 'PaymentRequest';

        return $this->sendRequest($uri, $input);
    }

    /**
     * Verify driver.
     *
     * @param array $input
     * @param bool  $extra
     *
     * @return array
     */
    public function verify($input, $extra)
    {
        $uri = ($extra) ? 'PaymentVerificationWithExtra' : 'PaymentVerification';

        return $this->sendRequest($uri, $input);
    }

    /**
     * Refresh authority driver.
     *
     * @param array $input
     *
     * @return array
     */
    public function refreshAuthority($input)
    {
        return $this->sendRequest('RefreshAuthority', $input);
    }

    /**
     * Unverified transactions driver.
     *
     * @param array $input
     *
     * @return array
     */
    public function unverifiedTransactions($input)
    {
        return $this->sendRequest('UnverifiedTransactions', $input);
    }

    /**
     * Send requests to zarinpal.
     *
     * @param string $uri
     * @param array  $input
     *
     * @return array
     */
    public function sendRequest($uri, $input)
    {
        try {
            $client = new SoapClient($this->baseUrl, ['encoding' => 'UTF-8']);
            $response = $client->{$uri}($input);
            $response = (array) $response;
        } catch (SoapFault $error) {
            $response = ['Status' => -303];
        }

        return $response;
    }
}
