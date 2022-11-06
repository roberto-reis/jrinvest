<?php

namespace App\Domain\Operacao\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
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

        Excel::import(new OperacaoImport(), $file);

        Session::flash('success', 'Excel importado com sucesso!');

        return redirect()->route('operacoes.import');
    }

    public function getImportModelo()
    {
        $pathFile = Storage::path('public/download/import-operacoes.xlsx');

        $headers = [
            'Content-Type: application/xlsx',
        ];

        return response()->download($pathFile, 'import-operacoes.xlsx', $headers);
    }

}


