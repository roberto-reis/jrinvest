<?php
namespace App\Domain\Ativo\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AtivoRequest extends FormRequest
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
            'codigo' => ['required', 'string', Rule::unique('ativos')->ignore($this->id)],
            'nome' => ['required', 'string'],
            'setor' => ['required', 'string'],
            'classe_ativo' => ['required', 'string']
        ];
    }

    public function messages()
    {
        return [
            'codigo.required' => 'O campo "Codigo" é obrigatório',
            'codigo.unique' => 'Este ativo já está cadastrado',
            'nome.required' => 'O campo "Nome" é obrigatório',
            'setor.required' => 'O campo "Setor" é obrigatório',
            'classe_ativo.required' => 'O campo "Cotação" é obrigatório',
        ];
    }
}
