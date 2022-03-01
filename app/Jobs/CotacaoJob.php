<?php

namespace App\Jobs;

use App\Models\Ativo;
use App\Models\Cotacao;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Domain\Cotacao\Services\CotacaoService;

class CotacaoJob implements ShouldQueue
{
    private $serviceCotacao;
    private $ativo;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Ativo $ativo)
    {
        $this->serviceCotacao = new CotacaoService();
        $this->ativo = $ativo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            
            if ($this->ativo->nome_classe_ativo == 'Ações' || $this->ativo->nome_classe_ativo == 'FII') {
                $responseCotacao = $this->serviceCotacao->getCotacoes($this->ativo->codigo);
                Cotacao::create([
                    'ativo_id' => $this->ativo->id,
                    'moeda_ref' => 'brl',
                    'preco' => $responseCotacao["Global Quote"]["05. price"],
                ]);
            }

            if ($this->ativo->nome_classe_ativo == 'Cripto') {
                $responseCotacaoCripto = $this->serviceCotacao->getCotacoesCripto($this->ativo->codigo);
                Cotacao::create([
                    'ativo_id' => $this->ativo->id,
                    'moeda_ref' => 'brl',
                    'preco' => $responseCotacaoCripto["Realtime Currency Exchange Rate"]["5. Exchange Rate"],
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Erro ao consultar cotação: ', [$e->getMessage()]);
        }

    }
}
