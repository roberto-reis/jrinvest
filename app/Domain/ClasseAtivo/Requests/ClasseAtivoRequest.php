<?php
namespace App\Domain\ClasseAtivo\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ClasseAtivoRequest extends FormRequest
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
            'nome' => ['required', 'string', Rule::unique('classes_ativos')->ignore($this->id)],
            'descricao' => ['required', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'nome.required' => 'O campo "Classe de ativo" é obrigatório',
            'nome.unique' => 'Esta Classe de ativo já foi cadastrada',
            'descricao.required' => 'O campo "Descricao" é obrigatório',
        ];
    }
}
