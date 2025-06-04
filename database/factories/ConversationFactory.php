<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use ApiMultipurpose\Models\Conversation;

class ConversationFactory extends Factory
{
    protected $model = Conversation::class;

    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(['group', 'private']),
            'created_by' => \ApiMultipurpose\Models\User::factory()->create()->id,
        ];
    }
}
