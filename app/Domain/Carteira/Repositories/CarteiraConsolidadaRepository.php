<?php

namespace App\Domain\Carteira\Repositories;

use Illuminate\Support\Collection;
use App\Domain\Cotacao\Models\Cotacao;
use App\Domain\Carteira\Models\CarteiraConsolidada;
use App\Domain\Carteira\Models\RentabilidadeCarteira;
use App\Domain\Rebalanceamento\Models\RebalanceamentoAtivo;
use App\Domain\Rebalanceamento\Models\RebalanceamentoClasse;

class CarteiraConsolidadaRepository
{

    /**
     * Calcula o percentual, quantidade e valor de cada ativo da carteira para o rebalanceamento
     * @return Collection
     */
    public function getCarteiraComPercentualAtual(): Collection
    {        
        $carteiraConsolidada = CarteiraConsolidada::query()->select('corteiras_consolidadas.*', 'ativos.codigo', 'classes_ativos.nome as classe_nome')
                            ->join('ativos', 'ativos.id', '=', 'corteiras_consolidadas.ativo_id')
                            ->join('classes_ativos', 'classes_ativos.id', '=', 'ativos.classe_ativo_id')
                            ->where('user_id', auth()->user()->id)
                            ->orderBy('ativos.codigo', 'asc')->get();

        $minhaCarteiraAtualizada = collect([
            "ativos" => $carteiraConsolidada,
            "valor_total_carteira" => $carteiraConsolidada->sum('valor_total_ativo'),
            "custo_total_carteira" => $carteiraConsolidada->sum('custo_total_ativo'),
        ]);

        return $minhaCarteiraAtualizada;
    }

    /**
     * Calcula o percentual, quantidade e valor ideal de cada ativo para ter na carteira
     * @return Collection
     */
    public function getCarteiraComPercentualIdeal(): Collection
    {
        // Recebe a carteira com percentual atual calculado
        $minhaCarteiraConsolidada = $this->getCarteiraComPercentualAtual();

        $rebalanceamentoAtivo = RebalanceamentoAtivo::select('rebalanceamento_ativos.*', 'ativos.codigo', 'classes_ativos.nome as classe_nome')
            ->join('ativos', 'ativos.id', '=', 'rebalanceamento_ativos.ativo_id')
            ->join('classes_ativos', 'classes_ativos.id', '=', 'ativos.classe_ativo_id')
            ->where('user_id', auth()->user()->id)
            ->orderBy('ativos.codigo', 'asc')
            ->get();

        $dataHojeMenos5Dias = date('Y-m-d', strtotime('-5 day'));
        $cotacoes = Cotacao::query()->where('preco', '>', 0)
                                    ->whereDate('created_at', '>=', $dataHojeMenos5Dias)
                                    ->orderBy('created_at', 'desc')->get();

        if ($minhaCarteiraConsolidada->isEmpty() || is_null($minhaCarteiraConsolidada)) {
            return collect();
        }

        // Calcula percentual/peso ideal de cada ativo
        $carteiraIdealConsolidada = $rebalanceamentoAtivo->map(function ($carteiraIdeal) use ($minhaCarteiraConsolidada, $cotacoes) {
            $cotacao = $cotacoes->where('ativo_id', $carteiraIdeal->ativo_id)->first()->preco;
            $valorTotalCarteira = $minhaCarteiraConsolidada['valor_total_carteira'];
            
            $valor_total_ativo = ($valorTotalCarteira * $carteiraIdeal->percentual) / 100; // Calcula o valor do ativo com base no percentual ideal
            $quantidade_ativo = $valor_total_ativo / $cotacao; // Calcula a quantidade do ativo com base na cotação atual

            $carteiraIdeal->valor_total_ativo = $valor_total_ativo;
            $carteiraIdeal->quantidade_ativo = $quantidade_ativo;
            $carteiraIdeal->cotacao = $cotacao;

            return $carteiraIdeal;
        });

        $carteiraIdealComTotal = collect([
            "ativos" => $carteiraIdealConsolidada,
            "valor_total_carteira" => $minhaCarteiraConsolidada['valor_total_carteira'],
        ]);

        return $carteiraIdealComTotal;
    }

