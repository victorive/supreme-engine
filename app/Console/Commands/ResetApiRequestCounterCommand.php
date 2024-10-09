<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ThirdPartySyncService;

class ResetApiRequestCounterCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:api-request-counter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset API request counter';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ThirdPartySyncService::resetCounts();
    }
}
