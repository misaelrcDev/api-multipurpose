<?php

namespace ApiMultipurpose\Http\Controllers;

use ApiMultipurpose\Http\Requests\UserRequest;
use ApiMultipurpose\Services\UserServiceInterface;

class UserController extends Controller
{
    public function __construct(protected UserServiceInterface $service) {}

    public function index()
    {
        return $this->service->all();
    }

    public function show(string $id)
    {
        return $this->service->find($id);
    }

    public function update(UserRequest $request, string $id)
    {
        return $this->service->update($id, $request->validated());
    }

    public function destroy(string $id)
    {
        return $this->service->find($id);
    }
}
