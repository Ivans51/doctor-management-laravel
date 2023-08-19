<?php

namespace Tests\Feature;

use Tests\TestCase;

class ViewAuthControllerTest extends TestCase
{

    public function test_get_login(): void
    {
        $response = $this->get(route('doctor.login'));

        $response->assertStatus(200);
    }

    public function test_get_register(): void
    {
        $response = $this->get(route('doctor.register'));

        $response->assertStatus(200);
    }

    public function test_get_forgot(): void
    {
        $response = $this->get(route('doctor.forgot'));

        $response->assertStatus(200);
    }

    public function test_get_patient_login(): void
    {
        $response = $this->get(route('patient.login'));

        $response->assertStatus(200);
    }

    public function test_get_patient_forgot(): void
    {
        $response = $this->get(route('patient.forgot'));

        $response->assertStatus(200);
    }

    public function test_get_admin_login(): void
    {
        $response = $this->get(route('admin.login'));

        $response->assertStatus(200);
    }

    public function test_get_admin_forgot(): void
    {
        $response = $this->get(route('admin.forgot'));

        $response->assertStatus(200);
    }
}
