<?php

namespace ApiMultipurpose\Repositories;

interface ConversationRepositoryInterface
{
    public function all();
    public function getByUser(int $userId);
    public function find(string $id);
    public function store(array $data);
    public function storeWithParticipants(array $data, int $creatorId);
    public function show(string $id, int $userId);
    public function destroy(string $id);
}
