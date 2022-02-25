<?php
namespace App\Domain\Rebalanceamento\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RebalanceamentoClasseRequest extends FormRequest
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
            'classe_ativo_id' => ['required', 
                Rule::unique('rebalanceamento_classes')->where('user_id', auth()->user()->id)->ignore($this->id)],
            'porcentagem' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'classe_ativo_id.required' => 'O campo "Classe de ativo" é obrigatório',
            'classe_ativo_id.unique' => 'Já foi definido uma porcentagem para esta classe',
            'porcentagem.required' => 'O campo "Porcentagem" é obrigatório',
        ];
    }
}
