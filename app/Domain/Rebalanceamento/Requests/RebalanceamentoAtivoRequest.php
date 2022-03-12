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
            'percentual' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'ativo_id.required' => 'O campo "Ativo" é obrigatório',
            'ativo_id.unique' => 'Já foi definido uma porcentagem para este ativo',
            'percentual.required' => 'O campo "Porcentagem" é obrigatório',
        ];
    }
}
