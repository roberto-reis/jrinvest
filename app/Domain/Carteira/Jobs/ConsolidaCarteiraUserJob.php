<?php

namespace App\Domain\Carteira\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Domain\Cotacao\Models\Cotacao;
use Illuminate\Queue\SerializesModels;
use App\Domain\Carteira\Models\Carteira;
use App\Domain\Operacao\Models\Operacao;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Domain\Carteira\Models\CarteiraConsolidada;
use App\Domain\Carteira\Models\RentabilidadeCarteira;

class ConsolidaCarteiraUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $usuarioLogado;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($usuario)
    {
        $this->usuarioLogado = $usuario;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {

            DB::beginTransaction();
            
            // Calcula o percentual e rentabilidade de cada ativo com a cotação atual
            $carteiraConsolidada = $this->getCarteiraCalculaPercentualAtual();

            // Salva ou atualiza a consolidação da carteira
            $carteiraConsolidada['ativos']->map(function ($ativo) {
                CarteiraConsolidada::query()->updateOrCreate([
                    'user_id' => $ativo->user_id,
                    'ativo_id' => $ativo->ativo_id,
                ], [
                    'quantidade_saldo' => $ativo->quantidade_saldo,
                    'preco_medio' => $ativo->preco_medio,
                    'custo_total_ativo' => $ativo->custo_total_ativo,
                    'cotacao' => $ativo->cotacao,
                    'valor_total_ativo' => $ativo->valor_total_ativo,
                    'percentual' => $ativo->percentual,
                    'rentabilidade_valor' => $ativo->rentabilidade_valor,
                    'rentabilidade_percentual' => $ativo->rentabilidade_percentual,
                ]);
            });
            
            // Salva a rentabilidade da carteira
            CalculaRentabilidadeCarteiraJob::dispatch($carteiraConsolidada, $this->usuarioLogado->id);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao consolidar carteira do Usuário: ', [
                'Menssagem' => $e->getMessage(),
                'JOB' => __CLASS__,
                'Linha' => $e->getLine(),
            ]);
            return false;
        }
    }

    /**
     * Calcula o percentual e rentabilidade de cada ativo com a cotação atual
     *
     * @return Collection
     */
    private function getCarteiraCalculaPercentualAtual(): Collection
    {        
        $dataHojeMenos5Dias = date('Y-m-d', strtotime('-5 day'));
        $cotacoes = Cotacao::query()->where('preco', '>', 0)
                                    ->whereDate('created_at', '>=', $dataHojeMenos5Dias)
                                    ->orderBy('created_at', 'desc')->get();
                                
        $minhaCarteira = Carteira::query()->where('user_id', $this->usuarioLogado->id)->get();

        if ($cotacoes->isEmpty() || $minhaCarteira->isEmpty()) {
            throw new \Exception('Não foi possível calcular o percentual, não há cotações disponíveis ou não há ativos na carteira');
        }

        // Atualiza o custo total do ativo com a cotação atual
        $myCarteiraUpdated = $minhaCarteira->map(function ($ativo) use ($cotacoes) {
            $cotacao = $cotacoes->where('ativo_id', $ativo->ativo_id)->first();
            $ativo->cotacao = $cotacao->preco;
            $ativo->valor_total_ativo = $ativo->quantidade_saldo * $cotacao->preco; // calcula o valor do ativo com a cotação atual
            
            return $ativo;
        });

        // Pega o custo e valor total de todos os ativos atualizados com a cotação atual
        $custoTotalCarteira = $myCarteiraUpdated->sum('custo_total_ativo');
        $valorTotalCarteira = $myCarteiraUpdated->sum('valor_total_ativo');
        
        // Calcula percentual e rentabilidade de cada ativo
        $carteiraComPercentualAtual = $myCarteiraUpdated->map(function ($ativo) use ($valorTotalCarteira) {
            $ativo->percentual = ($ativo->valor_total_ativo / $valorTotalCarteira) * 100; // Porcentagem do ativo na carteira
            $ativo->rentabilidade_valor = ($ativo->valor_total_ativo - $ativo->custo_total_ativo); // calcula a rentabilidade do ativo em valor
            $ativo->rentabilidade_percentual = ($ativo->rentabilidade_valor / $ativo->custo_total_ativo) * 100; // calcula a rentabilidade em %

            return $ativo;
        });

        $minhaCarteiraAtualizada = collect([
            "ativos" => $carteiraComPercentualAtual,
            "valor_total_carteira" => $valorTotalCarteira,
            "custo_total_carteira" => $custoTotalCarteira,
        ]);

        return $minhaCarteiraAtualizada;
    }

}
