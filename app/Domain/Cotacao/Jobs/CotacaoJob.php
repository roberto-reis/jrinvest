<?php

namespace App\Domain\Cotacao\Jobs;

use Illuminate\Bus\Queueable;
use App\Domain\Ativo\Models\Ativo;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Domain\Cotacao\Jobs\AcoesEFIICotacaoJob;
use App\Domain\ClasseAtivo\Models\ClasseAtivo;
use App\Domain\Cotacao\Services\CotacaoBrapiService;

class CotacaoJob implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(CotacaoBrapiService $serviceCotacao)
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
