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
    Zarinpal\Laravel\ZarinpalServiceProvider::class,
    ...
],
...
```

publish the config file:

```
php artisan vendor:publish --provider="Zarinpal\Laravel\ZarinpalServiceProvider"
```

set "MERCHANT_ID" in `.env` file:

```
...
MERCHANT_ID=XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX
...
```

# Use it

request new payment:

```php
<?php

...
use Zarinpal\Drivers\RestDriver;
use Zarinpal\Zarinpal;
...

...
$client = new Zarinpal(config('zarinpal.merchantID'), new RestDriver());
$callBackURL = url('/payment/verify');
$amount = 5000;
$description = 'a short description';
$response = $client->request($callBackURL, $amount, $description);
if(!isset($response['Authority'])) {
	return 'Error!';
}
return $client->redirect();
...
```

verify the payment:

```php
<?php

...
use Zarinpal\Drivers\RestDriver;
use Zarinpal\Zarinpal;
...

...
$client = new Zarinpal(config('zarinpal.merchantID'), new RestDriver());
if($response['Success']) {
	return 'Payment was successful.';
}
return 'Payment was not successful!';
...
```
# SandBox is for developers

if you want test zarinpal payment then SandBox is for you:

```php
<?php

...
use Zarinpal\Drivers\SoapDriver;
use Zarinpal\Zarinpal;
...

...
$client = new Zarinpal('test', new SoapDriver(), true);
...
```
