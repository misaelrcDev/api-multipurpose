<?php

namespace ApiMultipurpose\Repositories;

use ApiMultipurpose\Models\User;

class UserRepository implements UserRepositoryInterface
{
    protected $model = User::class;

    public function all()
    {
        return $this->model::all();
    }

    public function find(string $id)
    {
        return $this->model::find($id);
    }

    public function store(array $data)
    {
        return $this->model::create($data);
    }

    public function update(string $id, array $data)
    {
        $update = $this->model::find($id)->update($data);

        if ($update)
           return $this->model::find($id);

        throw new \Exception('User not found');
    }

    public function destroy(string $id)
    {
        return $this->model::find($id)->delete();
    }

}
