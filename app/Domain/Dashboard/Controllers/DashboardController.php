<?php

namespace App\Domain\Dashboard\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

        $dataUltimos30Dias = Carbon::now()->subDays(30)->format('Y-m-d');
        $dataUltimos180Dias = Carbon::now()->subDays(180)->format('Y-m-d');
        $dataUltimos365Dias = Carbon::now()->subDays(365)->format('Y-m-d');

        // Rebalanceamento por Ativo
        $minhaCarteira = $this->carteiraRepository->getCarteiraComPercentualAtual();

        // dd($minhaCarteira['ativos']->toArray());

        $carteiraIdeal = $this->carteiraRepository->getCarteiraComPercentualIdeal();

        
        $carteiraAjuste = $this->carteiraRepository->getCarteiraComPercentualAjuste();

        

        // Rebalanceamento por Categoria
        $minhaCarteiraPorClasses = $this->carteiraRepository->getCarteiraComPercentualAtualPorClasse();
        $carteiraIdealPorClasse = $this->carteiraRepository->getCarteiraComPercentualIdealPorClasse();   
        
        // Rentabilidade Percentual da Carteira
        $rentabidadeCarteiraHoje = $this->carteiraRepository->rentabidadeCarteira();
        $rentabidadeCarteira30Dias = $this->carteiraRepository->rentabidadeCarteira($dataUltimos30Dias);
        $rentabidadeCarteira180Dias = $this->carteiraRepository->rentabidadeCarteira($dataUltimos180Dias);
        $rentabidadeCarteira365Dias = $this->carteiraRepository->rentabidadeCarteira($dataUltimos365Dias);


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
