<?php

namespace App\Domain\Ativo\Controllers;

use Inertia\Inertia;
use App\Models\Ativo;
use App\Models\ClasseAtivo;
use Illuminate\Http\Request;
use App\Domain\Ativo\DTO\AtivoDTO;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Domain\Ativo\Requests\AtivoRequest;
use App\Domain\Ativo\Actions\CreateAtivoAction;
use App\Domain\Ativo\Actions\DeleteAtivoAction;
use App\Domain\Ativo\Actions\UpdateAtivoAction;

class AtivoController extends Controller
{
    private $perPage = 10;


    public function index(Request $request)
    {
        $search = $request->get('search');
        $this->perPage = $request->get('perPage', $this->perPage);
        $field = $request->get('field', 'created_at');
        $direction = $request->get('direction', 'desc');

        $ativos = Ativo::select('ativos.*', 'classes_ativos.nome as classe_ativo')
                    ->join('classes_ativos', 'ativos.classe_ativo_id', '=', 'classes_ativos.id');
                    
        if ($search) {
            $ativos->where('codigo', 'like', "%{$search}%")
            ->orWhere('ativos.nome', 'like', "%{$search}%")
            ->orWhere('classes_ativos.nome', 'like', "%{$search}%")
            ->orWhere('setor', 'like', "%{$search}%")
            ->orWhere('ativos.created_at', 'like', "%{$search}%");
        }

        if ($field && $direction) {
            $ativos->orderBy($field, $direction);
        }

        return Inertia::render('Ativos/Home', [
            'ativos' => $ativos->paginate($this->perPage),
            'filters' => [
                'search' => $search,
                'field' => $field,
                'direction' => $direction,
                'perPage' => $this->perPage,
            ],
        ]);
    }

    public function create()
    {
        $classesAtivos = ClasseAtivo::get();

        return Inertia::render('Ativos/Create', [
            'classesAtivos' => $classesAtivos,
        ]);
    }

    public function store(AtivoRequest $AtivoRequest, CreateAtivoAction $createAtivoAction)
    {
        $ativoDTO = AtivoDTO::fromRequest($AtivoRequest);

        try {
            $createAtivoAction($ativoDTO);
            Session::flash('success', 'Ativo cadastrada com sucesso!');            
        } catch (\Exception $e) {
            Log::error('error ao savar ativo: ', [$e->getMessage()]);
        }

        return Redirect::route('ativos.index');

    }

    public function edit($id)
    {
        $ativo = Ativo::find($id);
        $classesAtivos = ClasseAtivo::get();

        if (!$ativo) {
            return Redirect::route('ativos.index');
        }

        return Inertia::render('Ativos/Edit', [
            'ativo' => $ativo,
            'classesAtivos' => $classesAtivos,
        ]);
    }

    public function update(AtivoRequest $AtivoRequest, UpdateAtivoAction $updateAtivoAction)
    {
        $ativoDTO = AtivoDTO::fromRequest($AtivoRequest);

        try {
            $updateAtivoAction($ativoDTO, $AtivoRequest->id);
            Session::flash('success', 'Ativo atualizada com sucesso!');            
        } catch (\Exception $e) {
            Log::error('error ao atualizar ativo: ', [$e->getMessage()]);
        }

        return Redirect::route('ativos.index');
    }

    public function destroy(DeleteAtivoAction $deleteAtivoAction, $id)
    {
        try {
            $deleteAtivoAction($id);
            Session::flash('success', 'Ativo excluída com sucesso!');            
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
            Log::error('error ao excluir ativo: ', [$e->getMessage()]);
        }

        return Redirect::route('ativos.index');
    }
}
