<?php

namespace Pdfapiio\PdfapiLaravel;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Pdfapiio\PdfapiLaravel\Enums\ApiOutputType;

class PdfApi
{
    const API_URL = 'https://pdf-api.io/api';

    public string $accept = 'application/pdf';

    public ApiOutputType $output = ApiOutputType::PDF;

    public function __construct(
        public string $apiKey,
    ) {}

    public function asBinary(): static
    {
        $this->accept = 'application/pdf';

        return $this;
    }

    public function asJson(): static
    {
        $this->accept = 'application/json';

        return $this;
    }

    public function output(ApiOutputType $output): static
    {
        $this->output = $output;

        return $this;
    }

    public function request(): PendingRequest
    {
        return Http::baseUrl(self::API_URL)
            ->withToken($this->apiKey);
    }

    public function getTemplates(): mixed
    {
        return $this->request()
            ->withHeader('Accept', 'application/json')
            ->get('/templates')
            ->json();
    }

    public function render(string $templateId, array $data): mixed
    {
        $response = $this->request()
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Accept', $this->accept)
            ->post("/templates/{$templateId}/pdf", [
                'data' => $data,
                'output' => $this->output->value,
            ]);

        return $this->accept === 'application/json'
            ? $response->json()
            : $response->body();
    }

    public function merge(array $templates): mixed
    {
        return $this->request()
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Accept', $this->accept)
            ->post('/templates/merge', [
                'templates' => $templates,
                'output' => $this->output->value,
            ])->json();
    }
}
