<?php

namespace App\Domain\Dashboard\Controllers;

use Inertia\Inertia;
use App\Models\Cotacao;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {

        $cotacoes = Cotacao::get();

        



        return Inertia::render('Dashboard/Home', [
            'minhaCarteira' => $minhaCarteira = collect(),
        ]);
    }

}
