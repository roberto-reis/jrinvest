<?php

namespace App\Domain\Dashboard\Controllers;

use Inertia\Inertia;
use App\Models\Ativo;
use App\Models\Cotacao;
use App\Models\Operacao;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domain\Cotacao\Services\CotacaoService;
use App\Jobs\CotacaoJob;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        $cotacao = new CotacaoService();

        $ativos = Ativo::get();
        
        


        return Inertia::render('Dashboard/Home');
    }

    public function RentabilidadeDia()
    {
        $operacoes = Operacao::get();
    }
}
