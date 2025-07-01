<?php

namespace ApiMultipurpose\Http\Controllers;

use Illuminate\Http\Request;
use ApiMultipurpose\Models\User;
use Illuminate\Support\Facades\Hash;
use ApiMultipurpose\Http\Requests\LoginRequest;
use ApiMultipurpose\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->session()->regenerate(); // ← ESSENCIAL para que a sessão seja válida após login

        return response()->json(Auth::user());
    }


    // public function login(LoginRequest $request)
    // {
    //     $user = User::where('email', $request->email)->first();

    //     if (!$user || !Hash::check($request->password, $user->password)) {
    //         return response()->json(['message' => 'Unauthorized'], 401);
    //     }

    //         return response()->json([
    //             'token' => $user->createToken('token-name')->plainTextToken,
    //             'userName' => $user->name
    //         ], 200);
    // }


    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logout realizado com sucesso.']);
    }


    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        Auth::login($user); // loga o usuário após o registro
        $request->session()->regenerate(); // inicia uma sessão segura

        return response()->json($user);
    }
}

