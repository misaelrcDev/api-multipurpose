<?php

namespace ApiMultipurpose\Http\Controllers;

use ApiMultipurpose\Models\Conversation;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $conversations = $user->conversations; // Relacionamento entre users e conversations

        return response()->json([
            'message' => 'success',
            'data' => $conversations,
        ], 200);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|string|in:private,group',
            'participants' => 'required|array|min:1', // IDs dos participantes
        ]);

        $conversation = Conversation::create([
            'type' => $data['type'],
            'created_by' => auth()->id(),
        ]);

        // Relaciona os participantes à conversa
        $conversation->users()->attach(array_merge($data['participants'], [auth()->id()]));

        return response()->json([
            'message' => 'Conversation created successfully',
            'data' => $conversation,
        ], 201);
    }
}
