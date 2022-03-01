<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Domain\Cotacao\Jobs\CotacaoJob;

class CotacaoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:cotacao';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualização de cotação de ações, FII e criptomoedas';

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
        CotacaoJob::dispatch();
    }
}
