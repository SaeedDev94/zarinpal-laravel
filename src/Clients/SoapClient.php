<?php

namespace Zarinpal\Clients;

use SoapClient as Client;
use SoapFault;

class SoapClient
{
    public $baseUrl;

    public function __construct($sandbox)
    {
        $sub = ($sandbox) ? 'sandbox' : 'www';
        $this->baseUrl = 'https://'.$sub.'.zarinpal.com/pg/services/WebGate/wsdl';
    }

    /**
     * Request new payment.
     *
     * @param  array $input
     * @param  bool  $extra
     *
     * @return array
     */
    public function request($input, $extra)
    {
        $uri = ($extra) ? 'PaymentRequestWithExtra' : 'PaymentRequest';

        return $this->sendRequest($uri, $input);
    }

    /**
     * Verify the payment.
     *
     * @param  array $input
     * @param  bool  $extra
     *
     * @return array
     */
    public function verify($input, $extra)
    {
        $uri = ($extra) ? 'PaymentVerificationWithExtra' : 'PaymentVerification';

        return $this->sendRequest($uri, $input);
    }

    /**
     * Extends authority token lifetime.
     *
     * @param  array $input
     *
     * @return array
     */
    public function refreshAuthority($input)
    {
        return $this->sendRequest('RefreshAuthority', $input);
    }

    /**
     * Get unverified transactions.
     *
     * @param  array $input
     *
     * @return array
     */
    public function unverifiedTransactions($input)
    {
        return $this->sendRequest('UnverifiedTransactions', $input);
    }

    /**
     * Send requests to zarinpal
     * and receive responses.
     *
     * @param  string $uri
     * @param  array  $input
     *
     * @return array
     */
    public function sendRequest($uri, $input)
    {
        try {
            $client = new Client($this->baseUrl, ['encoding' => 'UTF-8']);
            $response = $client->{$uri}($input);
            $response = (array) $response;
        } catch (SoapFault $error) {
            $response = ['Status' => -303];
        }

        return $response;
    }
}
