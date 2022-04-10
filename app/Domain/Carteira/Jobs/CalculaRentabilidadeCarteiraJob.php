<?php

namespace App\Domain\Carteira\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Domain\Carteira\Models\RentabilidadeCarteira;

class CalculaRentabilidadeCarteiraJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $usuarioId;
    private $carteiraConsolidada;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($carteiraConsolidada, string $usuario_id)
    {
        $this->carteiraConsolidada = $carteiraConsolidada;
        $this->usuarioId = $usuario_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {

            $valorTotalCarteira = $this->carteiraConsolidada['valor_total_carteira'];
            $custoTotalCarteira = $this->carteiraConsolidada['custo_total_carteira'];
            $rentabilidadeValor = ($valorTotalCarteira - $custoTotalCarteira); // Calcula a rentabilidade do valor total da carteira
            $rentabilidadePercentual = ($rentabilidadeValor / $custoTotalCarteira) * 100; // Calcula a rentabilidade do percentual total da carteira

            $rentabilidadeCarteira = new RentabilidadeCarteira();
            $rentabilidadeCarteira->user_id = $this->usuarioId;
            $rentabilidadeCarteira->custo_total_carteira = $custoTotalCarteira;
            $rentabilidadeCarteira->valor_total_carteira = $valorTotalCarteira;
            $rentabilidadeCarteira->rentabilidade_valor = $rentabilidadeValor;
            $rentabilidadeCarteira->rentabilidade_percentual = $rentabilidadePercentual;
            // $rentabilidadeCarteira->payload_ativos = json_encode($this->carteiraConsolidada['ativos']);

            $rentabilidadeCarteira->save();

        } catch (\Exception $e) {
            Log::error('Erro ao salvar rentabilidade',[
                'Menssagem' => $e->getMessage(),
                'JOB' => __CLASS__,
                'Linha' => $e->getLine(),
            ]);
        }
    }
}
