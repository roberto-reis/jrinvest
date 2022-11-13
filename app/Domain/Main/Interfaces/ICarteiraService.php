<?php

namespace App\Domain\Main\Interfaces;

use Illuminate\Support\Collection;

interface ICarteiraService
{
    public function getCarteiraComPercentualAtual(string $dataPeriodoRentabilidade = null): Collection;
    public function getCarteiraComPercentualIdeal(Collection $carteiraAtual = null): Collection;
    public function getCarteiraComPercentualAjuste(Collection $carteiraAtual = null, Collection $carteiraIdeal = null): Collection;
    public function getCarteiraComPercentualAtualPorClasse(Collection $carteiraAtual = null): Collection;
    public function getCarteiraComPercentualIdealPorClasse(Collection $carteiraAtual = null): Collection;
    public function getRentabidadeCarteira(string $dataPeriodoRentabilidade = null): array;
}
