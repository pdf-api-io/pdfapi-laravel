# PDF-API.io Laravel integration

[![Latest Version on Packagist](https://img.shields.io/packagist/v/pdf-api-io/pdfapi-laravel.svg?style=flat-square)](https://packagist.org/packages/pdf-api-io/pdfapi-laravel)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/pdf-api-io/pdfapi-laravel/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/pdf-api-io/pdfapi-laravel/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/pdf-api-io/pdfapi-laravel/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/pdf-api-io/pdfapi-laravel/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/pdf-api-io/pdfapi-laravel.svg?style=flat-square)](https://packagist.org/packages/pdf-api-io/pdfapi-laravel)

This package provides a Laravel integration for [PDF-API.io](https://pdf-api.io). PDF-API.io is a service that allows you to design your PDF templates in a drag-and-drop editor and render them using a simple API.

## Installation

You can install the package via composer:

```bash
composer require pdf-api-io/pdfapi-laravel
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="pdfapi-laravel-config"
```

This is the contents of the published config file:

```php
return [
    'api_key' => env('PDF_API_KEY'),
];
```

## Usage

### List available templates

To list all available templates, you can use the `getTemplates` method on the `PdfApi` facade.

```php
use Pdfapiio\PdfapiLaravel\Facades\PdfApi;

$templates = PdfApi::getTemplates();
```

### Render a PDF

To render a PDF, you can use the `render` method.

```php
use Pdfapiio\PdfapiLaravel\Facades\PdfApi;

$pdf = PdfApi::render('your-template-id', [
    'some-variable' => 'some-value',
]);

echo $pdf; // Output: "%PDF-1.7 %���� 6 0 obj << /Type /Page /Parent 1 0 R..."
```

By default, the `render` method will return the content of the PDF as a string. If you want to get a JSON response instead, you can use the `asJson` method. When JSON response is requested, the content of the PDF will be base64 encoded.

```php
use Pdfapiio\PdfapiLaravel\Facades\PdfApi;

$pdf = PdfApi::asJson()->render('your-template-id', [
    'some-variable' => 'some-value',
]);

$content = base64_decode($pdf['data']);
```

You can control the output by calling the `output` method:

```php
use Pdfapiio\PdfapiLaravel\Facades\PdfApi;

PdfApi::output(ApiOutputType::PDF)->render('your-template', []); // Returns the PDF as a string
PdfApi::output(ApiOutputType::URL)->render('your-template', []); // Returns the URL to the rendered PDF
```

### Merge templates

If you have multiple templates and you want to merge them into a single PDF, you can use the `merge` method.

```php
use Pdfapiio\PdfapiLaravel\Facades\PdfApi;

$pdf = PdfApi::merge([
    [
        'id' => 'your-template-id',
        'data' => [
            'some-variable' => '
        ],
    ],
    [
        'id' => 'your-template-id',
        'data' => [
            'some-variable' => '
        ],
    ],
]);
```

You can also use the `asJson` and `output` methods with the `merge` method.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [fredmarmillod](https://github.com/fredmarmillod)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
