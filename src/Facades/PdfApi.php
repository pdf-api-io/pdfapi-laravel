<?php

namespace Pdfapiio\PdfapiLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Pdfapiio\PdfapiLaravel\PdfApi
 */
class PdfApi extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Pdfapiio\PdfapiLaravel\PdfApi::class;
    }
}
