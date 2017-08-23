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
return 'Error! Status: '.$zarinpal->response['Status'];
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
    return 'Payment was successful: '.$zarinpal->response['RefID'];
}
return 'Error! Status: '.$zarinpal->response['Status'];
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

<b>NOTE:</b> make sure [SOAP](http://php.net/manual/en/book.soap.php) is installed<br>
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

# Custom status codes definition

there are a bunch of status codes for differents stats,
some of them are official and you can find their definitions
in [this repo](https://github.com/ZarinPal-Lab/Documentation-PaymentGateway), 
others are custom:

- `Zarinpal\Zarinpal::verify[-102]`: "Status" query string is not equal to "OK"
- `Zarinpal\Drivers\RestDriver::request[-201]`: method had no response
- ` Zarinpal\Drivers\RestDriver::verify[-202]`: method had no response
- `Zarinpal\Drivers\SoapDriver::request[-301]`: method had no response
- ` Zarinpal\Drivers\SoapDriver::verify[-302]`: method had no response
