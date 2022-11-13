<?php

namespace App\Domain\Dashboard\Controllers;

use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Domain\Dashboard\Services\DashboardService;

class DashboardController extends Controller
{
    private DashboardService $service;

    public function __construct(DashboardService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $carteiraConsolidada = $this->service->listCarteiraConsolidada();

        // Rentabilidade Percentual da Carteira
        $rentabidadeCarteiraHoje = $this->service->listRentabidadeCarteira();
        $rentabidadeCarteira30Dias = $this->service->listRentabidadeCarteira(now()->subDays(30)->format('Y-m-d'));
        $rentabidadeCarteira180Dias = $this->service->listRentabidadeCarteira(now()->subDays(180)->format('Y-m-d'));
        $rentabidadeCarteira365Dias = $this->service->listRentabidadeCarteira(now()->subDays(365)->format('Y-m-d'));

        return Inertia::render('Dashboard/Home', [
            'minhaCarteira' => $carteiraConsolidada['carteiraAtual'],
            'carteiraIdeal' => $carteiraConsolidada['carteiraIdeal'],
            'carteiraAjuste' => $carteiraConsolidada['carteiraAjuste'],
            'minhaCarteiraPorClasses' => $carteiraConsolidada['minhaCarteiraPorClasses'],
            'carteiraIdealPorClasse' => $carteiraConsolidada['carteiraIdealPorClasse'],
            'rentabidadeCarteiraHoje' => $rentabidadeCarteiraHoje,
            'rentabidadeCarteira30Dias' => $rentabidadeCarteira30Dias,
            'rentabidadeCarteira180Dias' => $rentabidadeCarteira180Dias,
            'rentabidadeCarteira365Dias' => $rentabidadeCarteira365Dias,
        ]);
    }
}
