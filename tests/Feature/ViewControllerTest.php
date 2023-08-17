<?php

namespace Tests\Feature;

use App\Utils\Constants;
use App\Utils\Testing;
use Tests\TestCase;

class ViewControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        (new Testing())->createFakeUserWithRole(Constants::$DOCTOR);
    }

    /**
     * A basic feature test example.
     */
    public function test_get_dashboard(): void
    {
        $response = $this->get(route('doctor.home'));

        $response->assertStatus(200);
    }

    public function test_blog(): void
    {
        $response = $this->get(route('doctor.blog'));

        $response->assertStatus(200);
    }

    public function test_patients(): void
    {
        $response = $this->get(route('doctor.my-patients'));

        $response->assertStatus(200);
    }

    public function test_appointments(): void
    {
        $response = $this->get(route('doctor.appointments'));

        $response->assertStatus(200);
    }

    public function test_schedule_timing(): void
    {
        $response = $this->get(route('doctor.schedule.timing'));

        $response->assertStatus(200);
    }

    public function test_payments(): void
    {
        $response = $this->get(route('doctor.payments'));

        $response->assertStatus(200);
    }

    public function test_messages(): void
    {
        $response = $this->get(route('doctor.messages'));

        $response->assertStatus(200);
    }
}
