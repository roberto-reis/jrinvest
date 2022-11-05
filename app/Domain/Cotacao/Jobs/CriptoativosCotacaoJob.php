<?php

namespace App\Domain\Cotacao\Jobs;

use App\Domain\Ativo\Models\Ativo;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use App\Domain\Cotacao\Models\Cotacao;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Domain\Cotacao\Services\CotacaoBrapiService;

class CriptoativosCotacaoJob implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $classeAtivo;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $classeAtivo)
    {
        $this->classeAtivo = $classeAtivo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(CotacaoBrapiService $serviceCotacao)
    {

        try {

            $ativos = Ativo::where('classe_ativo_id', $this->classeAtivo['id'])->get();

            if ($ativos->isEmpty()) {
                throw new \Exception('Não há Ativos cadastrados para classe ' . $this->classeAtivo['nome']);
            }

            $ativoCriptoImploded = $ativos->implode('codigo',',');
            $cotacaoCripto = $serviceCotacao->getCotacoesCripto($ativoCriptoImploded);

            if (!empty($cotacaoCripto)) {
                foreach ($cotacaoCripto['coins'] as $cotacao) {
                    Cotacao::create([
                        'ativo_id' => $ativos->where('codigo', $cotacao['coin'])->first()->id,
                        'moeda_ref' => $cotacao['currency'],
                        'preco' => $cotacao['regularMarketPrice'] ?? '0.0',
                    ]);
                }
            }

            Log::info("Cotações de ativos classe {$this->classeAtivo['nome']}: ", [
                "Total"  => $cotacaoCripto ? count($cotacaoCripto['coins']) : 0,
                "ativos" => $ativoCriptoImploded
            ]);

        } catch (\Exception $e) {
            throw $e;
        }

    }




}
