<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Domain\Carteira\Jobs\ConsolidaCarteiraJob;

class ConsolidaCarteiraCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consolida:carteiras';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consolida as carteiras de todos os usuários';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        ConsolidaCarteiraJob::dispatch();
    }
}
