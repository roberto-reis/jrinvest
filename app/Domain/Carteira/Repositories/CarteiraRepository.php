<?php
namespace App\Domain\Carteira\Repositories;

use App\Domain\Carteira\DTO\CarteiraDTO;
use Carbon\Carbon;
use App\Domain\Cotacao\Models\Cotacao;
use App\Domain\Carteira\Models\Carteira;
use App\Domain\Main\Interfaces\ICarteiraRepository;
use App\Domain\Operacao\Models\Operacao;
use App\Domain\Rebalanceamento\Models\RebalanceamentoAtivo;
use App\Domain\Rebalanceamento\Models\RebalanceamentoClasse;

class CarteiraRepository implements ICarteiraRepository
{
    /**
     * Calcula o percentual, quantidade e valor de cada ativo da carteira para o rebalanceamento
     * @param ?string $dataPeriodoRentabilidade
     * @return array
     */
    public function getCarteiraComPercentualAtual(string $dataPeriodoRentabilidade = null): array
    {
        $cotacoesModelo = Cotacao::query();
        $minhaCarteira = Carteira::select('carteiras.*', 'ativos.codigo', 'classes_ativos.nome as classe_nome')
                            ->join('ativos', 'ativos.id', '=', 'carteiras.ativo_id')
                            ->join('classes_ativos', 'classes_ativos.id', '=', 'ativos.classe_ativo_id')
                            ->where('user_id', auth()->user()->id)
                            ->orderBy('ativos.codigo', 'asc')->get();

        if (is_null($dataPeriodoRentabilidade)) {
            $cotacoesModelo->where('preco', '>', 0)
                           ->orderBy('created_at', 'desc');
        } else {
            $cotacoesModelo->whereDate('created_at', '>=', $dataPeriodoRentabilidade)
                           ->where('preco', '>', 0)
                           ->orderBy('created_at', 'asc');
        }

        $cotacoes = $cotacoesModelo->get();

        if ($cotacoes->isEmpty() || $minhaCarteira->isEmpty()) {
            return [];
        }

        // Calcula o valor do ativo com a cotação atual
        $myCarteiraAtualizada = $minhaCarteira->map(function ($ativo) use ($cotacoes) {
            $cotacao = $cotacoes->firstWhere('ativo_id', $ativo->ativo_id);
            $ativoDto = CarteiraDTO::fromArray($ativo->toArray());

            $ativoDto->cotacao = $cotacao->preco;
            $ativoDto->valor_ativo = $ativoDto->quantidade_saldo * $cotacao->preco;

            return $ativoDto;
        });

        // Pega o custo total de todos os ativos atualizados com a cotação atual
        $valorTotalCarteira = $myCarteiraAtualizada->sum('valor_ativo');
        $custoTotalCarteira = $myCarteiraAtualizada->sum('custo_total_ativo');

        // Calcula percentual e rentabilidade de cada ativo
        $carteiraComPercentualAtual = $myCarteiraAtualizada->map(function ($ativo) use ($valorTotalCarteira) {
            $ativo->percentual = ($ativo->valor_ativo / $valorTotalCarteira) * 100; // Porcentagem do ativo na carteira
            $ativo->rentabilidade_valor = ($ativo->valor_ativo - $ativo->custo_total_ativo); // calcula a rentabilidade do ativo em valor
            $ativo->rentabilidade_percentual = ($ativo->rentabilidade_valor / $ativo->custo_total_ativo) * 100; // calcula a rentabilidade em

            return $ativo;
        });

        return [
            "ativos" => $carteiraComPercentualAtual,
            "valor_total_carteira" => $valorTotalCarteira,
            "custo_total_carteira" => $custoTotalCarteira,
        ];
    }

