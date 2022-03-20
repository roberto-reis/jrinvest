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
            'percentual' => ['required', 'regex:/^\d+(\.\d{1,2})?$/', 'numeric', 'min:0.01', 'max:100'],
        ];
    }

    public function messages()
    {
        return [
            'classe_ativo_id.required' => 'O campo "Classe de ativo" é obrigatório',
            'classe_ativo_id.unique' => 'Já foi definido uma porcentagem para esta classe',
            'percentual.required' => 'O campo :attribute é obrigatório',
            'percentual.regex' => 'O campo :attribute tem que ser número válido. ex: 1.50 ou 25.50',
            'percentual.min' => 'O campo :attribute deve ser pelo menos :min.',
            'percentual.max' => 'O campo :attribute deve ser no máximo :max.',
        ];
    }
}
