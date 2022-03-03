<?php

namespace App\Domain\Dashboard\Controllers;

use Inertia\Inertia;
use App\Models\Operacao;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ativo;
use App\Models\Cotacao;

class DashboardController extends Controller
{
    public function index()
    {

        $cotacoes = Cotacao::get();

        $operacoesAtivos = Operacao::select('operacoes.*', 'ativos.codigo as codigo_ativo')
            ->join('ativos', 'operacoes.ativo_id', '=', 'ativos.id')
            ->where('user_id', auth()->user()->id)->get();

        // dd($operacoesAtivos->groupBy('codigo_ativo'));

        $minhaCarteira = collect();
        foreach ($operacoesAtivos->groupBy('codigo_ativo') as $operacao) {
            $operacaoNew = $operacao->first();
            // dd($operacaoNew->toArray());
            $cotacao = $cotacoes->where('ativo_id', $operacaoNew->ativo_id)->first();
            $operacaoVendas = $operacao->where('tipo_operacao', 'venda')->sum('quantidade');
            $operacaoCompras = $operacao->where('tipo_operacao', 'compra')->sum('quantidade');
            $operacaoValorTotal = $operacao->where('tipo_operacao', 'compra')->sum('valor_total');

            $minhaCarteira->push([
                'ativo_id' => $operacaoNew->ativo_id,
                'codigo_ativo' => $operacaoNew->codigo_ativo,
                'quantidade_saldo' => $operacaoCompras - $operacaoVendas,
                'valor_total' => ($operacaoCompras - $operacaoVendas) * $cotacao->preco,
                'cotacao' => $cotacao->preco,
                'preco_medio' => $operacaoValorTotal / $operacaoCompras,
            ]);
        }

        // dd($minhaCarteira);

        // $minhaCarteira = $operacoesAtivos->groupBy('codigo_ativo')->map(function ($operacao) use ($cotacoes) {
        //     $operacaoNew = $operacao->first();
        //     $cotacao = $cotacoes->where('ativo_id', $operacaoNew->ativo_id)->first();
        //     $operacaoVendas = $operacao->where('tipo_operacao', 'venda')->sum('quantidade');
        //     $operacaoCompras = $operacao->where('tipo_operacao', 'compra')->sum('quantidade');

        //     return [
        //         'ativo_id' => $operacaoNew->ativo_id,
        //         'codigo_ativo' => $operacaoNew->codigo_ativo,
        //         'quantidade_saldo' => $operacaoCompras - $operacaoVendas,
        //         'valor_total' => ($operacaoCompras - $operacaoVendas) * $cotacao->preco,
        //         'cotacao' => $cotacao->preco,
        //     ];
        // });
        // dd($minhaCarteira->sum('valor_total'));



        return Inertia::render('Dashboard/Home', [
            'minhaCarteira' => $minhaCarteira,
        ]);
    }

    public function RentabilidadeDia()
    {
        $operacoes = Operacao::get();
    }

}
