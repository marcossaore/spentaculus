<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SignInPostRequest extends FormRequest
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
            'email' => 'required|email|',
            'password' => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'O email deve ser informado.',
            'email.email' => 'O email informado não é válido.',

            'password.required' => 'A senha deve ser informada.',
            'password.string' => 'A senha precisa ser informado com caractere.',
        ];
    }

    public function attributes(){
        return [
            'name' => 'nome',
            'password' => 'senha',
        ];
    }
}
