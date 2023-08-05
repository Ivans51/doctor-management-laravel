<?php

namespace Database\Factories;

use App\Models\Chat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $chat = Chat::query()->inRandomOrder()->first();
        return [
            'chat_id' => 1,
            'user_id' => $this->faker->randomElement([$chat->user1_id, $chat->user2_id]),
            'message' => $this->faker->text(100),
        ];
    }
}
