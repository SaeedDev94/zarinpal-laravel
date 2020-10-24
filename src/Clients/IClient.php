<?php

namespace Zarinpal\Clients;

interface IClient
{
    /**
     * Request new payment.
     *
     * @param array $input
     * @param bool $extra
     *
     * @return array
     */
    public function request(array $input, bool $extra);

    /**
     * Verify the payment.
     *
     * @param array $input
     * @param bool $extra
     *
     * @return array
     */
    public function verify(array $input, bool $extra);

    /**
     * Extends authority token lifetime.
     *
     * @param array $input
     *
     * @return array
     */
    public function refreshAuthority(array $input);

    /**
     * Get unverified transactions.
     *
     * @param array $input
     *
     * @return array
     */
    public function unverifiedTransactions(array $input);

    /**
     * Send requests to zarinpal
     * and receive responses.
     *
     * @param string $uri
     * @param array $input
     *
     * @return array
     */
    public function sendRequest(string $uri, array $input);
}
