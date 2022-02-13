<?php

namespace App\Domain\Ativo\Controllers;

use Inertia\Inertia;
use App\Models\Ativo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ClasseAtivo;

class AtivoController extends Controller
{
    private $perPage = 10;


    public function index(Request $request)
    {
        $request->validate([
            'direction' => ['nullable', 'in:asc,desc'],
            'field' => ['nullable', 'in:codigo,classe_ativo,descricao,setor,created_at'],
            'search' => ['nullable', 'string'],
            'perPage' => ['nullable', 'integer'],
        ]);

        $search = request()->get('search');
        $this->perPage = request()->get('perPage') ?? $this->perPage;
        $field = request()->get('field') ?? 'created_at';
        $direction = request()->get('direction') ?? 'desc';

        $ativos = Ativo::select('ativos.*', 'classes_ativos.nome as classe_ativo')
                    ->join('classes_ativos', 'ativos.classe_ativo_id', '=', 'classes_ativos.id');
                    
        if ($search) {
            $ativos->where('codigo', 'like', "%{$search}%")
            ->orWhere('ativos.descricao', 'like', "%{$search}%")
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

    public function store(Request $request)
    {
        dd($request->all());
    }
}
