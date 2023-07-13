<?php

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\Role;
use App\Models\User;
use App\Utils\Constants;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\HigherOrderBuilderProxy;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Doctor>
 */
class DoctorFactory extends Factory
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
            'speciality' => $this->faker->word(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'photo' => $this->faker->word(),
            'status' => $this->faker->randomElement([
                Constants::$ACTIVE,
                Constants::$INACTIVE
            ]),
            'created_at' => $this->faker->dateTime(),
            'updated_at' => $this->faker->dateTime(),
        ];
    }
}
