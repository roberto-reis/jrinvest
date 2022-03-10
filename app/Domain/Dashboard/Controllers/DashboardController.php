<?php

namespace App\Domain\Dashboard\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domain\Cotacao\Models\Cotacao;
use App\Domain\Carteira\Models\Carteira;
use App\Domain\Rebalanceamento\Models\RebalanceamentoAtivo;

class DashboardController extends Controller
{

    public function index()
    {
        $carteiraComPercentualAjuste = $this->getCarteiraComPercentualAjuste();
        

        return Inertia::render('Dashboard/Home', [
            'minhaCarteira' => $carteiraComPercentualAjuste,
        ]);
    }

    private function getCarteiraComPercentualAtual()
    {
        $cotacoes = Cotacao::orderBy('created_at', 'desc')->get();
        $minhaCarteira = Carteira::with('ativo')->where('user_id', auth()->user()->id)->get();

        // Atualiza o custo total do ativo com a cotação atual
        $myCarteiraUpdated = $minhaCarteira->map(function ($item) use ($cotacoes) {
            $cotacao = $cotacoes->where('ativo_id', $item->ativo_id)->first();
            $item->cotacao_atual = $cotacao->preco;
            $item->valor_total_ativo = $item->quantidade_saldo * $cotacao->preco;
            
            return $item;
        });

        // Pega o custo total de todos os ativos atualizados com a cotação atual
        $valorTotalCarteira = $myCarteiraUpdated->sum('valor_total_ativo');

        // Calcula percentual/peso atual de cada ativo
        $carteiraComPercentualAtual = $myCarteiraUpdated->map(function ($item) use ($valorTotalCarteira) {
            $item->percentual_atual = ($item->valor_total_ativo / $valorTotalCarteira) * 100;
            return $item;
        });

        $minhaCarteiraAtualizada = collect([
            "ativos" => $carteiraComPercentualAtual,
            "valor_total_carteira" => $valorTotalCarteira,
        ]);

        return $minhaCarteiraAtualizada;
    }

    private function getCarteiraComPercentualIdeal()
    {
        $carteiraPercentualAtual = $this->getCarteiraComPercentualAtual();
        $valorTotalCarteira = $carteiraPercentualAtual['valor_total_carteira'];
        $rebalanceamentoAtivo = RebalanceamentoAtivo::where('user_id', auth()->user()->id)->get();

        $carteiraPercentualIdeal = $carteiraPercentualAtual['ativos']
            ->map(function ($ativo) use ($rebalanceamentoAtivo, $valorTotalCarteira) {
                $rebalanceamentoAtivoIdeal = $rebalanceamentoAtivo->where('ativo_id', $ativo->ativo_id)->first();

                $valor = ($valorTotalCarteira * $rebalanceamentoAtivoIdeal->percentual) / 100;
                $quantidade_ativo = $valor / $ativo->cotacao_atual;
                $percentual = $rebalanceamentoAtivoIdeal->percentual;

                $ativo->peso_ideal = [
                    'quantidade_ativo' => $quantidade_ativo,
                    'valor' => $valor,
                    'percentual' => $percentual,
                ];
                
                return $ativo;
            });

        $minhaCarteiraAtualizada = collect([
            "ativos" => $carteiraPercentualIdeal,
            "valor_total_carteira" => $valorTotalCarteira,
        ]);

        return $minhaCarteiraAtualizada;
    }

    private function getCarteiraComPercentualAjuste()
    {
        $carteiraComPercentualIdeal = $this->getCarteiraComPercentualIdeal();

        // Calcula o percentual de ajuste de cada ativo
        $carteiraComPercentualAjuste = $carteiraComPercentualIdeal['ativos']->map(function ($ativo) {
            $valor = $ativo->peso_ideal['valor'] - $ativo->valor_total_ativo;
            $quantidade_ativo = $valor / $ativo->cotacao_atual;
            $percentual = $ativo->peso_ideal['percentual'] - $ativo->percentual_atual;

            $ativo->peso_ajuste = [
                'quantidade_ativo' => $quantidade_ativo,
                'valor' => $valor,
                'percentual' => $percentual,
            ];

            return $ativo;
        });

        $minhaCarteiraAtualizada = collect([
            "ativos" => $carteiraComPercentualAjuste,
            "valor_total_carteira" => $carteiraComPercentualIdeal['valor_total_carteira'],
        ]);

        return $minhaCarteiraAtualizada;
    }

}
