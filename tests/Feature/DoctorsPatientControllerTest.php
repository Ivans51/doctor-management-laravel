<?php

namespace Tests\Feature;

use App\Models\MedicalSpecialty;
use App\Utils\Constants;
use App\Utils\Testing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DoctorsPatientControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        (new Testing())->createFakeUserWithRole(Constants::$PATIENT);
    }

    /**
     */
    public function test_appointments_patient(): void
    {
        $medicalSpecialtyId = MedicalSpecialty::query()->inRandomOrder()->first()->id;

        $data = [
            'medical_specialty_id' => $medicalSpecialtyId,
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->get(route('patient.doctor.list', $data));

        $response->assertStatus(200);
    }
}
