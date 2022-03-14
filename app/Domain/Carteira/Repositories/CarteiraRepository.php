<?php
namespace App\Domain\Carteira\Repositories;

use Carbon\Carbon;
use App\Domain\Cotacao\Models\Cotacao;
use App\Domain\Carteira\Models\Carteira;
use App\Domain\Rebalanceamento\Models\RebalanceamentoAtivo;

class CarteiraRepository
{
    public function getCarteiraComPercentualAtual(string $dataCotacao = null)
    {
        $dataSub30Days = Carbon::now()->subDays(30)->format('Y-m-d');
        $minhaCarteira = Carteira::with('ativo')->where('user_id', auth()->user()->id)->get();
        $cotacoesModelo = Cotacao::query();

        if (is_null($dataCotacao)) { //  Se não for passado nenhum dia, pega o dia de hoje até um mês atrás
            $cotacoesModelo->where('preco', '>', 0)
                        ->whereDate('created_at', '>', $dataSub30Days)
                        ->orderBy('created_at', 'desc');
                        
        } else { // Se for passado a data, pega da data passada até agora
            $cotacoesModelo->where('preco', '>', 0)
                        ->whereDate('created_at', '>=', $dataCotacao)
                        ->orderBy('created_at', 'asc');
        }

        $cotacoes = $cotacoesModelo->get();

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

    public function getCarteiraComPercentualIdeal()
    {
        $minhaCarteira = $this->getCarteiraComPercentualAtual();
        $rebalanceamentoAtivo = RebalanceamentoAtivo::with('ativo')->where('user_id', auth()->user()->id)->get();
        $cotacoes = Cotacao::where('preco', '>', 0)->orderBy('created_at', 'desc')->get();

        // Calcula percentual/peso ideal de cada ativo
        $carteiraIdeal = $rebalanceamentoAtivo->map(function ($ativo) use ($minhaCarteira, $cotacoes) {
            $cotacao = $cotacoes->where('ativo_id', $ativo->ativo_id)->first();
            $valorTotalCarteira = $minhaCarteira['valor_total_carteira'];
            
            $valor_ativo = ($valorTotalCarteira * $ativo->percentual) / 100; // Calcula o valor do ativo com base no percentual ideal
            $quantidade_ativo = $valor_ativo / $cotacao->preco; // Calcula a quantidade do ativo com base na cotação atual

            $ativo->valor_ativo = $valor_ativo;
            $ativo->quantidade_ativo = $quantidade_ativo;
            $ativo->cotacao = $cotacao->preco;

            return $ativo;
        });

        $carteiraIdealComTotal = collect([
            "ativos" => $carteiraIdeal,
            "valor_total_carteira" => $minhaCarteira['valor_total_carteira'],
        ]);

        return $carteiraIdealComTotal;
    }

    public function getCarteiraComPercentualAjuste()
    {
        $minhaCarteira = $this->getCarteiraComPercentualAtual();
        $carteiraIdeal = $this->getCarteiraComPercentualIdeal();

        // Calcula o percentual de ajuste de cada ativo
        $carteiraAjuste = $carteiraIdeal['ativos']->map(function ($ativo) use ($minhaCarteira) {
            $ativoMinhaCarteira = $minhaCarteira['ativos']->where('ativo_id', $ativo->ativo_id)->first(); // Pega o ativo da minha carteira

            if (!is_null($ativoMinhaCarteira)) {
                $ativo->quantidade_ativo = $ativo->quantidade_ativo - $ativoMinhaCarteira->quantidade_saldo; // Calcula a quantidade de ativos a ser ajustada          
                $ativo->percentual = $ativo->percentual - $ativoMinhaCarteira->percentual; // Calcula o percentual de ajuste
                $ativo->valor_ativo = $ativo->valor_ativo - $ativoMinhaCarteira->valor_ativo; // Calcula o valor de ajuste
            }

            return $ativo;
        });

        $carteiraComPercentualAjuste = collect([
            "ativos" => $carteiraAjuste,
            "valor_total_carteira" => $carteiraIdeal['valor_total_carteira'],
        ]);

        return $carteiraComPercentualAjuste;
    }

    public function getCarteiraComPercentualAtualPorClasse()
    {
        $minhaCarteira = $this->getCarteiraComPercentualAtual();
        $minhaCarteiraGrouped = $minhaCarteira['ativos']->groupBy('ativo.nome_classe_ativo');

        $minhaCarteiraPorClasses = $minhaCarteiraGrouped->map(function ($ativos, $index) use ($minhaCarteira) {
            $totalCarteira = $minhaCarteira['valor_total_carteira'];
            $percentual =  ($ativos->sum('valor_ativo') / $totalCarteira) * 100;
            return [
                'classe_ativo' => $index,
                'valor_total_classe' => $ativos->sum('valor_ativo'),
                'percentual' => number_format($percentual, 2, '.', ''),
            ];
        });

        return $minhaCarteiraPorClasses;
    }

    public function getCarteiraComPercentualIdealPorClasse()
    {
        $carteiraIdeal = $this->getCarteiraComPercentualIdeal();
        $carteiraIdealGrouped = $carteiraIdeal['ativos']->groupBy('ativo.nome_classe_ativo');
        
        $minhaCarteiraPorClasses = $carteiraIdealGrouped->map(function ($ativos, $index) use ($carteiraIdeal) {
            $totalCarteira = $carteiraIdeal['valor_total_carteira'];
            $percentual = ($ativos->sum('valor_ativo') / $totalCarteira) * 100;
            return [
                'classe_ativo' => $index,
                'valor_total_classe' => $ativos->sum('valor_ativo'),
                'percentual' => number_format($percentual, 2, '.', ''),
            ];
        });

        return $minhaCarteiraPorClasses;
    }
}