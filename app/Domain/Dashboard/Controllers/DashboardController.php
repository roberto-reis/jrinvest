<?php

namespace App\Domain\Dashboard\Controllers;

use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Domain\Main\Interfaces\ICarteiraService;

class DashboardController extends Controller
{
    private ICarteiraService $carteira;

    public function __construct(ICarteiraService $carteira)
    {
        $this->carteira = $carteira;
    }

    public function index()
    {
        // Rebalanceamento por Ativo
        $minhaCarteira = $this->carteira->getCarteiraComPercentualAtual();
        $carteiraIdeal = $this->carteira->getCarteiraComPercentualIdeal($minhaCarteira);
        $carteiraAjuste = $this->carteira->getCarteiraComPercentualAjuste($minhaCarteira, $carteiraIdeal);
        // Rebalanceamento por Categoria
        $minhaCarteiraPorClasses = $this->carteira->getCarteiraComPercentualAtualPorClasse($minhaCarteira);
        $carteiraIdealPorClasse = $this->carteira->getCarteiraComPercentualIdealPorClasse($minhaCarteira);

        // Rentabilidade Percentual da Carteira
        $rentabidadeCarteiraHoje = $this->carteira->getRentabidadeCarteira();
        $rentabidadeCarteira30Dias = $this->carteira->getRentabidadeCarteira(now()->subDays(30)->format('Y-m-d'));
        $rentabidadeCarteira180Dias = $this->carteira->getRentabidadeCarteira(now()->subDays(180)->format('Y-m-d'));
        $rentabidadeCarteira365Dias = $this->carteira->getRentabidadeCarteira(now()->subDays(365)->format('Y-m-d'));


        return Inertia::render('Dashboard/Home', [
            'minhaCarteira' => $minhaCarteira,
            'carteiraIdeal' => $carteiraIdeal,
            'carteiraAjuste' => $carteiraAjuste,
            'minhaCarteiraPorClasses' => $minhaCarteiraPorClasses,
            'carteiraIdealPorClasse' => $carteiraIdealPorClasse,
            'rentabidadeCarteiraHoje' => $rentabidadeCarteiraHoje,
            'rentabidadeCarteira30Dias' => $rentabidadeCarteira30Dias,
            'rentabidadeCarteira180Dias' => $rentabidadeCarteira180Dias,
            'rentabidadeCarteira365Dias' => $rentabidadeCarteira365Dias,
        ]);
    }

}
