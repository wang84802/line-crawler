<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\LineBotService;

class Clean extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean documents';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(LineBotService $LineBotService)
    {
        parent::__construct();
        $this->LineBotService = $LineBotService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->LineBotService->pushMessage('Mac序號');
    }
}
