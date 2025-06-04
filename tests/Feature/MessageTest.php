<?php

namespace Tests\Feature;

use ApiMultipurpose\Models\User;
use ApiMultipurpose\Models\Conversation;
use ApiMultipurpose\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa se um usuário pode enviar uma mensagem e outro usuário recebe.
     *
     * @return void
     */
    public function test_user_can_send_message_and_another_user_receives()
    {
        // Criar dois usuários
        /** @var \ApiMultipurpose\Models\User $sender */
        $sender = User::factory()->create();
        $receiver = User::factory()->create();

        // Criar conversa e associar usuários
        $conversation = Conversation::factory()->create(['type' => 'private']);
        $conversation->users()->attach([$sender->id, $receiver->id]);

        // Autenticar como o remetente
        $this->actingAs($sender);

        // Dados da mensagem
        $messageData = [
            'conversation_id' => $conversation->id,
            'content' => 'Olá, usuário!'
        ];

        // Enviar mensagem
        $response = $this->postJson('/api/messages', $messageData);

        // $response->dump();
        // Validar resposta
        $response->assertStatus(201)
                 ->assertJsonFragment(['content' => 'Olá, usuário!']);

        // Confirmar no banco de dados
        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'sender_id' => $sender->id,
            'content' => 'Olá, usuário!'
        ]);

        // Agora simular o "recebimento" pelo receiver:
        // Buscar as mensagens associadas à conversa
        $messages = $conversation->messages;

        $this->assertCount(1, $messages);
        $this->assertEquals('Olá, usuário!', $messages->first()->content);
    }

    /**
     * Testa se um usuário pode enviar uma mensagem privada
     *
     * @return void
     */
    public function test_user_can_send_private_message()
    {
        //
        $sender = User::factory()->create();
        $receiver = User::factory()->create();
        $conversation = Conversation::factory()->create(['type' => 'private']);

        $conversation->users()->attach([$sender->id, $receiver->id]);

        /** @var \ApiMultipurpose\Models\User $sender */
        $this->actingAs($sender);

        $messageData = [
            'conversation_id' => $conversation->id,
            'content' => 'Mensagem privada!',
        ];

        $response = $this->postJson('/api/messages', $messageData);

        $response->dump();
        // Validar resposta
        $response->assertStatus(201)
                 ->assertJsonFragment(['content' => 'Mensagem privada!']);

        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'sender_id' => $sender->id,
            'content' => 'Mensagem privada!',
        ]);
    }

    /**
     * Testa se um usuário pode enviar uma mensagem em grupo
     *
     * @return void
     */
    public function test_user_can_send_group_message()
    {
        $sender = User::factory()->create();
        $receiver1 = User::factory()->create();
        $receiver2 = User::factory()->create();
        $conversation = Conversation::factory()->create(['type' => 'group']);

        $conversation->users()->attach([$sender->id, $receiver1->id, $receiver2->id]);

        /** @var \ApiMultipurpose\Models\User $sender */
        $this->actingAs($sender);

        $messageData = [
            'conversation_id' => $conversation->id,
            'content' => 'Mensagem em grupo!',
        ];

        $response = $this->postJson('/api/messages', $messageData);

        $response->dump();
        // Validar resposta
        $response->assertStatus(201)
                 ->assertJsonFragment(['content' => 'Mensagem em grupo!']);

        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'sender_id' => $sender->id,
            'content' => 'Mensagem em grupo!',
        ]);
    }

    // Testa se um usuário não pode enviar mensagem sem autenticação
    // (ou seja, sem estar logado)
    // Isso é importante para garantir que apenas usuários autenticados possam enviar mensagens.
    // Se um usuário tentar enviar uma mensagem sem estar autenticado, o sistema deve retornar um erro 401 (não autorizado).
    public function test_user_cannot_send_message_without_authentication()
    {
        $conversation = Conversation::factory()->create();
        $messageData = ['conversation_id' => $conversation->id, 'content' => 'Mensagem sem autenticação'];

        // Não autenticar usuário antes de enviar a mensagem
        // Isso simula o cenário onde um usuário tenta enviar uma mensagem sem estar logado.
        // O sistema deve retornar um erro 401 (não autorizado) nesse caso.
        $response = $this->postJson('/api/messages', $messageData);

        $response->dump();
        // Validar resposta
        $response->assertStatus(401); // Esperado: não autorizado
    }

    // Testa se uma conversa de grupo pode ter várias mensagens
    // Isso é importante para garantir que as conversas em grupo possam ter múltiplas mensagens de diferentes usuários.
    // Se uma conversa de grupo tiver várias mensagens, o sistema deve permitir que todas sejam armazenadas e recuperadas corretamente.
    // Isso garante que a funcionalidade de mensagens em grupo esteja funcionando conforme o esperado.
    // Além disso, é importante verificar se as mensagens são associadas corretamente à conversa e aos usuários que as enviaram.
    // Isso garante que a lógica de associação entre mensagens, conversas e usuários esteja funcionando corretamente.
    // Se uma conversa de grupo tiver várias mensagens, o sistema deve permitir que todas sejam armazenadas e recuperadas corretamente.
    // Isso garante que a funcionalidade de mensagens em grupo esteja funcionando conforme o esperado.
    public function test_group_conversation_can_have_multiple_messages()
    {
        $sender = User::factory()->create();
        $receiver1 = User::factory()->create();
        $receiver2 = User::factory()->create();
        $conversation = Conversation::factory()->create(['type' => 'group']);

        $conversation->users()->attach([$sender->id, $receiver1->id, $receiver2->id]);

        Message::factory()->create([
            'conversation_id' => $conversation->id,
            'sender_id' => $sender->id,
            'content' => 'Primeira mensagem no grupo!',
        ]);

        Message::factory()->create([
            'conversation_id' => $conversation->id,
            'sender_id' => $receiver1->id,
            'content' => 'Segunda mensagem no grupo!',
        ]);

        $this->assertCount(2, $conversation->messages);
    }

    // Testa se uma conversa pode ter várias mensagens
    public function test_conversation_can_have_multiple_messages()
    {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();
        $conversation = Conversation::factory()->create();

        $conversation->users()->attach([$sender->id, $receiver->id]);

        Message::factory()->create([
            'conversation_id' => $conversation->id,
            'sender_id' => $sender->id,
            'content' => 'Primeira mensagem',
        ]);

        Message::factory()->create([
            'conversation_id' => $conversation->id,
            'sender_id' => $receiver->id,
            'content' => 'Segunda mensagem',
        ]);

        // Verificar se a conversa tem duas mensagens
        $conversation->load('messages'); // Carregar as mensagens associadas à conversa
        $this->assertNotEmpty($conversation->messages);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $conversation->messages);
        $this->assertTrue($conversation->messages->contains(function ($message) use ($sender, $receiver) {
            return $message->sender_id === $sender->id || $message->sender_id === $receiver->id;
        }));
        // Verificar se a conversa tem exatamente duas mensagens
        $this->assertTrue($conversation->messages->contains('content', 'Primeira mensagem'));
        $this->assertTrue($conversation->messages->contains('content', 'Segunda mensagem'));

        $this->assertCount(2, $conversation->messages);
    }

    public function test_user_cannot_view_conversations_of_another_user()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Criar conversa que pertence apenas ao User1
        $conversation = Conversation::factory()->create(['type' => 'private']);
        $conversation->users()->attach([$user1->id]);

        // Autenticar como User2 (que não participa da conversa)
        /** @var \ApiMultipurpose\Models\User $user2 */
        $this->actingAs($user2);

        // Tentar acessar a conversa do User1
        $response = $this->getJson("/api/conversations/{$conversation->id}");

        // Espera-se um erro 403 (Acesso Negado)
        $response->assertStatus(404);
    }

}
