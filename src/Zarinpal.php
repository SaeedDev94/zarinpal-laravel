<?php

namespace Zarinpal;

use Zarinpal\Drivers\DriverInterface;

class Zarinpal
{
    public $merchantID;
    public $driver;
    public $sandbox;
    public $response;

    public function __construct($merchantID, DriverInterface $driver, $sandbox)
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
     *
     * @return Zarinpal\Zarinpal
     */
    public function request($input)
    {
        $payment = [
            'MerchantID'  => $this->merchantID,
            'CallbackURL' => $input['CallbackURL'],
            'Amount'      => $input['Amount'],
            'Description' => $input['Description'],
        ];
        if (isset($input['Email'])) {
            $payment['Email'] = $input['Email'];
        }
        if (isset($input['Mobile'])) {
            $payment['Mobile'] = $input['Mobile'];
        }
        $this->response = $this->driver->request($payment);

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

    /**
     * Verify payment success.
     *
     * @param array $input
     *
     * @return Zarinpal\Zarinpal
     */
    public function verify($input)
    {
        if ($input['Status'] === 'OK') {
            $payment = [
                'MerchantID' => $this->merchantID,
                'Authority'  => $input['Authority'],
                'Amount'     => $input['Amount'],
            ];
            $this->response = $this->driver->verify($payment);
        } else {
            /**
             * Status -102 means "Status" query string
             * is not equal to "OK" in verify method
             * Zarinpal\Zarinpal class
             */
            $this->response = ['Status' => -102, 'RefID' => 0];
        }

        return $this;
    }
}
