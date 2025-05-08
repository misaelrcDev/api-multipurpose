<?php

namespace ApiMultipurpose\Http\Controllers;

use ApiMultipurpose\Http\Requests\ConversationRequest;
use ApiMultipurpose\Http\Requests\DestroyConversationRequest;
use ApiMultipurpose\Services\ConversationServiceInterface;

class ConversationController extends Controller
{
    public function __construct(protected ConversationServiceInterface $service) {}
    //Listar conversas do usuario
    public function index()
    {
        return $this->service->getByUser(auth()->id());

    }
    // Criar nova conversa
    public function store(ConversationRequest $request)
    {
        return $this->service->store($request->validated(), auth()->id());
    }
    // Detalhes de uma conversa
    public function show($id)
    {
        return $this->service->show($id, auth()->id());
    }
    // Excluir conversa
    public function destroy(DestroyConversationRequest $request,  string $conversation)
    {
        return $this->service->destroy($conversation);
    }
}
