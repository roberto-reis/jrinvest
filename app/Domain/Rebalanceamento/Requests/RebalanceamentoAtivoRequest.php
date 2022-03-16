<?php
namespace App\Domain\Rebalanceamento\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RebalanceamentoAtivoRequest extends FormRequest
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
            'ativo_id' => ['required', 
                Rule::unique('rebalanceamento_ativos')->where('user_id', auth()->user()->id)->ignore($this->id)],
                'percentual' => ['required', 'regex:/^\d+(\.\d{1,2})?$/', 'numeric', 'min:0.01', 'max:100'],
        ];
    }

    public function messages()
    {
        return [
            'ativo_id.required' => 'O campo "Ativo" é obrigatório',
            'ativo_id.unique' => 'Já foi definido uma porcentagem para este ativo',
            'percentual.required' => 'O campo :attribute é obrigatório',
            'percentual.regex' => 'O campo :attribute tem que ser número válido. ex: 1.50 ou 25.50',
            'percentual.min' => 'O campo :attribute deve ser pelo menos :min.',
            'percentual.max' => 'O campo :attribute deve ser no máximo :max.',
        ];
    }
}
