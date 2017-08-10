# Zarinpal Transaction Library for Laravel
Just another transaction request library for zarinpal

##add provider
Add provider to providers list in "config/app.php":
just add :
```php
'providers' => [
    ...
    Zarinpal\Laravel\ZarinpalServiceProvider::class,
    ...
]
```
and run
'`php artisan vendor:publish --provider="Zarinpal\Laravel\ZarinpalServiceProvider"`'
to add config file to laravel configs directory.

##usage

###request
```php
use Zarinpal\Drivers\SoapDriver;
use Zarinpal\Zarinpal;

$client = new Zarinpal(config('zarinpal.merchantID'), new SoapDriver());

$answer = $client->request("http://example.com/verify.php", 4000, 'Payment Description');

//it will redirect to zarinpal to do the transaction or fail and just echo the errors.
if(isset($answer['Authority'])) {
    return $client->redirect($answer['Authority']);
}

return 'There Was an error!';
```

###verify
```php
use Zarinpal\Drivers\SoapDriver;
use Zarinpal\Zarinpal;

$client = new Zarinpal(config('zarinpal.merchantID'),new SoapDriver());

$result = $client->verify('OK', 4000);
if($result['Success']) return 'Success';
return 'Payment was not successful!';
```

##For Developers
just put 'true' as third parameter of new instance of Zarinpal in both request and verify!

```php
$client = new Zarinpal('XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX',new SoapDriver(), true);
```
