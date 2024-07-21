<?php

namespace Pdfapiio\PdfapiLaravel\Enums;

enum ApiOutputType: string
{
    case PDF = 'pdf';
    case URL = 'url';
}
