<?php

namespace Zarinpal;

class Zarinpal
{
    public $merchantID;
    public $driver;
    public $sandbox;
    public $response;

    public function __construct($merchantID, $driver, $sandbox)
    {
        $this->merchantID = $merchantID;
        $this->driver = $driver;
        $this->sandbox = $sandbox;
        $this->response = [];
    }

    /**
     * Request for new payment
     * to get "Authority" if no error occur.
     *
     * @param array $input
     * @param bool  $extra
     *
     * @return Zarinpal\Zarinpal
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
        if($extra) {
            $payment['AdditionalData'] = (string) $input['AdditionalData'];
        }
        $this->response = $this->driver->request($payment, $extra);

        return $this;
    }

    /**
     * Verify payment success.
     *
     * @param array $input
     * @param bool  $extra
     *
     * @return Zarinpal\Zarinpal
     */
    public function verify($input, $extra = false)
    {
        if ($input['Status'] === 'OK') {
            $payment = [
                'MerchantID' => $this->merchantID,
                'Authority'  => (string) $input['Authority'],
                'Amount'     => (int) $input['Amount'],
            ];
            $this->response = $this->driver->verify($payment, $extra);
        } else {
            $this->response = ['Status' => -101];
        }

        return $this;
    }

    /**
     * Request for new payment with extra data.
     *
     * @param array $input
     *
     * @return Zarinpal\Zarinpal
     */
    public function requestWithExtra($input)
    {
        return $this->request($input, true);
    }

    /**
     * Verify payment success with extra data.
     *
     * @param array $input
     *
     * @return Zarinpal\Zarinpal
     */
    public function verifyWithExtra($input)
    {
        return $this->verify($input, true);
    }

    /**
     * Refresh authority token.
     *
     * @param array $input
     *
     * @return Zarinpal\Zarinpal
     */
    public function refreshAuthority($input)
    {
        $detail = [
            'MerchantID'  => $this->merchantID,
            'Authority'   => (string) $input['Authority'],
            'ExpireIn'    => (int) $input['ExpireIn'],
        ];
        $this->response = $this->driver->refreshAuthority($detail);

        return $this;
    }

    /**
     * Get unverified transactions.
     *
     * @param array $input
     *
     * @return Zarinpal\Zarinpal
     */
    public function unverifiedTransactions($input)
    {
        $detail = [
            'MerchantID'  => $this->merchantID,
        ];
        $this->response = $this->driver->unverifiedTransactions($detail);

        return $this;
    }

    /**
     * Redirect to payment page.
     *
     * @param string $authority
     *
     * @return redirect
     */
    public function redirect($authority)
    {
        $sub = ($this->sandbox) ? 'sandbox' : 'www';
        $url = 'https://'.$sub.'.zarinpal.com/pg/StartPay/'.$authority;

        return redirect($url);
    }
}
