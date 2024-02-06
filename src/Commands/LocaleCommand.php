<?php

namespace Bertell\Locale\Commands;

use Illuminate\Console\Command;

class LocaleCommand extends Command
{
    public $signature = 'laravel-locale';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
