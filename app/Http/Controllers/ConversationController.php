<?php

namespace ApiMultipurpose\Http\Controllers;

use ApiMultipurpose\Models\Conversation;
use ApiMultipurpose\Services\ConversationServiceInterface;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function __construct(protected ConversationServiceInterface $service) {}
    //Listar conversas do usuario
    public function index()
    {
        return $this->service->getByUser(auth()->id());

    }

    // Criar nova conversa
    public function store(Request $request)
    {
        return $this->service->store($request->all(), auth()->id());
    }

    // Detalhes de uma conversa
    public function show($id)
    {
        return $this->service->show($id, auth()->id());
    }

    // Excluir conversa
    public function destroy($id)
    {
        return $this->service->destroy($id);
    }
}
