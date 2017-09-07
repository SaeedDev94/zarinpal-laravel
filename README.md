# **Zarinpal payment for Laravel Framework**

[JUMP TO: Use this lib with other frameworks](#use-this-lib-with-other-frameworks)

install it:

```
composer require saeedpooyanfar/zarinpal
```

add it to providers in "config/app.php" file:

```php
<?php

...
'providers' => [
    ...
    Zarinpal\ZarinpalServiceProvider::class,
    ...
],
...
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
use Zarinpal\Zarinpal;
...

...
public function request(Zarinpal $zarinpal) {
    $payment = [
        'CallbackURL' => route('payment.verify'), // Required
        'Amount'      => 5000,                    // Required
        'Description' => 'a short description',   // Required
        'Email'       => 'saeedp47@gmail.com',    // Optional
        'Mobile'      => '0933xxx7694'            // Optional
    ];
    $response = $zarinpal->request($payment);
    if($response['Status'] === 100) {
        $authority = $response['Authority'];
        return $zarinpal->redirect($authority);
    }
    return 'Error,
    Status: '.$response['Status'].',
    Message: '.$response['Message'];
}
...
```

**verify the payment:**

```php
<?php

...
use Illuminate\Support\Facades\Input;
use Zarinpal\Zarinpal;
...

...
public function verify(Zarinpal $zarinpal) {
    $payment = [
        'Authority' => Input::get('Authority'), // $_GET['Authority']
        'Status'    => Input::get('Status'),    // $_GET['Status']
        'Amount'    => 5000
    ];
    $response = $zarinpal->verify($payment);
    if($response['Status'] === 100) {
        return 'Payment was successful,
        RefID: '.$response['RefID'].',
        Message: '.$response['Message'];
    }
    return 'Error,
    Status: '.$response['Status'].',
    Message: '.$response['Message'];
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
$sandbox = false; // OR true
$client = new GuzzleClient($sandbox);
$lang = 'fa'; // OR en
$zarinpal = new Zarinpal($merchantID, $client, $lang, $sandbox);
// object is ready, call methods now!
...
```

# **Other available methods**

1- `requestWithExtra`:
dividing money in different zarinpal wallets
```php
...
// same as request method,
// but this needs AdditionalData in payment array
$amount = 10000;
$divider = [];
// 8000 in zp.1.1 wallet and
// 2000 in zp.2.5 wallet
$divider['Wages'] = [
    'zp.1.1' => [
        'Amount'      => $amount * (80/100), // 80%
        'Description' => 'a short description'
    ],
    'zp.2.5' => [
        'Amount'      => $amount * (20/100), // 20%
        'Description' => 'a short description'
    ]
];
$payment = [
    ...
    'Amount'         => $amount,               // Required
    'AdditionalData' => json_encode($divider), // Required
    ...
];
$response = $zarinpal->requestWithExtra($payment);
...
```

2- `verifyWithExtra`:
if you used requestWithExtra method for payment then you must verify it with this one
```php
...
// exactly same as verify method
$response = $zarinpal->verifyWithExtra($payment);
...

```

3- `refreshAuthority`:
extends authority token lifetime
```php
...
$detail = [
    'Authority' => $authority, // Required
    'ExpireIn'  => 7200        // Required (in secodns)
];
$response = $zarinpal->refreshAuthority($detail);
...
```

4- `unverifiedTransactions`:
get successful payments which you didn't call verify method on them
```php
...
$response = $zarinpal->unverifiedTransactions();
$payments = json_decode($response['Authorities']);
foreach($payments as $payment) {
    $authority = $payment->Authority;
    $amount = $payment->Amount;
    $channel = $payment->Channel;
    $date = $payment->Date;
    ...
}
...
```

# **Other available configs**

* ZARINPAL_LANG:
    * messages language
    * possible values: [fa, en]
* ZARINPAL_SANDBOX:
    * use sandbox service for testing payment
    * possible values: [0, 1]
* ZARINPAL_CLIENT:
    * client to send requests and receive responses
    * possible values: [Guzzle, Soap]

# **Final note**

This lib and its methods written based on
[official zarinpal documents](https://github.com/ZarinPal-Lab/Documentation-PaymentGateway),
so reading the docs might be helpful
