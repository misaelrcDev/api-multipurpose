<?php

namespace ApiMultipurpose\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Auth\Access\AuthorizationException;
use ApiMultipurpose\Models\Conversation;

class DestroyConversationRequest extends FormRequest
{
    public function authorize(): bool
    {
        $conversationId = $this->route('conversation');

        return Conversation::where('id', $conversationId)
            ->whereHas('users', function ($query) {
                $query->where('user_id', auth()->id());
            })->exists();
    }

    public function rules(): array
    {
        return [
            'id' => 'required|exists:conversations,id',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'O ID da conversa é obrigatório.',
            'id.exists' => 'A conversa especificada não existe.',
        ];
    }

    public function validationData()
    {
        return array_merge($this->all(), [
            'id' => $this->route('conversation'),
        ]);
    }

    protected function failedAuthorization()
    {
        throw new AuthorizationException('Você não tem permissão para excluir esta conversa.');
    }
}

