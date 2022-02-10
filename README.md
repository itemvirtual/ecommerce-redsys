# Ecommerce Redsys
> Redsys payments with Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/itemvirtual/ecommerce-redsys.svg?style=flat-square)](https://packagist.org/packages/itemvirtual/ecommerce-redsys)
[![Total Downloads](https://img.shields.io/packagist/dt/itemvirtual/ecommerce-redsys.svg?style=flat-square)](https://packagist.org/packages/itemvirtual/ecommerce-redsys)


## Installation

You can install the package via composer:

```bash
composer require itemvirtual/ecommerce-redsys
```
Publish config (with `--force` option to update)
``` bash
php artisan vendor:publish --provider="Itemvirtual\EcommerceRedsys\EcommerceRedsysServiceProvider" --tag=config
```
Add this environment variable to your `.env`
``` bash
ECOMMERCE_REDSYS_ENVIRONMENT=test # or production
ECOMMERCE_REDSYS_KEY="sq7HjrUOBfKmC576ILgskD5srU870gJ7"
ECOMMERCE_REDSYS_CODE="999008881"
ECOMMERCE_REDSYS_TERMINAL=1
ECOMMERCE_REDSYS_CURRENCY=978
ECOMMERCE_REDSYS_LANGUAGE="001"
ECOMMERCE_REDSYS_NOTIFICATION_URL="${APP_URL}/redsys-notification-url"
ECOMMERCE_REDSYS_URL_OK="${APP_URL}/redsys-ok-url"
ECOMMERCE_REDSYS_URL_KO="${APP_URL}/redsys-ko-url"
ECOMMERCE_REDSYS_TRADE_NAME="My Ecommerce"
ECOMMERCE_REDSYS_TITULAR="Your name"
```
Add your `ECOMMERCE_REDSYS_NOTIFICATION_URL` to your `VerifyCsrfToken` middleware
```php
protected $except = [
    'redsys-notification-url'
];
```

## Usage
### To create the redsys payment form
```php
use Itemvirtual\EcommerceRedsys\EcommerceRedsys;

$EcommerceRedsys = new EcommerceRedsys();
$redsysForm = $EcommerceRedsys->setAmount(100)
    ->setMerchantData('YourMerchantData')
    ->setDescription('Purchase title')
    ->setSubmitButtonTitle('Pay button text')
    ->createForm();
```
To debug parameters
```php
$EcommerceRedsys = new EcommerceRedsys();
$params = $EcommerceRedsys->setAmount(100)
    ->setMerchantData('YourMerchantData')
    ->setDescription('Purchase title')
    ->setMerchantcode('Your Custom Data')
    ->setCurrency(999)
    ->setLanguage('Your Custom Data')
    ->setTransactiontype('Your Custom Data')
    ->setTerminal(1)
    ->setMethod('Your Custom Data')
    ->setNotificationUrl('Your Custom Data')
    ->setUrlOk('Your Custom Data')
    ->setUrlKo('Your Custom Data')
    ->setTradeName('Your Custom Data')
    ->setTitular('Your Custom Data')
    ->setEnvironment('test')
    ->setSubmitButtonTitle('Your Custom Button title')
    ->debugParameters();
```

### To get redsys response
```php
use Itemvirtual\EcommerceRedsys\EcommerceRedsys;

$EcommerceRedsys = new EcommerceRedsys();
$parameters = $EcommerceRedsys->getMerchantParameters($request->get('Ds_MerchantParameters'));
$yourMerchantData = $EcommerceRedsys->getMerchantData($request->get('Ds_MerchantParameters'));

// validate transaction
$isValid = $EcommerceRedsys->checkValidPayment($request->all())
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

-   [Itemvirtual](https://github.com/itemvirtual)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Links

[Redsys Entorno de pruebas](https://pagosonline.redsys.es/entornosPruebas.html)