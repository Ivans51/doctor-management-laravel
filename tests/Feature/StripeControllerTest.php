<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Chat;
use App\Models\MedicalSpecialty;
use App\Utils\Constants;
use App\Utils\Testing;
use Tests\TestCase;

class StripeControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        (new Testing())->createFakeUserWithRole(Constants::$PATIENT);
    }

    /**
     */
    public function test_payment_stripe(): void
    {
        $appointment = Appointment::query()
            ->inRandomOrder()
            ->first();

        if ($appointment->medical_specialty_id == null) {
            $medicalSpecialty = MedicalSpecialty::query()
                ->inRandomOrder()
                ->first();

            $appointment->medical_specialty_id = $medicalSpecialty->id;
            $appointment->save();
        }

        $data = [
            'appointment_id' => $appointment->id,
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->post(route('patient.payment-stripe', $data));

        $response->assertStatus(302);
        $response->assertSessionHas('_token');

        $data = [
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->get(route('patient.payment-stripe-success', $data));

        $response->assertStatus(200);
        $response->assertViewHas('appointment');
    }
}
