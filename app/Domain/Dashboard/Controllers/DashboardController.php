<?php

namespace App\Domain\Dashboard\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Domain\Carteira\Models\CarteiraConsolidada;
use App\Domain\Carteira\Repositories\CarteiraConsolidadaRepository;
use App\Domain\Carteira\Repositories\CarteiraRepository;

class DashboardController extends Controller
{
    private $carteiraRepository;

    public function __construct(CarteiraRepository $carteiraRepository, CarteiraConsolidadaRepository $carteiraConsolidadaRepository)
    {
        // $this->carteiraRepository = $carteiraRepository;
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
        $minhaCarteiraPorClasses = $this->carteiraConsolidadaRepository->getCarteiraComPercentualAtualPorClasse();
        $carteiraIdealPorClasse = $this->carteiraConsolidadaRepository->getCarteiraComPercentualIdealPorClasse();   
        
        // Rentabilidade Percentual da Carteira
        // $rentabidadeCarteiraHoje = $this->carteiraRepository->rentabidadeCarteira();
        // $rentabidadeCarteira30Dias = $this->carteiraRepository->rentabidadeCarteira($dataUltimos30Dias);
        // $rentabidadeCarteira180Dias = $this->carteiraRepository->rentabidadeCarteira($dataUltimos180Dias);
        // $rentabidadeCarteira365Dias = $this->carteiraRepository->rentabidadeCarteira($dataUltimos365Dias);


        return Inertia::render('Dashboard/Home', [
            'minhaCarteira' => $minhaCarteira,
            'carteiraIdeal' => $carteiraIdeal,
            'carteiraAjuste' => $carteiraAjuste,
            'minhaCarteiraPorClasses' => $minhaCarteiraPorClasses,
            'carteiraIdealPorClasse' => $carteiraIdealPorClasse,
            'rentabidadeCarteiraHoje' => collect([]), // $rentabidadeCarteiraHoje
            'rentabidadeCarteira30Dias' => collect([]), // $rentabidadeCarteira30Dias
            'rentabidadeCarteira180Dias' => collect([]), // $rentabidadeCarteira180Dias
            'rentabidadeCarteira365Dias' => collect([]), // $rentabidadeCarteira365Dias
        ]);
    }

}
