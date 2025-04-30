<?php

namespace ApiMultipurpose\Http\Controllers;

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
}
