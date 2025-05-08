<?php

namespace ApiMultipurpose\Services;

use ApiMultipurpose\Repositories\ConversationRepositoryInterface;
use ApiMultipurpose\Traits\CustomJsonResponse;

class ConversationService implements ConversationServiceInterface
{
    use CustomJsonResponse;

    protected string $contextPluralName = 'Conversations';

    public function __construct(protected ConversationRepositoryInterface $repository) {}

    public function all()
    {
        return $this->execute(fn () => $this->repository->all(), $this->getMethodContext());
    }

    public function getByUser(int $userId)
    {
        return $this->execute(fn () => $this->repository->getByUser($userId), $this->getMethodContext());
    }

    public function find(string $id)
    {
        return $this->execute(fn () => $this->repository->find($id), $this->getMethodContext());
    }

    public function show(string $id, int $userId)
    {
        return $this->execute(fn () => $this->repository->show($id, $userId), $this->getMethodContext());
    }

    public function store(array $data, int $creatorId)
    {
        return $this->execute(fn () => $this->repository->storeWithParticipants($data, $creatorId), $this->getMethodContext());
    }

    public function destroy(string $id)
    {
        return $this->execute(fn () => $this->repository->destroy($id), $this->getMethodContext());
    }
}
