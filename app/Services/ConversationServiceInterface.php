<?php

namespace ApiMultipurpose\Services;

use Illuminate\Http\Request;

interface ConversationServiceInterface
{
    public function all();
    public function getByUser(int $userId);
    public function find(string $id);
    public function show(string $id, int $userId);
    public function store(array $data, int $creatorId);
    public function destroy(string $id);
}
