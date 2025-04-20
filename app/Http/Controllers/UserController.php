<?php

namespace ApiMultipurpose\Http\Controllers;

use ApiMultipurpose\Models\User;
use Illuminate\Http\Request;

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
        // Edit profile
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user->name = $request->input('name', $user->name);
        $user->email = $request->input('email', $user->email);
        $user->password = $request->input('password', $user->password);
        $user->avatar = $request->input('avatar', $user->avatar);
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
