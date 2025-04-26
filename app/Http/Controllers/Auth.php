<?php

namespace ApiMultipurpose\Http\Controllers;

use Illuminate\Http\Request;
use ApiMultipurpose\Models\User;
use Illuminate\Support\Facades\Hash;
use ApiMultipurpose\Http\Requests\LoginRequest;

class Auth extends Controller
{

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

            return response()->json([
                'token' => $user->createToken('token-name')->plainTextToken,
                'userName' => $user->name
            ], 200);
    }


    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function register(Request $request)
    {

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }
}

