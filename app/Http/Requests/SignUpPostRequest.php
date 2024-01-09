<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignUpPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $this->user,
            'password' => 'required|string|min:8'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O nome deve ser informado.',
            'name.string' => 'O nome precisa ser informado como caractere.',

            'email.required' => 'O email deve ser informado.',
            'email.email' => 'O email informado não é válido.',
            'email.unique' => 'O email informado já existe',

            'password.required' => 'A senha deve ser informada.',
            'password.string' => 'A senha precisa ser informado com caractere.',
            'password.min' => 'A senha precisa ter ao menos 8 caracteres para ser mais segura.'
        ];
    }

    public function attributes(){
        return [
            'name' => 'nome',
            'password' => 'senha'
        ];
    }
}