    /**
     * Calcula o percentual, quantidade e valor ideal de cada ativo para ter na carteira
     * @param ?array $carteiraAtual
     * @return array
     */
    public function getCarteiraComPercentualIdeal(array $carteiraAtual = null): array
    {
        $minhaCarteira = $carteiraAtual ?? $this->getCarteiraComPercentualAtual();

        $cotacoes = Cotacao::where('preco', '>', 0)->orderBy('created_at', 'desc')->get();
        $rebalanceamentoAtivo = RebalanceamentoAtivo::select('rebalanceamento_ativos.*', 'ativos.codigo', 'classes_ativos.nome as classe_nome')
            ->join('ativos', 'ativos.id', '=', 'rebalanceamento_ativos.ativo_id')
            ->join('classes_ativos', 'classes_ativos.id', '=', 'ativos.classe_ativo_id')
            ->where('user_id', auth()->user()->id)
            ->orderBy('ativos.codigo', 'asc')
            ->get();

        if (empty($minhaCarteira) || $cotacoes->isEmpty() || $rebalanceamentoAtivo->isEmpty()) {
            return [];
        }

        // Calcula percentual/peso ideal de cada ativo
        $carteiraIdeal = $rebalanceamentoAtivo->map(function ($ativo) use ($minhaCarteira, $cotacoes) {
            $cotacao = $cotacoes->firstWhere('ativo_id', $ativo->ativo_id);
            $ativoDto = CarteiraDTO::fromArray($ativo->toArray());

            $valorTotalCarteira = (float) $minhaCarteira['valor_total_carteira'];
            $valor_ativo = ($valorTotalCarteira * $ativoDto->percentual) / 100; // Calcula o valor do ativo com base no percentual ideal
            $quantidade_ativo = $valor_ativo / $cotacao->preco; // Calcula a quantidade do ativo com base na cotação atual

            $ativoDto->valor_ativo = $valor_ativo;
            $ativoDto->quantidade_ativo = $quantidade_ativo;
            $ativoDto->cotacao = $cotacao->preco;

            return $ativoDto;
        });

        return [
            "ativos" => $carteiraIdeal,
            "valor_total_carteira" => $minhaCarteira['valor_total_carteira'],
        ];
    }

    /**
     * Calcula o percentual, quantidade e valor de cada ativo da carteira para o rebalanceamento
     * @param ?array $carteiraAtual
     * @param ?array $carteiraIdeal
     * @return array
     */
    public function getCarteiraComPercentualAjuste(array $carteiraAtual = null, array $carteiraIdeal = null): array
    {
        $minhaCarteira = $carteiraAtual ?? $this->getCarteiraComPercentualAtual();
        $carteiraIdeal = $carteiraIdeal ?? $this->getCarteiraComPercentualIdeal();

        if (empty($minhaCarteira) || empty($carteiraIdeal)) {
            return [];
        }

        // Calcula o percentual de ajuste de cada ativo
        $carteiraAjuste = $carteiraIdeal['ativos']->map(function ($ativo) use ($minhaCarteira) {
            $ativoMinhaCarteira = $minhaCarteira['ativos']->firstWhere('ativo_id', $ativo->ativo_id); // Pega o ativo da minha carteira
            $ativoAjuste = clone $ativo;

            if (!is_null($ativoMinhaCarteira)) {
                $ativoAjuste->quantidade_ativo = $ativoAjuste->quantidade_ativo - $ativoMinhaCarteira->quantidade_saldo; // Calcula a quantidade de ativos a ser ajustada
                $ativoAjuste->percentual = $ativoAjuste->percentual - $ativoMinhaCarteira->percentual; // Calcula o percentual de ajuste
                $ativoAjuste->valor_ativo = $ativoAjuste->valor_ativo - $ativoMinhaCarteira->valor_ativo; // Calcula o valor de ajuste
            }

            return $ativoAjuste;
        });

        return [
            "ativos" => $carteiraAjuste,
            "valor_total_carteira" => $carteiraIdeal['valor_total_carteira'],
        ];
    }

