<?php

namespace Database\Factories;

use App\Models\User;
use App\Utils\Constants;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'gender' => $this->faker->randomElement([
                Constants::$MALE,
                Constants::$FEMALE
            ]),
            'status' => $this->faker->randomElement([
                Constants::$ACTIVE,
                Constants::$INACTIVE
            ]),
            'user_id' => User::query()->inRandomOrder()->first()->id,
            'created_at' => $this->faker->dateTime(),
            'updated_at' => $this->faker->dateTime(),
        ];
    }
}
