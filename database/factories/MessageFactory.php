<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use ApiMultipurpose\Models\Message;
use ApiMultipurpose\Models\User;
use ApiMultipurpose\Models\Conversation;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        return [
            'conversation_id' => Conversation::factory(),
            'sender_id' => User::factory(),
            'content' => $this->faker->sentence,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
