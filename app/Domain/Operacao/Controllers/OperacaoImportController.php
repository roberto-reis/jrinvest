<?php

namespace App\Domain\Operacao\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use App\Domain\Operacao\Imports\OperacaoImport;

class OperacaoImportController extends Controller
{
    public function index()
    {
        return Inertia::render('Operacoes/Import');
    }

    public function store(Request $request)
    {
        $request->validate([
            'arquivo' => 'required|file|mimes:xlsx,xls|max:2048',
        ]);

        $file = $request->file('arquivo');

        Excel::import(new OperacaoImport, $file);

        Session::flash('success', 'Excel importado com sucesso!'); 

        return redirect()->route('operacoes.import');
    }
}


