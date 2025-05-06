<?php

namespace ApiMultipurpose\Repositories;

interface ConversationRepositoryInterface
{
    public function all();

    public function getByUser(int $userId);

    // public function findPrivateConversation(int $user1, int $user2);

    public function find(string $id);

    public function store(array $data);

    public function destroy(string $id);

}
