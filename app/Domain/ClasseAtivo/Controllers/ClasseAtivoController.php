<?php

namespace App\Domain\ClasseAtivo\Controllers;

use App\Domain\Ativo\Actions\UpdateAtivoAction;
use Inertia\Inertia;
use App\Models\ClasseAtivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Domain\ClasseAtivo\DTO\ClasseAtivoDTO;
use App\Domain\ClasseAtivo\Requests\ClasseAtivoRequest;
use App\Domain\ClasseAtivo\Actions\CreateClasseAtivoAction;

class ClasseAtivoController extends Controller
{
    private $perPage = 10;

    public function index(Request $request)
    {
        $request->validate([
            'direction' => ['nullable', 'in:asc,desc'],
            'field' => ['nullable', 'in:nome,descricao,created_at'],
            'search' => ['nullable', 'string'],
            'perPage' => ['nullable', 'integer'],
        ]);

        $search = $request->get('search');
        $this->perPage = $request->get('perPage') ?? $this->perPage;
        $field = $request->get('field') ?? 'created_at';
        $direction = $request->get('direction') ?? 'desc';

        $classesAtivos = ClasseAtivo::query();
                    
        if ($classesAtivos) {
            $classesAtivos->where('nome', 'like', "%{$search}%")
            ->orWhere('descricao', 'like', "%{$search}%")
            ->orWhere('created_at', 'like', "%{$search}%");
        }

        if ($field && $direction) {
            $classesAtivos->orderBy($field, $direction);
        }

        return Inertia::render('ClasseAtivo/Home', [
            'classesAtivos' => $classesAtivos->paginate($this->perPage),
            'filters' => [
                'search' => $search,
                'field' => $field,
                'direction' => $direction,
                'perPage' => $this->perPage,
            ],
        ]);
    }

    public function store(ClasseAtivoRequest $classeAtivoRequest, CreateClasseAtivoAction $createClasseAtivoAction)
    {
        $classeAtivo = ClasseAtivoDTO::fromRequest($classeAtivoRequest);

        try {
            $createClasseAtivoAction($classeAtivo);
            Session::flash('success', 'Classe de Ativo cadastrada com sucesso!');            
        } catch (\Exception $e) {
            Log::error('error ao savar Classe de Ativo: ', [$e->getMessage()]);
        }

        return Redirect::route('classe_ativo.index');        
    }

    public function update(ClasseAtivoRequest $classeAtivoRequest, UpdateAtivoAction $updateAtivoAction)
    {
        $classeAtivo = ClasseAtivoDTO::fromRequest($classeAtivoRequest);

        try {
            $updateAtivoAction($classeAtivo, $classeAtivoRequest->id);
            Session::flash('success', 'Classe de Ativo atualizada com sucesso!');
        } catch (\Exception $e) {
            Log::error('error ao atualizar Classe de Ativo: ', [$e->getMessage()]);
        }

        return Redirect::route('classe_ativo.index');
    }
}
