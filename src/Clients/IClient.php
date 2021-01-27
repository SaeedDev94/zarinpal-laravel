<?php

namespace Zarinpal\Clients;

use GuzzleHttp\Exception\RequestException;

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
     * @throws RequestException
     * @return array
     */
    function sendRequest(string $method, array $payload, array $headers = []);
}
