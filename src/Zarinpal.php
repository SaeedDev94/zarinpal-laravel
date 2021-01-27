<?php

namespace Zarinpal;

use GuzzleHttp\Exception\RequestException;
use Zarinpal\Messages\Message;
use Zarinpal\Clients\IClient;

class Zarinpal
{
    /**
     * Zarinpal constructor.
     * @param string $merchantID
     * @param IClient $client
     * @param string $lang
     * @param bool $sandbox
     * @param bool $zarinGate
     * @param string $zarinGatePSP
     * @param bool $laravel
     */
    function __construct(
        string $merchantID,
        IClient $client,
        string $lang,
        bool $sandbox,
        bool $zarinGate,
        string $zarinGatePSP = '',
        bool $laravel = false
    ) {
        $this->merchantID = $merchantID;
        $this->client = $client;
        $this->lang = $lang;
        $this->sandbox = $sandbox;
        $this->zarinGate = $zarinGate;
        $this->zarinGatePSP = $zarinGatePSP;
        $this->laravel = $laravel;
        $this->zarinGatePSPList = ['Asan', 'Sep', 'Sad', 'Pec', 'Fan', 'Emz'];
    }

    public string $merchantID;
    public IClient $client;
    public string $lang;
    public bool $sandbox;
    public bool $zarinGate;
    public array $zarinGatePSPList;
    public string $zarinGatePSP;
    public bool $laravel;

    /**
     * Request for new payment
     * to get "Authority" if no error occur.
     *
     * @see http://bit.ly/3sVkMU9
     *
     * @param array $payload
     *
     * @throws RequestException
     * @return array
     */
    function request(array $payload)
    {
        return $this->client->sendRequest('request', array_merge([
            'merchant_id' => $this->merchantID
        ], $payload));
    }

    /**
     * Verify payment success.
     *
     * @see http://bit.ly/3a75K54
     *
     * @param array $payload
     *
     * @throws RequestException
     * @return array
     */
    function verify(array $payload)
    {
        return $this->client->sendRequest('verify', array_merge([
            'merchant_id' => $this->merchantID
        ], $payload));
    }

    /**
     * Get unverified transactions.
     *
     * @see http://bit.ly/3qP3MNB
     *
     * @throws RequestException
     * @return array
     */
    function unVerified()
    {
        return $this->client->sendRequest('unVerified', [
            'merchant_id' => $this->merchantID
        ]);
    }

    /**
     * Refund to user.
     *
     * @see http://bit.ly/3qNEkb2
     *
     * @param string $accessToken
     * @param array $payload
     *
     * @throws RequestException
     * @return array
     */
    function refund(string $accessToken, array $payload)
    {
        return $this->client->sendRequest('unVerified', array_merge([
            'merchant_id' => $this->merchantID
        ], $payload), [
            'authorization' => "Bearer ${accessToken}"
        ]);
    }

    /**
     * Get message of (status) code
     *
     * @see http://bit.ly/2M5Ltoz
     *
     * @param int $code
     *
     * @return string
     */
    function getCodeMessage(int $code)
    {
        return Message::get($this->lang, $code);
    }

    /**
     * Get generated redirect url
     *
     * @see http://bit.ly/2MsIOF7
     *
     * @param string $authority
     *
     * @return string
     */
    function getRedirectUrl(string $authority)
    {
        $subDomain = ($this->sandbox) ? 'sandbox' : 'www';
        $zarinGateURL = ($this->zarinGate) ? '/ZarinGate' : '';

        if(
            $this->zarinGate &&
            trim($this->zarinGatePSP) !== '' &&
            in_array($this->zarinGatePSP, $this->zarinGatePSPList)
        ) {
            $zarinGateURL = '/' . $this->zarinGatePSP;
        }

        return 'https://' . $subDomain . '.zarinpal.com/pg/StartPay/' . $authority . $zarinGateURL;
    }

    /**
     * Redirect to payment page.
     *
     * @param string $authority
     *
     * @return mixed
     */
    function redirect(string $authority)
    {
        $url = $this->getRedirectUrl($authority);
        if ($this->laravel) {
            return redirect($url);
        }
        header('Location: ' . $url);
        exit;
    }
}
