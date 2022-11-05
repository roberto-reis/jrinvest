<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Domain\ClasseAtivo\Models\ClasseAtivo;
use App\Domain\Cotacao\Jobs\AcoesEFIICotacaoJob;
use App\Domain\Cotacao\Jobs\CriptoativosCotacaoJob;

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
        try {

            $classesAtivos = ClasseAtivo::all()->toArray();

            if (empty($classesAtivos)) {
                throw new \Exception('Não há Classe de Ativos cadastradas');
            }

            foreach ($classesAtivos as $classeAtivo) {
                switch ($classeAtivo['nome']) {
                    case 'Ações':
                    case 'FII':
                        AcoesEFIICotacaoJob::dispatch($classeAtivo);
                        break;
                    case 'Cripto':
                    case 'Stablecoin':
                        CriptoativosCotacaoJob::dispatch($classeAtivo);
                        break;
                }
            }

        } catch (\Exception $e) {
            throw $e;
        }
    }
}
