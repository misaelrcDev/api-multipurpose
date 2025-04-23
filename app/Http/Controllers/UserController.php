<?php

namespace ApiMultipurpose\Http\Controllers;

use ApiMultipurpose\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all users
        $users = User::all();

        // Return the users as a JSON response
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Fetch a user by ID
        $user = User::find($id);

        // Check id the user exists
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        // Return the user as a JSON response
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the request data
        $request->validate([
            'name' => 'string|max:255',
            'email' => 'string|email|max:255',
            'avatar' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
        ]);
        // Edit profile
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Processamento do avatar
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');

            // Verifica se o avatar atual existe antes de deletar
            if (!is_null($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Salva o novo arquivo
            $path = $file->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->name = $request->input('name', $user->name);
        $user->email = $request->input('email', $user->email);
        $user->save();
        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Delete user
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}
