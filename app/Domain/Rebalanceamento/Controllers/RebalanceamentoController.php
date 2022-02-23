<?php

namespace App\Domain\Rebalanceamento\Controllers;

use Inertia\Inertia;
use App\Models\Ativo;
use App\Models\ClasseAtivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\RebalanceamentoAtivo;
use App\Models\RebalanceamentoClasse;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Domain\Rebalanceamento\DTO\RebalanceamentoClasseDTO;
use App\Domain\Rebalanceamento\Actions\CreateRebalanceamentoClasse;
use App\Domain\Rebalanceamento\Requests\RebalanceamentoClasseRequest;

class RebalanceamentoController extends Controller
{
    public function index()
    {

        $ativos = Ativo::get();
        $classeAtivos = ClasseAtivo::get();

        $classeRebalanceamentos = RebalanceamentoClasse::with('classeAtivo')->where('user_id', auth()->user()->id)->get();
        $ativoRebalanceamentos = RebalanceamentoAtivo::with('ativo')->where('user_id', auth()->user()->id)->get();

        return Inertia::render('Rebalanceamento/Home', [
            'classeRebalanceamentos' => $classeRebalanceamentos,
            'ativoRebalanceamentos' => $ativoRebalanceamentos,
            'ativos' => $ativos,
            'classeAtivos' => $classeAtivos
        ]);
    }

    public function porcentagemClasseStore(RebalanceamentoClasseRequest $request, CreateRebalanceamentoClasse $createRebalanceamentoClasse)
    {
        $rebalanceamentoClasseDTO = RebalanceamentoClasseDTO::fromRequest($request);
        try {
            $createRebalanceamentoClasse($rebalanceamentoClasseDTO);            
            Session::flash('success', 'Rebalanceamento cadastrada com sucesso!');            
        } catch (\Exception $e) {            
            Log::error('Rebalanceamento por classe de ativo: ', [$e->getMessage()]);
        }

        return Redirect::route('rebalanceamento.index');   
    }
}