    /**
     * Calcula o percentual atual de cada classe de ativo
     * @param ?array $carteiraAtual
     * @return array
     */
    public function getCarteiraComPercentualAtualPorClasse(array $carteiraAtual = null): array
    {
        $minhaCarteira = $carteiraAtual ?? $this->getCarteiraComPercentualAtual();

        if (empty($minhaCarteira)) {
            return [];
        }

        $minhaCarteiraGrouped = $minhaCarteira['ativos']->groupBy('classe_nome');
        $minhaCarteiraPorClasses = $minhaCarteiraGrouped->map(function ($ativos, $key) use ($minhaCarteira) {
            $totalCarteira = (float) $minhaCarteira['valor_total_carteira'];
            $percentual =  ($ativos->sum('valor_ativo') / $totalCarteira) * 100;
            return [
                'classe_ativo' => $key,
                'valor_total_classe' => $ativos->sum('valor_ativo'),
                'percentual' => number_format($percentual, 2, '.', ''),
            ];
        });

        return $minhaCarteiraPorClasses->toArray();
    }

    /**
     * Calcula e retorna o percentual ideal de cada classe de ativo
     * @param ?array $carteiraAtual
     * @return array
     */
    public function getCarteiraComPercentualIdealPorClasse(array $carteiraAtual = null): array
    {
        $minhaCarteira = $carteiraAtual ?? $this->getCarteiraComPercentualAtual();
        $carteiraIdealPorClasses = RebalanceamentoClasse::select('rebalanceamento_classes.*', 'classes_ativos.nome as classe_ativo')
            ->join('classes_ativos', 'classes_ativos.id', '=', 'rebalanceamento_classes.classe_ativo_id')
            ->where('user_id', auth()->user()->id)
            ->orderBy('classe_ativo')
            ->get();

        if (empty($minhaCarteira) || $carteiraIdealPorClasses->isEmpty()) {
            return [];
        }

        $minhaCarteiraPorClasses = $carteiraIdealPorClasses->map(function ($classe) use ($minhaCarteira) {
            $totalCarteira = (float) $minhaCarteira['valor_total_carteira'];
            $valorTotalClasse = ($totalCarteira * $classe->percentual) / 100;
            return [
                'classe_ativo' => $classe->classe_ativo,
                'valor_total_classe' => $valorTotalClasse,
                'percentual' => $classe->percentual,
            ];
        });

        return $minhaCarteiraPorClasses->toArray();
    }

    /**
     * calcula e retorna a rentabilidade total da carteira conforme periodo informado
     * @param ?string $dataperiodoRentabilidade
     * @return array
     */
    public function getRentabidadeCarteira(string $dataPeriodoRentabilidade = null): array
    {
        $dataOperacoesMaisAntiga = Operacao::orderBy('data_operacao', 'asc')->first();
        $minhaCarteira = $this->getCarteiraComPercentualAtual($dataPeriodoRentabilidade);

        if (is_null($dataOperacoesMaisAntiga) || empty($minhaCarteira)) {
            return [];
        }

        $dataOperacoesMaisAntiga = Carbon::parse($dataOperacoesMaisAntiga->created_at)->format('Y-m-d');
        if (!is_null($dataPeriodoRentabilidade) && $dataPeriodoRentabilidade < $dataOperacoesMaisAntiga) {
            $dataPeriodoRentabilidade = $dataOperacoesMaisAntiga;
        }

        $valorTotalCarteira = (float) $minhaCarteira['valor_total_carteira'];
        $custoTotalCarteira = (float) $minhaCarteira['custo_total_carteira'];

        $rentabilidadeValor = ($valorTotalCarteira - $custoTotalCarteira);

        return [
            'valor_total_carteira' => $valorTotalCarteira,
            'custo_total_carteira' => $custoTotalCarteira,
            'rentabilidade_valor' => $rentabilidadeValor,
            'rentabilidade_percentual' => ($rentabilidadeValor / $custoTotalCarteira) * 100,
        ];
    }
}
