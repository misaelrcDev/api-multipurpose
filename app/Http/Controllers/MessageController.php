<?php

namespace ApiMultipurpose\Http\Controllers;

use ApiMultipurpose\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    // Listar mensagens de uma conversa
    public function index($conversationId)
    {
        $messages = Message::where('conversation_id', $conversationId)->get();

        return response()->json([
            'message' => 'success',
            'data' => $messages,
        ], 200);
    }

    // Enviar mensagem
    public function store(Request $request)
    {
        $data = $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'content' => 'required|string',
        ]);

        $message = Message::create([
            'conversation_id' => $data['conversation_id'],
            'sender_id' => auth()->id(),
            'content' => $data['content'],
        ]);

        return response()->json([
            'message' => 'Message sent successfully',
            'data' => $message,
        ], 201);
    }
}
