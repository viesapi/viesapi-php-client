# VIES API Client for PHP

This is the official repository for [VIES API](https://viesapi.eu) Client for PHP.

[VIES API](https://viesapi.eu) provides selected entrepreneurs' data using, among others, web services, programming
libraries, and dedicated applications. By using the available software (libraries, applications and Excel add-in)
your customers will be able to:

* check contractors' EU VAT number status in VIES system,
* download company details from VIES system,
* automatically fill in the invoice forms,

in the fastest possible way.

## Documentation

The documentation and samples are available [here](https://viesapi.eu/vies-programming-libraries/).

## How to use

### Composer

The release version of the library is published in [Packagist](https://packagist.org/packages/viesapi/client).
Add the following dependency using the _composer_ tool:

```bash
composer require viesapi/client
```

### Manual setup

Copy the _VIESAPI_ directory and its contents somewhere within your project structure. Then include _VIESAPI/VIESAPIClient.php_
in your source code (it provides all API definitions) and call the autoloader function.

```php
require_once 'VIESAPI/VIESAPIClient.php';
			
\VIESAPI\VIESAPIClient::registerAutoloader();
```

## License

This project is delivered under Apache License, Version 2.0:

- [![License (Apache 2.0)](https://img.shields.io/badge/license-Apache%20version%202.0-blue.svg?style=flat-square)](http://www.apache.org/licenses/LICENSE-2.0)