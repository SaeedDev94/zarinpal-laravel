# Zarinpal payment for Laravel Framework

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

# Use it

request new payment:

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
        return $zarinpal->redirect($response['Authority']);
    }
    return 'Error,
    Status: '.$response['Status'].',
    Message: '.$response['Message'];
}
...
```

verify the payment:

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

# Use this lib with other frameworks

```php
<?php

...
use Zarinpal\Zarinpal;
use Zarinpal\Drivers\RestDriver; // OR SoapDriver
...

...
$merchantID = 'XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX';
$sandbox = false; // OR true
$driver = new RestDriver($sandbox);
$lang = 'fa'; // OR en
$zarinpal = new Zarinpal($merchantID, $driver, $lang, $sandbox);
// object is ready, call methods now!
...
```

# Other available methods

`requestWithExtra`
```php
...
// same as request method,
// but this needs AdditionalData in payment array
$payment = [
    ...
    'AdditionalData' => json_encode($extraData), // Required
    ...
];
$response = $zarinpal->requestWithExtra($payment);
...
```

`verifyWithExtra`
```php
...
// excatly same as verify method
$response = $zarinpal->verifyWithExtra($payment);
...

```

# Other available configs

* ZARINPAL_LANG:
    * change message language
    * possible values: [fa, en]
* ZARINPAL_SANDBOX:
    * use sandbox service for testing payment
    * possible values: [0, 1]
* ZARINPAL_DRIVER:
    * client to send requests and receive responses
    * possible values: [Rest, Soap]
