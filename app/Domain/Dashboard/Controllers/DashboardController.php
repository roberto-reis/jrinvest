<?php

namespace App\Domain\Dashboard\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Operacao;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard/Home');
    }

    public function RentabilidadeDia()
    {
        $operacoes = Operacao::get();
    }
}
