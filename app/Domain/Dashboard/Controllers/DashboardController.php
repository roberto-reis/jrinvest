<?php

namespace App\Domain\Dashboard\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domain\Cotacao\Models\Cotacao;
use App\Domain\Carteira\Models\Carteira;
use App\Domain\Carteira\Repositories\CarteiraRepository;
use Carbon\Carbon;

class DashboardController extends Controller
{
    private $carteiraRepository;

    public function __construct(CarteiraRepository $carteiraRepository)
    {
        $this->carteiraRepository = $carteiraRepository;
    }
    
    public function index()
    {

        $dataSub30Days = Carbon::now()->subDays(30)->format('Y-m-d');

        


        // Rebalanceamento por Ativo
        $minhaCarteira = $this->carteiraRepository->getCarteiraComPercentualAtual();
        $carteiraIdeal = $this->carteiraRepository->getCarteiraComPercentualIdeal();
        $carteiraAjuste = $this->carteiraRepository->getCarteiraComPercentualAjuste();

        // Rebalanceamento por Categoria
        $minhaCarteiraPorClasses = $this->carteiraRepository->getCarteiraComPercentualAtualPorClasse();
        $carteiraIdealPorClasse = $this->carteiraRepository->getCarteiraComPercentualIdealPorClasse();   
        
        // Rentabilidade Percentual da Carteira
        $rentabidadeCarteiraHoje = $this->rentabidadeCarteira();
        $rentabidadeCarteira30Dias = $this->rentabidadeCarteira(30);


        return Inertia::render('Dashboard/Home', [
            'minhaCarteira' => $minhaCarteira,
            'carteiraIdeal' => $carteiraIdeal,
            'carteiraAjuste' => $carteiraAjuste,
            'minhaCarteiraPorClasses' => $minhaCarteiraPorClasses,
            'carteiraIdealPorClasse' => $carteiraIdealPorClasse,
            'rentabidadeCarteiraHoje' => $rentabidadeCarteiraHoje,
        ]);
    }


    public function rentabidadeCarteira(int $dias = null)
    {
        $dataCotacao = null;

        if (!is_null($dias)) {
            $dataCotacao = Carbon::now()->subDays($dias)->format('Y-m-d');
        }

        $minhaCarteira = $this->carteiraRepository->getCarteiraComPercentualAtual($dataCotacao);
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

    public function getCarteiraComPercentualAtual(string $dataCotacao = null)
    {
        $dataAgora = Carbon::now()->format('Y-m-d');
        $dataSub1Day = Carbon::now()->subDays(1)->format('Y-m-d');
        $dataSub30Days = Carbon::now()->subDays(30)->format('Y-m-d');
        $dataNowSub6Months = Carbon::now()->subMonths(6)->format('Y-m-d');
        $dataNowSub1Year = Carbon::now()->subYears(1)->format('Y-m-d');

        $cotacoesModelo = Cotacao::query();

        if (is_null($dataCotacao)) { //  Se não for passado nenhum dia, pega o dia de hoje até um mês atrás
            $cotacoesModelo->where('preco', '>', 0)
                        ->whereDate('created_at', '>=', $dataSub30Days)
                        ->orderBy('created_at', 'desc');
                        
        } else { // Se for passado a data, pega da data passada até agora
            $cotacoesModelo->where('preco', '>', 0)
                        ->whereDate('created_at', '>=', $dataCotacao)
                        ->orderBy('created_at', 'asc');
        }

        $cotacoes = $cotacoesModelo->get();
        dd($cotacoes->toArray());
        $minhaCarteira = Carteira::with('ativo')->where('user_id', auth()->user()->id)->get();

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
