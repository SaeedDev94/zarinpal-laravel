<?php

namespace Zarinpal\Drivers;

interface DriverInterface
{
    /**
     * Request driver.
     *
     * @param array $input
     * @param bool  $debug
     *
     * @return array
     */
    public function request($input, $debug);

    /**
     * Verify driver.
     *
     * @param array $input
     * @param bool  $debug
     *
     * @return array
     */
    public function verify($input, $debug);

    /**
     * Generate proper URL for driver.
     *
     * @param bool $debug
     *
     * @return string
     */
    public function mkurl($debug);
}
