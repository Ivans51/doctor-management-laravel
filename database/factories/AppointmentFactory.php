<?php

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Schedule;
use App\Utils\Constants;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
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
            'schedule_id' => Schedule::query()->inRandomOrder()->first()->id,
            'payment_id' => Payment::query()->inRandomOrder()->first()->id,
            'status' => $this->faker->randomElement([
                Constants::$PENDING,
                Constants::$APPROVED,
                Constants::$REJECTED,
            ]),
            'description' => $this->faker->text(),
            'created_at' => $this->faker->dateTimeBetween('-1 years', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 years', 'now'),
        ];
    }
}
