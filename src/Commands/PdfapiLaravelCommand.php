<?php

namespace Pdfapiio\PdfapiLaravel\Commands;

use Illuminate\Console\Command;

class PdfapiLaravelCommand extends Command
{
    public $signature = 'pdfapi-laravel';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
