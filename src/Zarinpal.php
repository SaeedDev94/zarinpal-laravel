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
     * @param  array $input
     *
     * @return array
     */
    public function request($input)
    {
        $payment = [
            'MerchantID'  => $this->merchantID,
            'CallbackURL' => $input['CallbackURL'],
            'Amount'      => $input['Amount'],
            'Description' => $input['Description']
        ];
        if (isset($input['Email'])) {
            $payment['Email'] = $input['Email'];
        }
        if (isset($input['Mobile'])) {
            $payment['Mobile'] = $input['Mobile'];
        }
        $response = $this->driver->request($payment, $this->debug);
        if (isset($response['Authority'])) {
            Session::put('zarinpal.meta', [
                'authority' => $response['Authority'],
                'amount' => $input['Amount']
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
            $payment = [
                'MerchantID' => $this->merchantID,
                'Authority'  => $meta['authority'],
                'Amount'     => $meta['amount']
            ];
            return $this->driver->verify($payment, $this->debug);
        }
        return ['Success' => false];
    }
}
