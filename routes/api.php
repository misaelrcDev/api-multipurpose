<?php

use Illuminate\Support\Facades\Route;
use ApiMultipurpose\Http\Controllers\Auth;
use ApiMultipurpose\Http\Controllers\AuthController;
use ApiMultipurpose\Http\Controllers\ConversationController;
use ApiMultipurpose\Http\Controllers\MessageController;
use ApiMultipurpose\Http\Controllers\UserController;
use ApiMultipurpose\Http\Resources\UserResource;
use Illuminate\Http\Request;

// Rotas de autenticação
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1'); // Limita a 5 tentativas de login por minuto
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return new UserResource($request->user());
});


// Rotas de Usuario
Route::group(['prefix' => 'users', 'middleware' => 'auth:sanctum'], fn () => [
    Route::get('/', [UserController::class, 'index']),
    Route::get('/{id}', [UserController::class, 'show']),
    Route::put('/{user}', [UserController::class, 'update']),
    Route::delete('/{id}', [UserController::class, 'destroy']),
]);

// Rotas de conversa
Route::group(['prefix' => 'conversations', 'middleware' => 'auth:sanctum'], fn () => [
    Route::get('/', [ConversationController::class, 'index']),
    Route::post('/', [ConversationController::class, 'store']),
    Route::get('/{id}', [ConversationController::class, 'show']),
    Route::delete('/{conversation}', [ConversationController::class, 'destroy']),
]);

// Rotas de mensagem
Route::group(['prefix' => 'messages', 'middleware' => 'auth:sanctum'], fn () => [
    Route::get('/{conversationId}', [MessageController::class, 'index']),
    Route::post('/', [MessageController::class, 'store']),
]);


