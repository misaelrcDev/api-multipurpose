<?php

namespace ApiMultipurpose\Http\Controllers;

use ApiMultipurpose\Http\Requests\UserRequest;
use ApiMultipurpose\Services\UserServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function __construct(protected UserServiceInterface $service)
    {
    }

    public function index()
    {
        return $this->service->all();

    }

    public function show(string $id)
    {
        $this->service->find($id);

    }

    public function update(UserRequest $request, string $id)
    {
        return $this->service->update($id, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->service->find($id);

    }
}
