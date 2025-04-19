<?php

namespace ApiMultipurpose\Http\Controllers;

use ApiMultipurpose\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class Auth extends Controller
{

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json([
            'token' => $user->createToken('token-name')->plainTextToken,
            'userName' => $user->name
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'logged out']);
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string',
        ]);

        // Check if the user already exists
        $existingUser = User::where('email', $validatedData['email'])->first();
        if ($existingUser) {
            return response()->json([
                'message' => 'User already exists'
            ], 409);
        }
        //Check if the password empty
        if (empty($validatedData['password'])) {
            return response()->json([
                'message' => 'Password is required'
            ], 422);
        }

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        // Automatically log in the user after registration
        $token = $user->createToken('token-name')->plainTextToken;
        return response()->json([
            'token' => $token,
            'userName' => $user->name
        ], 201);
    }

}
