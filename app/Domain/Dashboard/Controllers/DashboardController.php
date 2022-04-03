<?php

namespace App\Domain\Dashboard\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Domain\Carteira\Repositories\CarteiraConsolidadaRepository;

class DashboardController extends Controller
{
    private $carteiraConsolidadaRepository;

    public function __construct(CarteiraConsolidadaRepository $carteiraConsolidadaRepository)
    {
        $this->carteiraConsolidadaRepository = $carteiraConsolidadaRepository;
    }
    
    public function index()
    {

        $dataUltimos30Dias = Carbon::now()->subDays(30)->format('Y-m-d');
        $dataUltimos180Dias = Carbon::now()->subDays(180)->format('Y-m-d');
        $dataUltimos365Dias = Carbon::now()->subDays(365)->format('Y-m-d');

        // Rebalanceamento por Ativo
        $minhaCarteira = $this->carteiraConsolidadaRepository->getCarteiraComPercentualAtual();
        $carteiraIdeal = $this->carteiraConsolidadaRepository->getCarteiraComPercentualIdeal();
        $carteiraAjuste = $this->carteiraConsolidadaRepository->getCarteiraComPercentualAjuste();

        // Rebalanceamento por Categoria
        $minhaCarteiraPorClasses = $this->carteiraConsolidadaRepository->getCarteiraComPercentualAtualPorClasse($minhaCarteira);
        $carteiraIdealPorClasse = $this->carteiraConsolidadaRepository->getCarteiraComPercentualIdealPorClasse($minhaCarteira);   
        
        // Rentabilidade Percentual da Carteira
        $rentabidadeCarteiraHoje = $this->carteiraConsolidadaRepository->rentabidadeCarteira();
        $rentabidadeCarteira30Dias = $this->carteiraConsolidadaRepository->rentabidadeCarteira($dataUltimos30Dias);
        $rentabidadeCarteira180Dias = $this->carteiraConsolidadaRepository->rentabidadeCarteira($dataUltimos180Dias);
        $rentabidadeCarteira365Dias = $this->carteiraConsolidadaRepository->rentabidadeCarteira($dataUltimos365Dias);


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
