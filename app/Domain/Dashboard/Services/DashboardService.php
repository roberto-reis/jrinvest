<?php

namespace App\Domain\Dashboard\Services;

use App\Domain\Main\Interfaces\ICarteiraRepository;

class DashboardService {

    public function __construct(
        private ICarteiraRepository $carteira
    )
    {
    }

    public function listCarteiraConsolidada()
    {
        $carteiraAtual = $this->carteira->getCarteiraComPercentualAtual();
        $carteiraIdeal = $this->carteira->getCarteiraComPercentualIdeal($carteiraAtual);
        $carteiraAjuste = $this->carteira->getCarteiraComPercentualAjuste($carteiraAtual, $carteiraIdeal);

        $carteiraAtualPorClasses = $this->carteira->getCarteiraComPercentualAtualPorClasse($carteiraAtual);
        $carteiraIdealPorClasses = $this->carteira->getCarteiraComPercentualIdealPorClasse($carteiraAtual);

        return [
            'carteiraAtual' => $carteiraAtual,
            'carteiraIdeal' => $carteiraIdeal,
            'carteiraAjuste' => $carteiraAjuste,
            'minhaCarteiraPorClasses' => $carteiraAtualPorClasses,
            'carteiraIdealPorClasse' => $carteiraIdealPorClasses
        ];
    }

    public function listRentabidadeCarteira(string $dataPeriodoRentabilidade = null)
    {
        return $this->carteira->getRentabidadeCarteira($dataPeriodoRentabilidade);
    }
}
