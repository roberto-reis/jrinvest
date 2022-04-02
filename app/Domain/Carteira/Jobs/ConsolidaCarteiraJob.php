<?php

namespace App\Domain\Carteira\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Domain\Cotacao\Models\Cotacao;
use Illuminate\Queue\SerializesModels;
use App\Domain\Cotacao\Jobs\CotacaoJob;
use App\Domain\Carteira\Models\Carteira;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Domain\Carteira\Models\CarteiraConsolidada;
use App\Domain\User\Models\User;

class ConsolidaCarteiraJob implements ShouldQueue
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
    public function handle()
    {
        
        try {

            
            $usuarios = User::all();
            foreach ($usuarios as $usuario) {

                // Calcula o percentual e rentabilidade de cada ativo com a cotação atual
                $carteiraConsolidada = $this->getCarteiraCalculaPercentualAtual($usuario->id);

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
                        'valor_total_ativo' => $ativo->valor_ativo,
                        'percentual' => $ativo->percentual,
                        'rentabilidade_valor' => $ativo->rentabilidade_valor,
                        'rentabilidade_percentual' => $ativo->rentabilidade_percentual,
                    ]);
                });

            }            

        } catch (\Exception $e) {
            Log::error('Erro ao consolidar carteira: ', [$e->getMessage()]);
            return false;
        }
    }


    /**
     * Calcula o percentual, quantidade e valor de cada ativo da carteira para o rebalanceamento
     * @param ?string $dataPeriodoRentabilidade
     * @return Collection
     */
    private function getCarteiraCalculaPercentualAtual(string $usuario_id, string $dataPeriodoRentabilidade = null): Collection
    {
        $cotacoesModelo = Cotacao::query();
        $minhaCarteira = Carteira::query()->where('user_id', $usuario_id)->get();

        if (is_null($dataPeriodoRentabilidade)) { //  Se não for passado nenhum dia, pega a cotação mais recente
            $cotacoesModelo->where('preco', '>', 0)
                            ->orderBy('created_at', 'desc');              
        } else { // Se for passado a data, pega a cotação mais recente antes da data passada
            $cotacoesModelo->where('preco', '>', 0)
                            ->whereDate('created_at', '>=', $dataPeriodoRentabilidade)
                            ->orderBy('created_at', 'asc');
        }

        $cotacoes = $cotacoesModelo->get();

        if ($cotacoes->isEmpty() || $minhaCarteira->isEmpty()) {
            throw new \Exception('Não foi possível calcular o percentual, não há cotações disponíveis ou não há ativos na carteira');
        }

        // Atualiza o custo total do ativo com a cotação atual
        $myCarteiraUpdated = $minhaCarteira->map(function ($ativo) use ($cotacoes) {
            $cotacao = $cotacoes->where('ativo_id', $ativo->ativo_id)->first();
            $ativo->cotacao = $cotacao->preco;
            $ativo->valor_ativo = $ativo->quantidade_saldo * $cotacao->preco; // calcula o valor do ativo com a cotação atual
            
            return $ativo;
        });

        // Pega o custo total de todos os ativos atualizados com a cotação atual
        $valorTotalCarteira = $myCarteiraUpdated->sum('valor_ativo');
        $custoTotalCarteira = $myCarteiraUpdated->sum('custo_total_ativo');

        // Calcula percentual e rentabilidade de cada ativo
        $carteiraComPercentualAtual = $myCarteiraUpdated->map(function ($ativo) use ($valorTotalCarteira) {
            $ativo->percentual = ($ativo->valor_ativo / $valorTotalCarteira) * 100; // Porcentagem do ativo na carteira
            $ativo->rentabilidade_valor = ($ativo->valor_ativo - $ativo->custo_total_ativo); // calcula a rentabilidade do ativo em valor
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