    /**
     * Calcula o percentual, quantidade e valor de cada ativo da carteira para o rebalanceamento
     * @return Collection
     */
    public function getCarteiraComPercentualAjuste(): Collection
    {
        // Recebe a carteira com percentual atual calculado
        $minhaCarteiraConsolidada = $this->getCarteiraComPercentualAtual();
        // Recebe a carteira com percentual ideal calculado
        $carteiraIdealConsolidada = $this->getCarteiraComPercentualIdeal();
        
        if ($minhaCarteiraConsolidada->isEmpty() || $carteiraIdealConsolidada->isEmpty()) {
            return collect();
        }

        // Calcula o percentual de ajuste de cada ativo
        $carteiraAjuste = $carteiraIdealConsolidada['ativos']->map(function ($ativo) use ($minhaCarteiraConsolidada) {
            $ativoMinhaCarteira = $minhaCarteiraConsolidada['ativos']->where('ativo_id', $ativo->ativo_id)->first(); // Pega o ativo da minha carteira

            if (!is_null($ativoMinhaCarteira)) {
                $ativo->quantidade_ativo = $ativo->quantidade_ativo - $ativoMinhaCarteira->quantidade_saldo; // Calcula a quantidade de ativos a ser ajustada          
                $ativo->percentual = $ativo->percentual - $ativoMinhaCarteira->percentual; // Calcula o percentual de ajuste
                $ativo->valor_total_ativo = $ativo->valor_total_ativo - $ativoMinhaCarteira->valor_total_ativo; // Calcula o valor de ajuste
            }

            return $ativo;
        });

        $carteiraComPercentualAjuste = collect([
            "ativos" => $carteiraAjuste,
            "valor_total_carteira" => $carteiraIdealConsolidada['valor_total_carteira'],
        ]);

        return $carteiraComPercentualAjuste;
    }

    /**
     * Calcula o percentual atual de cada classe de ativo
     * @return Collection
     */
    public function getCarteiraComPercentualAtualPorClasse($carteiraComPercentualAtual): Collection
    {
        // Recebe a carteira com percentual atual calculado
        $minhaCarteiraConsolidada = $carteiraComPercentualAtual;

        if ($minhaCarteiraConsolidada->isEmpty()) {
            return collect();
        }
        
        $minhaCarteiraGrouped = $minhaCarteiraConsolidada['ativos']->groupBy('classe_nome');
        $minhaCarteiraPorClasses = $minhaCarteiraGrouped->map(function ($ativos, $index) use ($minhaCarteiraConsolidada) {
            $totalCarteira = $minhaCarteiraConsolidada['valor_total_carteira'];
            $percentual =  ($ativos->sum('valor_total_ativo') / $totalCarteira) * 100;
            return [
                'classe_ativo' => $index,
                'valor_total_classe' => $ativos->sum('valor_total_ativo'),
                'percentual' => number_format($percentual, 2, '.', ''),
            ];
        });

        return $minhaCarteiraPorClasses;
    }

    /**
     * Calcula e retorna o percentual ideal de cada classe de ativo
     * @return Collection
     */
    public function getCarteiraComPercentualIdealPorClasse($carteiraComPercentualAtual): Collection
    {
        // Recebe a carteira com percentual atual calculado
        $minhaCarteiraConsolidada = $carteiraComPercentualAtual;

        $carteiraIdealPorClasses = RebalanceamentoClasse::query()->select('rebalanceamento_classes.*', 'classes_ativos.nome as classe_ativo')
            ->join('classes_ativos', 'classes_ativos.id', '=', 'rebalanceamento_classes.classe_ativo_id')
            ->where('user_id', auth()->user()->id)
            ->orderBy('classe_ativo')
            ->get();

        if ($minhaCarteiraConsolidada->isEmpty() || $minhaCarteiraConsolidada->isEmpty()) {
            return collect();
        }
        
        $minhaCarteiraPorClasses = $carteiraIdealPorClasses->map(function ($classe) use ($minhaCarteiraConsolidada) {
            $totalCarteira = $minhaCarteiraConsolidada['valor_total_carteira'];
            $valorTotalClasse = ($totalCarteira * $classe->percentual) / 100;
            return [
                'classe_ativo' => $classe->classe_ativo,
                'valor_total_classe' => $valorTotalClasse,
                'percentual' => $classe->percentual,
            ];
        });

        return $minhaCarteiraPorClasses;
    }

    /**
     * calcula e retorna a rentabilidade total da carteira conforme periodo informado
     * @param ?string $dataperiodoRentabilidade
     * @return array
     */
    public function rentabidadeCarteira(string $dataPeriodoRentabilidade = null)
    {
        $rentabidadeCarteiraModelo = RentabilidadeCarteira::query()
                                    ->select('custo_total_carteira', 'rentabilidade_valor', 'rentabilidade_percentual')
                                        ->where('user_id', auth()->user()->id);

        if (is_null($dataPeriodoRentabilidade)) { //  Se não for passado nenhum dia, pega a cotação mais recente
            $rentabidadeCarteira = $rentabidadeCarteiraModelo->orderBy('created_at', 'desc')->first();

        } else { // Se for passado a data, pega a cotação mais recente antes da data passada
            $rentabidadeCarteira = $rentabidadeCarteiraModelo->whereDate('created_at', '>=', $dataPeriodoRentabilidade)
                            ->orderBy('created_at', 'asc')
                            ->first();
        }

        if (is_null($rentabidadeCarteira)) {
            return collect();
        }

        return $rentabidadeCarteira; 
    }
}