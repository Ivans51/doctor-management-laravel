<?php

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\Patient;
use App\Utils\Constants;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'patient_id' => Patient::query()->inRandomOrder()->first()->id,
            'doctor_id' => Doctor::query()->inRandomOrder()->first()->id,
            'amount' => $this->faker->numberBetween(100, 1000),
            'payment_method' => Constants::$PAYMENT_METHODS[array_rand(Constants::$PAYMENT_METHODS)],
            'payment_status' => Constants::$PAYMENT_STATUS_PAID,
            'payment_date' => $this->faker->dateTimeBetween('-1 years', 'now'),
            'created_at' => $this->faker->dateTimeBetween('-1 years', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 years', 'now'),
        ];
    }
}
