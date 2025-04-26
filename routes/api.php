<?php

use Illuminate\Support\Facades\Route;
use ApiMultipurpose\Http\Controllers\Auth;
use ApiMultipurpose\Http\Controllers\UserController;

Route::post('/register', [Auth::class, 'register']);
Route::post('/login', [Auth::class, 'login']);
Route::post('/logout', [Auth::class, 'logout'])->middleware('auth:sanctum');

Route::group(['prefix' => 'users', 'middleware' => 'auth:sanctum'], fn () => [
    Route::get('/', [UserController::class, 'index']),
    Route::get('/{id}', [UserController::class, 'show']),
    Route::put('/{id}', [UserController::class, 'update']),
    Route::delete('/{id}', [UserController::class, 'destroy']),
]);

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
