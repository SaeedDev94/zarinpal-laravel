<?php

namespace Zarinpal\Clients;

use GuzzleHttp\Exception\GuzzleException;

interface IClient
{
    /**
     * Send requests to zarinpal
     * and receive responses.
     *
     * @param string $method
     * @param array $payload
     * @param array $headers
     *
     * @throws GuzzleException
     * @return array
     */
    function sendRequest(string $method, array $payload, array $headers = []);
}
