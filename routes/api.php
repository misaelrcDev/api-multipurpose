<?php

use Illuminate\Support\Facades\Route;
use ApiMultipurpose\Http\Controllers\Auth;
use ApiMultipurpose\Http\Controllers\ConversationController;
use ApiMultipurpose\Http\Controllers\MessageController;
use ApiMultipurpose\Http\Controllers\UserController;
// Rotas de autenticação
Route::post('/register', [Auth::class, 'register']);
Route::post('/login', [Auth::class, 'login']);
Route::post('/logout', [Auth::class, 'logout'])->middleware('auth:sanctum');

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
    Route::delete('/{id}', [ConversationController::class, 'destroy']),
]);

// Rotas de mensagem
Route::group(['prefix' => 'messages', 'middleware' => 'auth:sanctum'], fn () => [
    Route::get('/{conversationId}', [MessageController::class, 'index']),
    Route::post('/', [MessageController::class, 'store']),
]);
// Route::get('/conversations', [ConversationController::class, 'index'])->middleware('auth:sanctum');
// Route::post('/conversations', [ConversationController::class, 'store'])->middleware('auth:sanctum');
// Route::get('/conversations{id}', [ConversationController::class, 'show'])->middleware('auth:sanctum');
// Route::delete('/conversations{id}', [ConversationController::class, 'destroy'])->middleware('auth:sanctum');

