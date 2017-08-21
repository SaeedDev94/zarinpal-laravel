<?php

namespace Zarinpal\Drivers;

interface DriverInterface
{
    /**
     * Request driver.
     *
     * @param array $input
     *
     * @return array
     */
    public function request($input);

    /**
     * Verify driver.
     *
     * @param array $input
     *
     * @return array
     */
    public function verify($input);

    /**
     * Generate client object for driver.
     *
     * @return client object
     */
    public function client();
}
