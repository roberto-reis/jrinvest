<?php

namespace App\Domain\Operacao\Imports;

use App\Domain\Ativo\Models\Ativo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Domain\Operacao\Models\Operacao;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class OperacaoImport implements ToModel, WithHeadingRow
{
    use Importable;

    protected $ativos;

    public function __construct()
    {
        $this->ativos = Ativo::get();
    }
    
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Operacao([
            'user_id' => Auth::user()->id,
            'tipo_operacao' => $row['operacao'] == 'C' ? 'compra' : 'venda',
            'ativo_id' => $this->ativos->where('codigo', $row['codigo'])->first()->id,
            'data_operacao' => Date::excelToDateTimeObject($row['data'])->format('Y/m/d'),
            'quantidade' => $row['quantidade'],
            'cotacao_preco' => $row['preco'] ?? '0.0',
            'corretora' => 'Sistema',
        ]);
    }
}
