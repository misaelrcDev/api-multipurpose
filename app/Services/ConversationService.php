<?php

namespace ApiMultipurpose\Services;

use ApiMultipurpose\Repositories\ConversationRepositoryInterface;
use Illuminate\Support\Facades\Validator;

class ConversationService implements ConversationServiceInterface
{
    public function __construct(protected ConversationRepositoryInterface $repository) {}

    public function all()
    {
        $conversations = $this->repository->all();

        return response()->json([
            'message' => 'success',
            'data' => $conversations,
        ], 200);
    }

    public function getByUser(int $userId)
    {
        $conversation = $this->repository->getByUser($userId);

        if (count($conversation) === 0) {
            return response()->json(['message' => 'No conversations found'], 404);
        }
        return response()->json([
            'message' => 'success',
            'data' => $conversation,
        ], 200);
    }

    public function find(string $id)
    {
        return $this->repository->find($id);
    }

    public function show(string $id, int $userId)
    {
        $conversation = $this->repository->find($id);

        if (!$conversation) {
            return response()->json(['message' => 'Conversation not found'], 404);
        }

        // Garante que o usuário participa da conversa
        if (!$conversation->users()->where('user_id', $userId)->exists()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'message' => 'success',
            'data' => $conversation->load('users', 'messages'),
        ]);
    }

    public function store(array $data, int $creatorId)
    {
        $conversation = $this->repository->store([
            'type' => $data['type'],
            'created_by' => $creatorId,
        ]);

        $participantIds = array_merge($data['participants'], [$creatorId]);
        $conversation->users()->attach($participantIds);

        return response()->json([
            'message' => 'Conversation created successfully',
            'data' => $conversation,
        ], 201);
    }

    public function destroy(string $id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:conversations,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 400);
        }

        $conversation = $this->repository->find($id);

        if (!$conversation) {
            return response()->json(['message' => 'Conversation not found'], 404);
        }

        $this->repository->destroy($id);

        return response()->json([
            'message' => 'Convesation deleted successfully',
        ], 200);
    }
}
