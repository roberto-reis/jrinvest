<?php

namespace App\Domain\Main\Interfaces;

use Illuminate\Support\Collection;

interface ICarteiraRepository
{
    public function getCarteiraComPercentualAtual(string $dataPeriodoRentabilidade = null): array;
    public function getCarteiraComPercentualIdeal(array $carteiraAtual = null): array;
    public function getCarteiraComPercentualAjuste(array $carteiraAtual = null, array $carteiraIdeal = null): array;
    public function getCarteiraComPercentualAtualPorClasse(array $carteiraAtual = null): array;
    public function getCarteiraComPercentualIdealPorClasse(array $carteiraAtual = null): array;
    public function getRentabidadeCarteira(string $dataPeriodoRentabilidade = null): array;
}
