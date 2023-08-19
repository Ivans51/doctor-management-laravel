<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Utils\Constants;
use App\Utils\Testing;
use Tests\TestCase;

class ViewPatientControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        (new Testing())->createFakeUserWithRole(Constants::$PATIENT);
    }

    /**
     * A basic feature test example.
     */
    public function test_dashboard(): void
    {
        $response = $this->get(route('patient.home'));

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     */
    public function test_appointments(): void
    {
        $response = $this->get(route('patient.appointments'));

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     */
    public function test_schedule_timing(): void
    {
        $response = $this->get(route('patient.schedule.timing'));

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     */
    public function test_payments(): void
    {
        $response = $this->get(route('patient.payments'));

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     */
    public function test_messages(): void
    {
        $response = $this->get(route('patient.messages'));

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     */
    public function test_monitoring(): void
    {
        $response = $this->get(route('patient.monitoring'));

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     */
    public function test_checkout(): void
    {
        $appointmentId = Appointment::query()->first()->id;

        $response = $this->get(route('patient.checkout', [
            'appointment_id' => $appointmentId
        ]));

        $response->assertStatus(200);
    }
}
