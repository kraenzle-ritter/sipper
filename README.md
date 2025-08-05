# Sipper: SIPs

[![Latest Version on Packagist](https://img.shields.io/packagist/v/kraenzle-ritter/sipper.svg?style=flat-square)](https://packagist.org/packages/kraenzle-ritter/sipper)
[![Total Downloads](https://img.shields.io/packagist/dt/kraenzle-ritter/sipper.svg?style=flat-square)](https://packagist.org/packages/kraenzle-ritter/sipper)
[![Tests](https://github.com/kraenzle-ritter/sipper/actions/workflows/main.yml/badge.svg)](https://github.com/kraenzle-ritter/sipper/actions/workflows/main.yml)
[![Code Quality](https://github.com/kraenzle-ritter/sipper/actions/workflows/code-quality.yml/badge.svg)](https://github.com/kraenzle-ritter/sipper/actions/workflows/code-quality.yml)

This small package is used to read and parse SIP (Submission Information Package) metadata files in the eCH-0160 ARELDA format. It provides easy access to documents, dossiers, files, and their relationships within the SIP structure.

## Installation

You can install the package via composer:

```bash
composer require kraenzle-ritter/sipper
```

## Usage

### Basic Usage

```php
use KraenzleRitter\Sipper\SipReader;

$metadata = file_get_contents($file);
$sipReader = new SipReader($metadata);

// Access the raw XML
$xml = $sipReader->xml;

// Get all documents
$documents = $sipReader->getDokumente();

// Get all dossiers
$dossiers = $sipReader->getDossiers();

// Get first level dossiers only
$firstLevelDossiers = $sipReader->getFirstLevelDossiers();

// Get document by file reference ID
$document = $sipReader->getDokumentByDateiRef($dateiRef);

// Get file information by reference ID
$file = $sipReader->getDateiByDateiRef($dateiRef);

// Get full path of a file by reference ID
$path = $sipReader->getPathByDateiRef($dateiRef);

// Get document title by filename
$title = $sipReader->getDokumentTitelByFilename('example.pdf');

// Get parent identifier for a dossier
$parentId = SipReader::getParentIdentifier($dossier, 'PREFIX_');
```

### Example: Finding Documents by Filename

```php
$sipReader = new SipReader($metadata);

// Find the document title for a specific file
$documentTitle = $sipReader->getDokumentTitelByFilename('report.pdf');
if (!empty($documentTitle)) {
    echo "Document title: " . $documentTitle;
} else {
    echo "File not found in SIP";
}
```

### Exception Handling

Some methods throw `InvalidArgumentException` when the requested element is not found:

```php
try {
    $document = $sipReader->getDokumentByDateiRef('NONEXISTENT');
} catch (InvalidArgumentException $e) {
    echo "Document not found: " . $e->getMessage();
}
```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
