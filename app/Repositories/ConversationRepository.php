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

    public function destroy(string $id)
    {
        return $this->model::find($id)->delete();
    }



}
