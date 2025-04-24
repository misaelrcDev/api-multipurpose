<?php

namespace ApiMultipurpose\Services;

interface UserServiceInterface
{
    public function all();
    public function find(string $id);

    public function store(array $data);

    public function update(string $id, array $data);

    public function destroy(string $id);
}
