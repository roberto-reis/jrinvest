<?php

namespace App\Domain\Operacao\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OperacaoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ativo' => ['required'],
            'tipo_operacao' => ['required', 'string'],
            'data_operacao' => ['required', 'date'],
            'cotacao' => ['required', 'numeric'],
            'quantidade' => ['required'],
            'corretora' => ['required', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'ativo.required' => 'O campo Ativo é obrigatório',
            'tipo_operacao.required' => 'O campo Tipo de Operação é obrigatório',
            'data_operacao.required' => 'O campo Data de Operação é obrigatório',
            'cotacao.required' => 'O campo Cotação é obrigatório',
            'cotacao.numeric' => 'O campo Cotação tem que ser um número',
            'quantidade.required' => 'O campo Quantidade é obrigatório',
            'quantidade.numeric' => 'O campo Quantidade tem que ser um número',
            'corretora.required' => 'O campo Corretora é obrigatório',
        ];
    }
}
