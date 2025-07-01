<?php

use Illuminate\Support\Facades\Route;
use ApiMultipurpose\Http\Controllers\Auth;
use ApiMultipurpose\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/login', function () {
//     return response()->json(['message' => 'Página de login']);
// })->name('login');

// Route::post('/api/login', [AuthController::class, 'login']);

