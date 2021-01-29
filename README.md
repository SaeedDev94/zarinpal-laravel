# **Zarinpal payment for Laravel Framework**

- [Use this lib with other frameworks](#use-this-lib-with-other-frameworks)<br>
- [Available configs](#available-configs)<br>

install it:

```shell
composer require saeedpooyanfar/zarinpal
```

laravel service provider should register automatically, if not, register `Zarinpal\ZarinpalServiceProvider::class` manually or run:

```shell
composer dump-autoload
``` 

set 36 chars "ZARINPAL_MERCHANTID" in `.env` file:

```
...
ZARINPAL_MERCHANTID=XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX
...
```

# **Use it**

**request new payment:**

```php
<?php

...
use GuzzleHttp\Exception\RequestException;
use Zarinpal\Zarinpal;
...

...
function request(Zarinpal $zarinpal) {
    $payment = [
        'callback_url' => route('payment.verify'), // Required
        'amount'       => 5000,                    // Required
        'description'  => 'a short description',   // Required
        'metadata'     => [
            'mobile' => '0933xxx7694',       // Optional
            'email'  => 'saeedp47@gmail.com' // Optional
        ]
    ];
    try {
      $response = $zarinpal->request($payment);
      $code = $response['data']['code'];
      $message = $zarinpal->getCodeMessage($code);
      if($code === 100) {
          $authority = $response['data']['authority'];
          return $zarinpal->redirect($authority);
      }
      return 'Error,
      Code: ' . $code . ',
      Message: ' . $message;
    } catch (RequestException $exception) {
        // handle exception
    }
}
...
```

If you have other redirection methods you can use:

```php
...
$url = $zarinpal->getRedirectUrl($authority);
...
```

to get the redirect url as a string.


**verify the payment:**

```php
<?php

...
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Zarinpal\Zarinpal;
...

...
function verify(Request $request, Zarinpal $zarinpal) {
    $payment = [
        'authority' => $request->input('Authority'), // $_GET['Authority']
        'amount'    => 5000
    ];
    if ($request->input('Status') !== 'OK') abort(406);
    try {
      $response = $zarinpal->verify($payment);
      $code = $response['data']['code'];
      $message = $zarinpal->getCodeMessage($code);
      if($code === 100) {
          $refId = $response['data']['ref_id'];
          return 'Payment was successful,
          RefID: ' . $refId . ',
          Message: ' . $message;
      }
      return 'Error,
      Code: ' . $code . ',
      Message: ' . $message;
    } catch (RequestException $exception) {
        // handle exception
    }
}
...
```

# **Use this lib with other frameworks**

```php
<?php

...
use Zarinpal\Zarinpal;
use Zarinpal\Clients\GuzzleClient; // OR SoapClient
...

...
$merchantID = 'XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX';
$sandbox = false;
$zarinGate = false; // OR true
$zarinGatePSP = 'Asan'; // Leave this parameter blank if you don't need a custom PSP zaringate.
$client = new GuzzleClient($sandbox);
$lang = 'fa'; // OR en
$zarinpal = new Zarinpal($merchantID, $client, $lang, $sandbox, $zarinGate, $zarinGatePSP);
// object is ready, call methods now!
...
```

# **Available configs**

* ZARINPAL_LANG:
    * messages language
    * possible values: [fa, en]
* ZARINPAL_ZARINGATE:
    * use zarringate for redirect urls
    * possible values: [0, 1]
* ZARINPAL_ZARINGATE_PSP:
    * use custom PSP for zaringate 
    * possible values: 'Asan', 'Sep', 'Sad', 'Pec', 'Fan', 'Emz'
    
# **Run test**

```bash
# clone repo
# cd zarinpal-laravel
# composer install
cd test
php Request.php
```

# **Official documents**

[Link](https://next.zarinpal.com/paymentGateway/)
