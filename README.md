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
if(isset($zarinpal->response['Authority'])) {
    return $zarinpal->redirect();
}
return 'Error!';
...
```

verify the payment:

```php
<?php

...
use Zarinpal\Facade\Zarinpal;
...

...
$zarinpal = Zarinpal::verify();
if($zarinpal->response['Success']) {
    return 'Payment was successful.';
}
return 'Payment was not successful!';
...
```
# SandBox is for developers

<b>NOTE:</b> make sure [SOAP](http://php.net/manual/en/book.soap.php) is installed<br>
if you want test zarinpal payment then SandBox is for you,
set `ZARINPAL_DEBUG` to `1` in `.env` file:

```
...
ZARINPAL_DEBUG=1
...
```

to disable it:

```
...
ZARINPAL_DEBUG=0
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

back to rest:

```
...
ZARINPAL_DRIVER=Rest
...
```
