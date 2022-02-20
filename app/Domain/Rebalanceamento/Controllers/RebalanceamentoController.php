<?php

namespace App\Domain\Rebalanceamento\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Inertia\Inertia;

class RebalanceamentoController extends Controller
{
    public function index()
    {

        return Inertia::render('Rebalanceamento/Home');
    }
}
