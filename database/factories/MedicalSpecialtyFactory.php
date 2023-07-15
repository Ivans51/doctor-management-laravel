<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class MedicalSpecialtyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $medical_specialties = array(
            "Abdominal Imaging Radiology",
            "Addiction Medicine",
            "Addiction Psychiatry",
            "Adolescent Medicine",
            "Allergy and Immunology",
            "Anesthesiology",
            "Cardiology",
            "Dermatology",
            "Diagnostic Radiology",
            "Emergency Medicine",
            "Family Medicine",
            "Gastroenterology",
            "General Surgery",
            "Geriatrics",
            "Hematology",
            "Infectious Diseases",
            "Internal Medicine",
            "Medical Genetics",
            "Nephrology",
            "Neurology",
            "Nuclear Medicine",
            "Obstetrics and Gynecology",
            "Ophthalmology",
            "Orthopedic Surgery",
            "Otorhinolaryngology (ENT)",
            "Pathology",
            "Pediatrics",
            "Physical Medicine and Rehabilitation",
            "Psychiatry",
            "Pulmonary Medicine",
            "Radiation Oncology",
            "Rheumatology",
            "Urology"
        );

        return [
            'name' => $this->faker->randomElement($medical_specialties),
            'description' => $this->faker->text,
            'price' => $this->faker->randomFloat(2, 0, 1000),
            'currency' => 'USD',
        ];
    }
}
