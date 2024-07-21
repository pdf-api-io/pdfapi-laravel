<?php

namespace Pdfapiio\PdfapiLaravel\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Pdfapiio\PdfapiLaravel\PdfapiLaravelServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            PdfapiLaravelServiceProvider::class,
        ];
    }
}
