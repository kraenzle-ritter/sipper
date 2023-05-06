# Sipper: SIPs

[![Latest Version on Packagist](https://img.shields.io/packagist/v/kraenzle-ritter/sipper.svg?style=flat-square)](https://packagist.org/packages/kraenzle-ritter/sipper)
[![Total Downloads](https://img.shields.io/packagist/dt/kraenzle-ritter/sipper.svg?style=flat-square)](https://packagist.org/packages/kraenzle-ritter/sipper)
![GitHub Actions](https://github.com/kraenzle-ritter/sipper/actions/workflows/main.yml/badge.svg)

This small package is shared by Inge and Anton.

## Installation

You can install the package via composer:

```bash
composer require kraenzle-ritter/sipper
```

## Usage

```php
$metadata = file_getcontents($file);
$sipreader = new SipReader($metadata);

$sipreader->xml;
$sipreader->getDokumente();
$sipreader->getDokumentByDateiRef($dateiRef);
$sipreader->getDateiByDateiRef($dateiRef);
$sipreader->getPathByDateiRef($dateiRef);
```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
