<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Chat;
use App\Utils\Constants;
use App\Utils\Testing;
use Tests\TestCase;

class AppointmentControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        (new Testing())->createFakeUserWithRole(Constants::$DOCTOR);
    }

    /**
     */
    public function test_appointments_doctor(): void
    {
        $data = [
            'limit' => 10,
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->get(route('doctor.appointments.doctor', $data));

        $response->assertStatus(200);
    }

    /**
     */
    public function test_appointment_status(): void
    {
        $data = [
            'appointment_id' => Appointment::query()->inRandomOrder()->first()->id,
            'status' => Constants::$PENDING,
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->put(route('doctor.appointment.status', $data));

        $response->assertJsonStructure([
            'success',
            'message',
        ]);
        $response->assertStatus(200);
    }
}
