<?php
namespace App\Domain\Carteira\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Domain\Cotacao\Models\Cotacao;
use App\Domain\Carteira\Models\Carteira;
use App\Domain\Operacao\Models\Operacao;
use App\Domain\Rebalanceamento\Models\RebalanceamentoAtivo;
use App\Domain\Rebalanceamento\Models\RebalanceamentoClasse;

class CarteiraRepository
{
    
    /**
     * Calcula o percentual, quantidade e valor de cada ativo da carteira para o rebalanceamento
     * @param ?string $dataPeriodoRentabilidade
     * @return Collection
     */
    public function getCarteiraComPercentualAtual(string $dataPeriodoRentabilidade = null): Collection
    {
        $minhaCarteira = Carteira::query()->select('carteiras.*', 'ativos.codigo', 'classes_ativos.nome as classe_nome')
            ->join('ativos', 'ativos.id', '=', 'carteiras.ativo_id')
            ->join('classes_ativos', 'classes_ativos.id', '=', 'ativos.classe_ativo_id')
            ->where('user_id', auth()->user()->id)
            ->orderBy('ativos.codigo', 'asc')
            ->get();

        $cotacoesModelo = Cotacao::query();

        if (is_null($dataPeriodoRentabilidade)) { //  Se não for passado nenhum dia, pega a cotação mais recente
            $cotacoesModelo->where('preco', '>', 0)
                        ->orderBy('created_at', 'desc');
                        
        } else { // Se for passado a data, pega a cotação mais recente antes da data passada
            $cotacoesModelo->where('preco', '>', 0)
                        ->whereDate('created_at', '>=', $dataPeriodoRentabilidade)
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

    /**
     * Calcula o percentual, quantidade e valor ideal de cada ativo para ter na carteira
     * @return Collection
     */
    public function getCarteiraComPercentualIdeal(): Collection
    {
        $minhaCarteira = $this->getCarteiraComPercentualAtual();
        $rebalanceamentoAtivo = RebalanceamentoAtivo::select('rebalanceamento_ativos.*', 'ativos.codigo', 'classes_ativos.nome as classe_nome')
            ->join('ativos', 'ativos.id', '=', 'rebalanceamento_ativos.ativo_id')
            ->join('classes_ativos', 'classes_ativos.id', '=', 'ativos.classe_ativo_id')
            ->where('user_id', auth()->user()->id)
            ->orderBy('ativos.codigo', 'asc')
            ->get();

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

    /**
     * Calcula o percentual, quantidade e valor de cada ativo da carteira para o rebalanceamento
     * @return Collection
     */
    public function getCarteiraComPercentualAjuste(): Collection
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

    /**
     * Calcula o percentual atual de cada classe de ativo
     * @return Collection
     */
    public function getCarteiraComPercentualAtualPorClasse(): Collection
    {
        $minhaCarteira = $this->getCarteiraComPercentualAtual();
        $minhaCarteiraGrouped = $minhaCarteira['ativos']->groupBy('classe_nome');

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

    /**
     * Calcula e retorna o percentual ideal de cada classe de ativo
     * @return Collection
     */
    public function getCarteiraComPercentualIdealPorClasse(): Collection
    {
        $minhaCarteira = $this->getCarteiraComPercentualAtual();
        $carteiraIdealPorClasses = RebalanceamentoClasse::query()->select('rebalanceamento_classes.*', 'classes_ativos.nome as classe_ativo')
            ->join('classes_ativos', 'classes_ativos.id', '=', 'rebalanceamento_classes.classe_ativo_id')
            ->where('user_id', auth()->user()->id)
            ->orderBy('classe_ativo')
            ->get();
        
        $minhaCarteiraPorClasses = $carteiraIdealPorClasses->map(function ($classe) use ($minhaCarteira) {
            $totalCarteira = $minhaCarteira['valor_total_carteira'];
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
    public function rentabidadeCarteira(string $dataPeriodoRentabilidade = null): array
    {
        $dataOperacoesMaisAntiga = Operacao::orderBy('created_at', 'asc')->first()->created_at;
        $dataOperacoesMaisAntiga = Carbon::parse($dataOperacoesMaisAntiga)->format('Y-m-d');

        if (!is_null($dataPeriodoRentabilidade) && $dataPeriodoRentabilidade < $dataOperacoesMaisAntiga) {
            $dataPeriodoRentabilidade = $dataOperacoesMaisAntiga;
        }

        $minhaCarteira = $this->getCarteiraComPercentualAtual($dataPeriodoRentabilidade);
        $valorTotalCarteira = $minhaCarteira['valor_total_carteira'];
        $custoTotalCarteira = $minhaCarteira['custo_total_carteira'];

        $rentabilidadeValor = ($valorTotalCarteira - $custoTotalCarteira);
        $rentabilidadePercentual = ($rentabilidadeValor / $custoTotalCarteira) * 100;

        return [
            'valor_total_carteira' => $valorTotalCarteira,
            'custo_total_carteira' => $custoTotalCarteira,
            'rentabilidade_valor' => $rentabilidadeValor,
            'rentabilidade_percentual' => $rentabilidadePercentual,
        ];     
    }
}