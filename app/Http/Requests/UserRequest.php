<?php

namespace ApiMultipurpose\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name' => 'required|string|min:3|max:80',
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->route('user'),
            'password' => 'nullable|string|min:8',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function message(): array
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'name.string' => 'O campo nome deve ser uma string.',
            'name.min' => 'O campo nome deve ter pelo menos 3 caracteres.',
            'name.max' => 'O campo nome deve ter no máximo 80 caracteres.',
            'email.required' => 'O campo email é obrigatório.',
            'email.string' => 'O campo email deve ser uma string.',
            'email.email' => 'O campo email deve ser um endereço de e-mail válido.',
            'email.max' => 'O campo email deve ter no máximo 255 caracteres.',
            'email.unique' => 'O email já está em uso.',
            // 'password.string' => 'O campo senha deve ser uma string.',
            'password.min' => 'O campo senha deve ter pelo menos 8 caracteres.',
            'avatar.image' => 'O arquivo enviado não é uma imagem válida.',
            'avatar.mimes' => 'A imagem deve ser do tipo: jpeg, png, jpg, gif.',
            'avatar.max' => 'A imagem não pode ter mais de 2MB.'
        ];
    }
}
