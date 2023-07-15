<?php

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\MedicalSpecialty;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class DoctorMedicalSpecialtyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'doctor_id' => Doctor::query()->inRandomOrder()->first()->id,
            'medical_specialty_id' => MedicalSpecialty::query()->inRandomOrder()->first()->id,
        ];
    }
}
