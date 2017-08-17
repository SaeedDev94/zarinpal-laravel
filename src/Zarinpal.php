<?php

namespace Zarinpal;

use Zarinpal\Drivers\DriverInterface;
use Session;

class Zarinpal
{
    private $merchantID;
    private $driver;
    private $debug;

    public function __construct($merchantID, DriverInterface $driver, $debug = false)
    {
        $this->merchantID = $merchantID;
        $this->driver = $driver;
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
        $input = [
            'MerchantID'  => $this->merchantID,
            'CallbackURL' => $callbackURL,
            'Amount'      => $amount,
            'Description' => $description
        ];
        if (!empty($email)) {
            $input['Email'] = $email;
        }
        if (!empty($mobile)) {
            $input['Mobile'] = $mobile;
        }
        $response = $this->driver->request($input, $this->debug);
        if (isset($response['Authority'])) {
            Session::put('zarinpal.meta', [
                'authority' => $response['Authority'],
                'amount' => $amount
            ]);
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
        if(!Session::has('zarinpal.meta')) {
            return redirect()->back()->withInput()
            ->withErrors([
                'zarinpal.error' => 'Payment can\'t start because meta data missed!'
            ]);
        }
        $meta = Session::get('zarinpal.meta');
        $sub = ($this->debug)? 'sandbox':'www';
        $url = 'https://'.$sub.'.zarinpal.com/pg/StartPay/'.$meta['authority'];
        return redirect($url);
    }

    /**
     * Verify payment success
     *
     * @return array
     */
    public function verify()
    {
        if(Session::has('zarinpal.meta')) {
            $meta = Session::pull('zarinpal.meta');
            $input = [
                'MerchantID' => $this->merchantID,
                'Authority'  => $meta['authority'],
                'Amount'     => $meta['amount']
            ];
            return $this->driver->verify($input, $this->debug);
        }
        return ['Success' => false];
    }
}
