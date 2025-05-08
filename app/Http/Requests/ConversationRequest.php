<?php

namespace ApiMultipurpose\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConversationRequest extends FormRequest
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
        if ($this->isMethod('DELETE')) {
            return [
                'id' => 'required|exists:conversations,id',
            ];
        }

        return [
            'type' => 'required|string|in:group,private',
            'participants' => 'required|array|min:1',
            'participants.*' => 'exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'O ID da conversa é obrigatório.',
            'id.exists' => 'A conversa especificada não existe.',
            'type.required' => 'O campo tipo é obrigatório.',
            'type.in' => 'O tipo deve ser "group" ou "private".',
            'participants.required' => 'A conversa precisa ter participantes.',
            'participants.min' => 'A conversa deve ter pelo menos um participante.',
            'participants.*.exists' => 'Um ou mais participantes não existem no sistema.',
        ];
    }
}
