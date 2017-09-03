# Zarinpal payment for Laravel Framework

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

publish the config file:

```
php artisan vendor:publish --provider="Zarinpal\ZarinpalServiceProvider"
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
use Zarinpal\Facade\Zarinpal;
...

...
$payment = [
    'CallbackURL' => route('payment.verify'), // Required
    'Amount'      => 5000,                    // Required
    'Description' => 'a short description',   // Required
    'Email'       => 'saeedp47@gmail.com',    // Optional
    'Mobile'      => '0933xxx7694'            // Optional
];
$zarinpal = Zarinpal::request($payment);
if($zarinpal->response['Status'] === 100) {
    $authority = $zarinpal->response['Authority'];
    return $zarinpal->redirect($authority);
}
return 'Error! Status: '.$zarinpal->response['Status'].
', Message: '.$zarinpal->response['Message'];
...
```

verify the payment:

```php
<?php

...
use Illuminate\Support\Facades\Input;
use Zarinpal\Facade\Zarinpal;
...

...
$payment = [
    'Authority' => Input::get('Authority'),
    'Status'    => Input::get('Status'),
    'Amount'    => 5000
];
$zarinpal = Zarinpal::verify($payment);
if($zarinpal->response['Status'] === 100) {
    return 'Payment was successful: '.$zarinpal->response['RefID'].
    ', Message: '.$zarinpal->response['Message'];
}
return 'Error! Status: '.$zarinpal->response['Status'].
', Message: '.$zarinpal->response['Message'];
...
```

# Change message language

default message language is persian but you can change it to english,
set `ZARINPAL_LANG` to `en` in `.env` file:

```
...
ZARINPAL_LANG=en
...
```

back to default:

```
...
ZARINPAL_LANG=fa
...
```

# SandBox is for developers

if you want test zarinpal payment then SandBox is for you,
set `ZARINPAL_SANDBOX` to `1` in `.env` file:

```
...
ZARINPAL_SANDBOX=1
...
```

to disable it:

```
...
ZARINPAL_SANDBOX=0
...
```

# SoapDriver

<b>NOTE:</b> make sure [SOAP](http://php.net/manual/en/book.soap.php) installed<br>
you can also use SoapDriver instead of RestDriver for real payments,
set `ZARINPAL_DRIVER` to `Soap` in `.env` file:

```
...
ZARINPAL_DRIVER=Soap
...
```

back to default:

```
...
ZARINPAL_DRIVER=Rest
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
