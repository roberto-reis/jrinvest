<?php

namespace App\Domain\Carteira\Jobs;

use Illuminate\Bus\Queueable;
use App\Domain\User\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Domain\Cotacao\Models\Cotacao;
use Illuminate\Queue\SerializesModels;
use App\Domain\Operacao\Models\Operacao;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Domain\Carteira\Models\CarteiraConsolidada;

class ConsolidaTodasCarteirasJob implements ShouldQueue
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

                // Pega todas as operações do usuário e monta a carteira 
                $carteiraMontada = $this->montaCarteiraUsuario($usuario->id);
                
                // Calcula o percentual e rentabilidade de cada ativo com a cotação atual
                $carteiraConsolidada = $this->getCarteiraCalculaPercentualAtual($carteiraMontada);

                // Salva ou atualiza a consolidação da carteira
                $carteiraConsolidada['ativos']->map(function ($ativo) {
                    CarteiraConsolidada::query()->updateOrCreate([
                        'user_id' => $ativo['user_id'],
                        'ativo_id' => $ativo['ativo_id'],
                    ], [
                        'quantidade_saldo' => $ativo['quantidade_saldo'],
                        'preco_medio' => $ativo['preco_medio'],
                        'custo_total_ativo' => $ativo['custo_total_ativo'],
                        'cotacao' => $ativo['cotacao'],
                        'valor_total_ativo' => $ativo['valor_total_ativo'],
                        'percentual' => $ativo['percentual'],
                        'rentabilidade_valor' => $ativo['rentabilidade_valor'],
                        'rentabilidade_percentual' => $ativo['rentabilidade_percentual'],
                    ]);
                });

            }          

        } catch (\Exception $e) {
            Log::error('Erro ao consolidar todas carteiras: ', [
                $e->getMessage(),
                $e->getLine()
            ]);
            return false;
        }
    }

    /**
     * Pega todas as operações do usuário e monta a carteira
     *
     * @return Collection
     */
    private function montaCarteiraUsuario(string $usuario_id): Collection
    {
        $operacoes = Operacao::select('operacoes.*', 'ativos.codigo as codigo_ativo')
                ->join('ativos', 'operacoes.ativo_id', '=', 'ativos.id')
                ->where('user_id', $usuario_id)
                ->get();

        if ($operacoes->isEmpty()) {
            throw new \Exception('Nenhuma operação encontrada para o usuário');
        }

        $operacoesAtivos = $operacoes->groupBy('codigo_ativo');

        $carteiraMontada = $operacoesAtivos->map(function ($operacoes) {
            $newOperacao = $operacoes->first();
            $somaOperacoesCompras = $operacoes->where('tipo_operacao', 'compra')->sum('quantidade');
            $somaOperacoesVendas = $operacoes->where('tipo_operacao', 'venda')->sum('quantidade');
            $somaValorTotal = $operacoes->where('tipo_operacao', 'compra')->sum('valor_total');

            $quantidadeSaldo = ($somaOperacoesCompras - $somaOperacoesVendas);
            $precoMedio = ($somaValorTotal / $somaOperacoesCompras);
            $custoTotalAtivo = ($quantidadeSaldo * $precoMedio);

            return [
                'user_id' => $newOperacao->user_id,
                'ativo_id' => $newOperacao->ativo_id,
                'quantidade_saldo' => $quantidadeSaldo,
                'preco_medio' => $precoMedio,
                'custo_total_ativo' => $custoTotalAtivo,
            ];            
        });

        return $carteiraMontada;
    }

    /**
     * Calcula o percentual e rentabilidade de cada ativo com a cotação atual
     *
     * @param Collection $carteiraMontada
     * @return Collection
     */
    private function getCarteiraCalculaPercentualAtual(Collection $carteiraMontada): Collection
    {
        $minhaCarteira = $carteiraMontada;
        
        $dataHojeMenos2Dias = date('Y-m-d', strtotime('-2 day'));
        $cotacoes = Cotacao::query()->where('preco', '>', 0)
                                    ->whereDate('created_at', '>=', $dataHojeMenos2Dias)
                                    ->orderBy('created_at', 'desc')->get();

        if ($cotacoes->isEmpty() || $minhaCarteira->isEmpty()) {
            throw new \Exception('Não foi possível calcular o percentual, não há cotações disponíveis ou não há ativos na carteira');
        }

        // Atualiza o custo total do ativo com a cotação atual
        $myCarteiraUpdated = $minhaCarteira->map(function ($ativo) use ($cotacoes) {
            $cotacao = $cotacoes->where('ativo_id', $ativo['ativo_id'])->first();
            $ativo['cotacao'] = $cotacao->preco;
            $ativo['valor_total_ativo'] = $ativo['quantidade_saldo'] * $cotacao->preco; // calcula o valor do ativo com a cotação atual

            return $ativo;
        });

        // Pega o custo e valor total de todos os ativos atualizados com a cotação atual
        $custoTotalCarteira = $myCarteiraUpdated->sum('custo_total_ativo');
        $valorTotalCarteira = $myCarteiraUpdated->sum('valor_total_ativo');
        
        // Calcula percentual e rentabilidade de cada ativo
        $carteiraComPercentualAtual = $myCarteiraUpdated->map(function ($ativo) use ($valorTotalCarteira) {
            $ativo['percentual'] = ($ativo['valor_total_ativo'] / $valorTotalCarteira) * 100; // Porcentagem do ativo na carteira
            $ativo['rentabilidade_valor'] = ($ativo['valor_total_ativo'] - $ativo['custo_total_ativo']); // calcula a rentabilidade do ativo em valor
            $ativo['rentabilidade_percentual'] = ($ativo['rentabilidade_valor'] / $ativo['custo_total_ativo']) * 100; // calcula a rentabilidade em %

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
