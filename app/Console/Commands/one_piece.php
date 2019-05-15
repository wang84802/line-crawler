<?php

namespace App\Console\Commands;

use App\Http\Controllers\LineController;
use Illuminate\Console\Command;

class one_piece extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'one_piece';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(LineController $LineController)
    {
        parent::__construct();
        $this->LineController = $LineController;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->LineController->one_piece();
    }
}
