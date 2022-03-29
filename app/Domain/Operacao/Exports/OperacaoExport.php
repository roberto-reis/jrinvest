<?php

namespace App\Domain\Operacao\Exports;

use App\Domain\Operacao\Models\Operacao;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class OperacaoExport implements FromCollection, ShouldAutoSize, WithMapping, WithHeadings
{
    use Exportable;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $operacoes = Operacao::query()->select('operacoes.*', 'ativos.codigo as codigo_ativo', 'classes_ativos.nome as classe_ativo')
                    ->join('ativos', 'operacoes.ativo_id', '=', 'ativos.id')
                    ->join('classes_ativos', 'ativos.classe_ativo_id', '=', 'classes_ativos.id')
                    ->where('user_id', auth()->user()->id)->get();

        return $operacoes;
    }

    public function headings(): array
    {
        return [
            'Ativo',
            'Classe de ativo',
            'Tipo operação',
            'Corretora',
            'Cotacao preco',
            'Quantidade',
            'Valor Total',
            'Data Operação',
        ];
    }

    public function map($operacao): array
    {
        return [
            $operacao->codigo_ativo,
            $operacao->classe_ativo,
            $operacao->tipo_operacao,
            $operacao->corretora,
            $operacao->quantidade,
            $operacao->cotacao_preco,
            $operacao->valor_total,
            Carbon::parse($operacao->data_operacao)->format('d/m/Y'),
        ];
    }
    
}
