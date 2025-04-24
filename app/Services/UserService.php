<?php

namespace ApiMultipurpose\Services;

use ApiMultipurpose\Repositories\UserRepositoryInterface;

class UserService implements UserServiceInterface
{
    public function __construct(private UserRepositoryInterface $repository) {}

    public function all()
    {
        return $this->repository->all();
        if (count($users) === 0) {
            return response()->json(['message' => 'No users found'], 404);
        }

        return response()->json([
            'message' => 'success',
            'data' => $users,
        ], 200);
    }

    public function find(string $id)
    {
        $user = $this->repository->find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json([
            'message' => 'success',
            'data' => $user,
        ], 200);
    }

    public function store(array $data)
    {
        $user = $this->repository->store($data);

        if (!$user) {
            return response()->json(['message' => 'User not created'], 500);
        }

        return response()->json([
            'message' => 'User created successfully',
            'data' => $user,
        ], 201);
    }

    public function update(string $id, array $data)
    {
        $user = $this->repository->update($id, $data);

        if (!$user) {
            return response()->json(['message' => 'User not updated'], 400);
        }

        return response()->json([
            'message' => 'success',
            'data' => $user,
        ], 200);
    }

    public function destroy(string $id)
    {
        $user = $this->repository->destroy($id);

        if (!$user) {
            return response()->json(['message' => 'User not deleted'], 400);
        }

        return response()->json([
            'message' => 'success',
            'data' => $user,
        ], 200);
    }
}

