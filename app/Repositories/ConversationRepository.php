<?php

namespace ApiMultipurpose\Repositories;

use ApiMultipurpose\Models\Conversation;

class ConversationRepository implements ConversationRepositoryInterface
{
    protected $model = Conversation::class;

    public function all()
    {
        return $this->model::all();
    }

    public function getByUser(int $userId): array
    {
        return $this->model::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->with('users')->get()->toArray();
    }

    public function find(string $id)
    {
        return $this->model::find($id);
    }

    public function store(array $data)
    {
        return $this->model::create($data);
    }

    public function storeWithParticipants(array $data, int $creatorId)
    {
        $conversation = $this->store([
            'type' => $data['type'],
            'created_by' => $creatorId,
        ]);

        $participantIds = array_merge($data['participants'], [$creatorId]);
        $conversation->users()->attach($participantIds);

        return $conversation->load('users');
    }

    public function show(string $id, int $userId)
    {
        return $this->model::where('id', $id)
            ->whereHas('users', fn ($q) => $q->where('user_id', $userId))
            ->with(['users', 'messages'])
            ->first();
    }

    public function destroy(string $id)
    {
        return $this->model::findOrFail($id)->delete();
    }
}
