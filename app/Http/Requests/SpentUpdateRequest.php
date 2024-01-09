<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpentUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'description' => 'string|max:191',
            'spentAt' => 'date|date_format:Y-m-d H:i|before_or_equal:',
            'value' => 'integer|min:1'
        ];
    }

    public function messages()
    {
        return [
            'description.max' => 'A descrição pode ter no máximo 191 caracteres.',

            'spentAt.date' => 'A data não possui formato válido.',
            'spentAt.date_format' => 'A data não possui formato válido.',
            'spentAt.before_or_equal' => 'A data da despesa deve ser menor ou igual a data atual.',

            'value.integer' => 'O valor informado é inválido.',
            'value.min' => 'O valor deve ser maior que 0 (zero).',
        ];
    }
}
