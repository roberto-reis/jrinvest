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
        // Rebalanceamento por Ativo
        $minhaCarteira = $this->getCarteiraComPercentualAtual();
        $carteiraIdeal = $this->getCarteiraComPercentualIdeal();
        $carteiraAjuste = $this->getCarteiraComPercentualAjuste();

        // Rebalanceamento por Categoria
        $minhaCarteiraPorClasses = $this->getCarteiraComPercentualAtualPorClasse();
        $carteiraIdealPorClasse = $this->getCarteiraComPercentualIdealPorClasse();
        
        

        return Inertia::render('Dashboard/Home', [
            'minhaCarteira' => $minhaCarteira,
            'carteiraIdeal' => $carteiraIdeal,
            'carteiraAjuste' => $carteiraAjuste,
            'minhaCarteiraPorClasses' => $minhaCarteiraPorClasses,
            'carteiraIdealPorClasse' => $carteiraIdealPorClasse,
        ]);
    }

    private function getCarteiraComPercentualAtual()
    {
        $cotacoes = Cotacao::orderBy('created_at', 'desc')->get();
        $minhaCarteira = Carteira::with('ativo')->where('user_id', auth()->user()->id)->get();

        // Atualiza o custo total do ativo com a cotação atual
        $myCarteiraUpdated = $minhaCarteira->map(function ($ativo) use ($cotacoes) {
            $cotacao = $cotacoes->where('ativo_id', $ativo->ativo_id)->first();
            $ativo->cotacao_atual = $cotacao->preco;
            $ativo->valor_ativo = $ativo->quantidade_saldo * $cotacao->preco;
            
            return $ativo;
        });

        // Pega o custo total de todos os ativos atualizados com a cotação atual
        $valorTotalCarteira = $myCarteiraUpdated->sum('valor_ativo');

        // Calcula percentual/peso atual de cada ativo
        $carteiraComPercentualAtual = $myCarteiraUpdated->map(function ($item) use ($valorTotalCarteira) {
            $item->percentual_atual = ($item->valor_ativo / $valorTotalCarteira) * 100;
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
        $minhaCarteira = $this->getCarteiraComPercentualAtual();
        $cotacoes = Cotacao::orderBy('created_at', 'desc')->get();
        $rebalanceamentoAtivo = RebalanceamentoAtivo::with('ativo')->where('user_id', auth()->user()->id)->get();

        // Calcula percentual/peso ideal de cada ativo
        $carteiraIdeal = $rebalanceamentoAtivo->map(function ($ativo) use ($minhaCarteira, $cotacoes) {
            $cotacao = $cotacoes->where('ativo_id', $ativo->ativo_id)->first();
            $valorTotalCarteira = $minhaCarteira['valor_total_carteira'];

            $valor_ativo = ($valorTotalCarteira * $ativo->percentual) / 100; // Calcula o valor do ativo com base no percentual ideal

            $ativo->valor_ativo = $valor_ativo;
            $ativo->quantidade_ativo = $valor_ativo / $cotacao->preco; // Calcula a quantidade do ativo com base na cotação atual
            $ativo->cotacao_atual = $cotacao->preco;

            return $ativo;
        });

        $carteiraIdealComTotal = collect([
            "ativos" => $carteiraIdeal,
            "valor_total_carteira" => $minhaCarteira['valor_total_carteira'],
        ]);

        return $carteiraIdealComTotal;
    }

    private function getCarteiraComPercentualAjuste()
    {
        $minhaCarteira = $this->getCarteiraComPercentualAtual();
        $carteiraIdeal = $this->getCarteiraComPercentualIdeal();

        // Calcula o percentual de ajuste de cada ativo
        $carteiraAjuste = $carteiraIdeal['ativos']->map(function ($ativo) use ($minhaCarteira) {
            $ativoMinhaCarteira = $minhaCarteira['ativos']->where('ativo_id', $ativo->ativo_id)->first(); // Pega o ativo da minha carteira

            if (!is_null($ativoMinhaCarteira)) { // Se o ativo estiver na minha carteira
                $ativo->quantidade_ativo = $ativo->quantidade_ativo - $ativoMinhaCarteira->quantidade_saldo; // Calcula a quantidade de ativos a ser ajustada          
                $ativo->percentual = $ativo->percentual - $ativoMinhaCarteira->percentual_atual; // Calcula o percentual de ajuste
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
