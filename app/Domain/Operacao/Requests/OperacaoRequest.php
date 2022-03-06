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
        // Link referencia https://stackoverflow.com/questions/2811031/decimal-or-numeric-values-in-regular-expression-validation
        return [
            'ativo' => ['required', 'string'],
            'tipo_operacao' => ['required', 'string'],
            'data_operacao' => ['required', 'date'],
            'cotacao' => ['required', 'regex:/^[0-9][(\d{3})|(\.\d{3})]*(,\d+)?$/'],
            'quantidade' => ['required', 'regex:/^[0-9][(\d{3})|(\.\d{3})]*(,\d+)?$/'],
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
            'cotacao.regex' => 'O campo Cotação não é número válido. ex: 1,00, 1000,00 ou 1.000,00',
            'quantidade.required' => 'O campo Quantidade é obrigatório',
            'quantidade.regex' => 'O campo Quantidade tem que ser número válido. ex: 0,00560060 ou 1,50 ou 1000,00',
            'corretora.required' => 'O campo Corretora é obrigatório',
        ];
    }
}
