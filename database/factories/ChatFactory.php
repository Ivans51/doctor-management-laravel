<?php

namespace Database\Factories;

use App\Models\User;
use App\Utils\Constants;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Chat>
 */
class ChatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $randomElement = $this->faker->randomElement([Constants::$PATIENT, Constants::$DOCTOR]);
        if ($randomElement === Constants::$PATIENT) {
            $randomElement2 = Constants::$DOCTOR;
        } else {
            $randomElement2 = Constants::$PATIENT;
        }

        return [
            'user1_id' => User::query()->whereHas('roles', function ($query) use ($randomElement) {
                $query->where('name', $randomElement);
            })->inRandomOrder()->first(),
            'user2_id' => User::query()->whereHas('roles', function ($query) use ($randomElement2) {
                $query->where('name', $randomElement2);
            })->inRandomOrder()->first(),
        ];
    }
}
