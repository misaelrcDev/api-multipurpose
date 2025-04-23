<?php

namespace ApiMultipurpose\Http\Controllers;

use ApiMultipurpose\Models\User;
use ApiMultipurpose\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function __construct(protected UserRepository $userRepository)
    {
    }

    public function index()
    {
        return $this->userRepository->all();

    }

    public function show(string $id)
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($user);
    }

    public function update(Request $request, string $id)
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Validate request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'nullable|string|min:8',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update user
        $data = $request->all();
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar) {
                Storage::delete($user->avatar);
            }
            // Store new avatar
            $data['avatar'] = $request->file('avatar')->store('avatars');
        }
        $this->userRepository->update($id, $data);

        return response()->json(['message' => 'User updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Delete avatar
        if ($user->avatar) {
            Storage::delete($user->avatar);
        }

        // Delete user
        $this->userRepository->destroy($id);

        return response()->json(['message' => 'User deleted successfully']);
    }
}
