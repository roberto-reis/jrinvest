<?php

namespace App\Domain\Rebalanceamento\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\RebalanceamentoAtivo;
use App\Models\RebalanceamentoClasse;
use Inertia\Inertia;

class RebalanceamentoController extends Controller
{
    public function index()
    {
        $ativoRebalanceamentos = RebalanceamentoAtivo::get();
        $classeRebalanceamentos = RebalanceamentoClasse::get();

        return Inertia::render('Rebalanceamento/Home', [
            'ativoRebalanceamentos' => $ativoRebalanceamentos,
            'classeRebalanceamentos' => $classeRebalanceamentos,
        ]);
    }
}
