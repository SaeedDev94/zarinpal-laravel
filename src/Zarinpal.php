<?php

namespace Zarinpal;

use Zarinpal\Messages\Message;

class Zarinpal
{
    public $merchantID;
    public $client;
    public $lang;
    public $sandbox;
    public $response;

    public function __construct($merchantID, $client, $lang, $sandbox)
    {
        $this->merchantID = $merchantID;
        $this->client = $client;
        $this->lang = $lang;
        $this->sandbox = $sandbox;
        $this->response = [];
    }

    /**
     * Request for new payment
     * to get "Authority" if no error occur.
     *
     * @param  array $input
     * @param  bool  $extra
     *
     * @return array
     */
    public function request($input, $extra = false)
    {
        $payment = [
            'MerchantID'  => $this->merchantID,
            'CallbackURL' => (string) $input['CallbackURL'],
            'Amount'      => (int) $input['Amount'],
            'Description' => (string) $input['Description'],
        ];
        if (isset($input['Email'])) {
            $payment['Email'] = (string) $input['Email'];
        }
        if (isset($input['Mobile'])) {
            $payment['Mobile'] = (string) $input['Mobile'];
        }
        if ($extra) {
            $payment['AdditionalData'] = (string) $input['AdditionalData'];
        }
        $this->response = $this->client->request($payment, $extra);
        $this->setMessage();

        return $this->response;
    }

    /**
     * Verify payment success.
     *
     * @param  array $input
     * @param  bool  $extra
     *
     * @return array
     */
    public function verify($input, $extra = false)
    {
        if ($input['Status'] === 'OK') {
            $payment = [
                'MerchantID' => $this->merchantID,
                'Authority'  => (string) $input['Authority'],
                'Amount'     => (int) $input['Amount'],
            ];
            $this->response = $this->client->verify($payment, $extra);
        } else {
            $this->response = ['Status' => -101];
        }
        $this->setMessage();

        return $this->response;
    }

    /**
     * Request for new payment with extra data.
     *
     * @param  array $input
     *
     * @return array
     */
    public function requestWithExtra($input)
    {
        return $this->request($input, true);
    }

    /**
     * Verify payment success with extra data.
     *
     * @param  array $input
     *
     * @return array
     */
    public function verifyWithExtra($input)
    {
        return $this->verify($input, true);
    }

    /**
     * Refresh authority token.
     *
     * @param  array $input
     *
     * @return array
     */
    public function refreshAuthority($input)
    {
        $detail = [
            'MerchantID'  => $this->merchantID,
            'Authority'   => (string) $input['Authority'],
            'ExpireIn'    => (int) $input['ExpireIn'],
        ];
        $this->response = $this->client->refreshAuthority($detail);
        $this->setMessage();

        return $this->response;
    }

    /**
     * Get unverified transactions.
     *
     * @return array
     */
    public function unverifiedTransactions()
    {
        $detail = [
            'MerchantID'  => $this->merchantID,
        ];
        $this->response = $this->client->unverifiedTransactions($detail);
        $this->setMessage();

        return $this->response;
    }

    /**
     * Set message of status
     * 
     * @return void
     */
    public function setMessage() {
        $lang = $this->lang;
        $status = (string) $this->response['Status'];
        $message = Message::get($lang, $status);
        $this->response['Message'] = $message;
    }

    /**
     * Redirect to payment page.
     *
     * @param  string $authority
     *
     * @return void
     */
    public function redirect($authority)
    {
        $sub = ($this->sandbox) ? 'sandbox' : 'www';
        $url = 'https://'.$sub.'.zarinpal.com/pg/StartPay/'.$authority;
        header('Location: '.$url);
        exit;
    }
}
