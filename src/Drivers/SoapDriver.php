<?php

namespace Zarinpal\Drivers;

class SoapDriver implements DriverInterface
{
    private $debug;
    private $wsdlAddress;

    public function setWsdlAddress()
    {
        $url = ($this->debug) ? 'sandbox' : 'www';

        $this->wsdlAddress = 'https://'.$url.'.zarinpal.com/pg/services/WebGate/wsdl';

        return $this->wsdlAddress;
    }

    /**
     * request driver.
     *
     * @param $inputs
     *
     * @return array
     */
    public function request($inputs, $debug)
    {
        $this->debug = $debug;

        $client = new \SoapClient($this->setWsdlAddress(), ['encoding' => 'UTF-8']);
        $result = $client->PaymentRequest($inputs);
        if ($result->Status == 100) {
            return ['Authority' => $result->Authority];
        } else {
            return ['error' => $result->Status];
        }
    }

    /**
     * request driver.
     *
     * @param $inputs
     *
     * @return array
     */
    public function requestWithExtra($inputs)
    {
        $client = new \SoapClient($this->setWsdlAddress(), ['encoding' => 'UTF-8']);
        $result = $client->PaymentRequestWithExtra($inputs);
        if ($result->Status == 100) {
            return ['Authority' => $result->Authority];
        } else {
            return ['error' => $result->Status];
        }
    }

    /**
     * verify driver.
     *
     * @param $inputs
     *
     * @return array
     */
    public function verify($inputs, $debug)
    {
        $this->debug = $debug;
        $client = new \SoapClient($this->setWsdlAddress(), ['encoding' => 'UTF-8']);
        $result = $client->PaymentVerification($inputs);
        if ($result->Status == 100) {
            return ['Success' => true, 'Authority' => $_GET['Authority'], 'RefID' => $result->RefID];
        } else {
            return ['Success' => false, 'Authority' => $_GET['Authority']];
        }
    }

    /**
     * verify driver.
     *
     * @param $inputs
     *
     * @return array
     */
    public function verifyWithExtra($inputs)
    {
        $client = new \SoapClient($this->wsdlAddress, ['encoding' => 'UTF-8']);
        $result = $client->PaymentVerificationWithExtra($inputs);

        if ($result->Status == 100) {
            return ['Status' => 'success', 'RefID' => $result->RefID];
        } else {
            return ['Status' => 'error', 'error' => $result->Status];
        }
    }

    /**
     * @param mixed $wsdlAddress
     *
     * @return void
     */
    public function setAddress($wsdlAddress)
    {
        $this->wsdlAddress = $wsdlAddress;
    }
}
