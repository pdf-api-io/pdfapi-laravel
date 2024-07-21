<?php

namespace Pdfapiio\PdfapiLaravel;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PdfapiLaravelServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('pdfapi-laravel')
            ->hasConfigFile();
    }

    public function packageBooted()
    {
        $this->app->singleton(PdfApi::class, function ($app) {
            return new PdfApi(config('pdfapi.api_key'));
        });
    }
}
