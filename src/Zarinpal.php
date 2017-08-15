<?php

namespace Zarinpal;

use Zarinpal\Drivers\DriverInterface;
use Session;

class Zarinpal
{
    private $merchantID;
    private $driver;
    private $authority;
    private $amount;
    private $debug;

    public function __construct($merchantID, DriverInterface $driver, $debug = false)
    {
        $this->merchantID = $merchantID;
        $this->driver = $driver;
        $this->authority = null;
        $this->amount = 0;
        $this->debug = $debug;
    }

    /**
     * Request for new payment
     * to get "Authority" if no error occur
     *
     * @param string $callbackURL
     * @param int    $amount
     * @param string $description
     * @param string $email
     * @param string $mobile
     *
     * @return array
     */
    public function request($callbackURL, $amount, $description, $email = '', $mobile = '')
    {
        $this->amount = $amount;
        $input = [
            'MerchantID'  => $this->merchantID,
            'CallbackURL' => $callbackURL,
            'Amount'      => $amount,
            'Description' => $description,
        ];
        if (!empty($email)) {
            $input['Email'] = $email;
        }
        if (!empty($mobile)) {
            $input['Mobile'] = $mobile;
        }
        $response = $this->driver->request($input, $this->debug);
        if (isset($response['Authority'])) {
            $this->authority = $response['Authority'];
        }
        return $response;
    }

    /**
     * Redirect to payment page
     *
     * @return redirect
     */
    public function redirect()
    {
        Session::put('authority', $this->authority);
        Session::put('amount', $this->amount);
        $sub = ($this->debug)? 'sandbox':'www';
        $url = 'https://'.$sub.'.zarinpal.com/pg/StartPay/'.$this->authority;
        return redirect($url);
    }

    /**
     * Verify payment success
     *
     * @return array
     */
    public function verify()
    {
        if(Session::has('authority') && Session::has('amount')) {
            $authority = Session::get('authority');
            $amount = Session::get('amount');
            $input = [
                'MerchantID' => $this->merchantID,
                'Authority'  => $authority,
                'Amount'     => $amount,
            ];
            return $this->driver->verify($input, $this->debug);
        }
        return ['Success' => false];
    }
}
