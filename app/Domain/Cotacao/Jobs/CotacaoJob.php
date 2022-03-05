<?php

namespace App\Domain\Cotacao\Jobs;

use App\Models\Ativo;
use App\Models\Cotacao;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Domain\Cotacao\Services\CotacaoBrapiService;

class CotacaoJob implements ShouldQueue
{
    private $serviceCotacao;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->serviceCotacao = new CotacaoBrapiService();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        try {

            $ativos = Ativo::with('classeAtivo')->get();

            if (is_null($ativos)) {
                throw new \Exception('Não há ativos cadastrados');
            }

            // Cotação de ações e FII
            $ativoAcoesFii = $ativos->filter(function ($ativo) {
                return $ativo->nome_classe_ativo != 'Cripto';
            });

            $ativoAcoesFiiImploded = $ativoAcoesFii->implode('codigo',',');
            $cotacaoAcoesFii = $this->serviceCotacao->getCotacoes($ativoAcoesFiiImploded);

            foreach ($cotacaoAcoesFii['results'] as $cotacao) {
                Cotacao::create([
                    'ativo_id' => $ativoAcoesFii->where('codigo', $cotacao['symbol'])->first()->id,
                    'moeda_ref' => $cotacao['currency'],
                    'preco' => $cotacao['regularMarketPrice'] ?? 0,
                ]);
            }
            Log::info('Total cotações de ações e FII: ' . count($cotacaoAcoesFii['results']));

            // Cotação de criptomoedas
            $ativoCripto = $ativos->filter(function ($ativo) {
                return $ativo->nome_classe_ativo == 'Cripto';
            });

            $ativoCriptoImploded = $ativoCripto->implode('codigo',',');
            $cotacaoCripto = $this->serviceCotacao->getCotacoesCripto($ativoCriptoImploded);         

            foreach ($cotacaoCripto['coins'] as $cotacao) {
                Cotacao::create([
                    'ativo_id' => $ativoCripto->where('codigo', $cotacao['coin'])->first()->id,
                    'moeda_ref' => $cotacao['currency'],
                    'preco' => $cotacao['regularMarketPrice'] ?? 0,
                ]);
            }
            Log::info('Total cotações de criptomoedas: ' . count($cotacaoCripto['coins']));            
            
        } catch (\Exception $e) {
            Log::error('Erro ao consultar cotação: ', [$e->getMessage()]);
        }

    }



    
}
