<?php

namespace Zarinpal\Drivers;

interface DriverInterface
{
    public function setWsdlAddress();

    /**
     * @param $inputs
     *
     * @return array|redirect
     */
    public function request($inputs, $debug);

    /**
     * @param $inputs
     *
     * @return array|redirect
     */
    public function requestWithExtra($inputs);

    /**
     * @param $inputs
     *
     * @return array
     */
    public function verify($inputs, $debug);

    /**
     * @param $inputs
     *
     * @return array
     */
    public function verifyWithExtra($inputs);

    /**
     * @param $inputs
     *
     * @return array
     */
    public function setAddress($wsdlAddress);
}
