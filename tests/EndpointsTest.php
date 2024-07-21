<?php

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Pdfapiio\PdfapiLaravel\Enums\ApiOutputType;
use Pdfapiio\PdfapiLaravel\PdfApi;

beforeEach(function () {
    Http::fake([
        'https://pdf-api.io/api/templates' => Http::response([
            ['id' => '111', 'name' => 'Invoice'],
            ['id' => '222', 'name' => 'Receipt'],
        ]),
        'https://pdf-api.io/api/templates/1234/pdf' => function (Request $request) {
            if ($request->hasHeader('Accept', 'application/json')) {
                return $request->data()['output'] === 'url'
                    ? Http::response(['status' => 200, 'data' => 'https://some-url.pdf'])
                    : Http::response(['status' => 200, 'data' => 'base64-encoded-pdf']);
            } else {
                return $request->data()['output'] === 'url'
                    ? Http::response(['status' => 200, 'data' => 'https://some-url.pdf'])
                    : Http::response('PDF content');
            }
        },
        'https://pdf-api.io/api/templates/merge' => Http::response(['status' => 200, 'data' => 'base64-encoded-pdf']),
    ]);
});

it('can get templates', function () {
    (new PdfApi('some-key'))->getTemplates();

    Http::assertSent(function (Request $request) {
        return $request->hasHeader('Accept', 'application/json')
            && $request->url() === 'https://pdf-api.io/api/templates';
    });
});

it('can render a PDF', function () {
    (new PdfApi('some-key'))->render('1234', ['some' => 'data']);

    Http::assertSent(function (Request $request) {
        return $request->hasHeader('Accept', 'application/pdf')
            && $request->hasHeader('Content-Type', 'application/json')
            && $request->url() === 'https://pdf-api.io/api/templates/1234/pdf'
            && $request['data'] === ['some' => 'data']
            && $request['output'] === 'pdf';
    });
});

it('can render a PDF and return a JSON response', function () {
    (new PdfApi('some-key'))->asJson()->render('1234', ['some' => 'data']);

    Http::assertSent(function (Request $request) {
        return $request->hasHeader('Accept', 'application/json')
            && $request->hasHeader('Content-Type', 'application/json')
            && $request->url() === 'https://pdf-api.io/api/templates/1234/pdf'
            && $request['data'] === ['some' => 'data']
            && $request['output'] === 'pdf';
    });
});

it('can render a PDF and return a Binary response', function () {
    (new PdfApi('some-key'))->asBinary()->render('1234', ['some' => 'data']);

    Http::assertSent(function (Request $request) {
        return $request->hasHeader('Accept', 'application/pdf')
            && $request->hasHeader('Content-Type', 'application/json')
            && $request->url() === 'https://pdf-api.io/api/templates/1234/pdf'
            && $request['data'] === ['some' => 'data']
            && $request['output'] === 'pdf';
    });
});

it('can output a PDF as a URL', function () {
    (new PdfApi('some-key'))->output(ApiOutputType::URL)->render('1234', ['some' => 'data']);

    Http::assertSent(function (Request $request) {
        return $request->hasHeader('Accept', 'application/pdf')
            && $request->hasHeader('Content-Type', 'application/json')
            && $request->url() === 'https://pdf-api.io/api/templates/1234/pdf'
            && $request['data'] === ['some' => 'data']
            && $request['output'] === 'url';
    });
});

it('can merge PDFs', function () {
    (new PdfApi('some-key'))->merge([
        ['id' => '111', 'data' => ['some' => 'data']],
        ['id' => '222', 'data' => ['some' => 'data']],
    ]);

    Http::assertSent(function (Request $request) {
        return $request->hasHeader('Accept', 'application/pdf')
            && $request->hasHeader('Content-Type', 'application/json')
            && $request->url() === 'https://pdf-api.io/api/templates/merge'
            && $request['templates'] === [
                ['id' => '111', 'data' => ['some' => 'data']],
                ['id' => '222', 'data' => ['some' => 'data']],
            ];
    });
});
