<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Utils\Constants;
use App\Utils\Testing;
use Faker\Factory as Faker;
use Tests\TestCase;

class AppointmentPatientControllerTest extends TestCase
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
        $data = [
            'limit' => 10,
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->get(route('patient.appointments.patient', $data));

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
            ->put(route('patient.appointment.status', $data));

        $response->assertJsonStructure([
            'success',
            'message',
        ]);
        $response->assertStatus(200);
    }

    /**
     */
    public function test_appointment_store(): void
    {
        $faker = Faker::create();

        $data = [
            'patient_id' => Patient::query()->inRandomOrder()->first()->id,
            'doctor_id' => Doctor::query()->inRandomOrder()->first()->id,
            'start_time' => $faker->time(),
            'end_time' => $faker->time(),
            'description' => $faker->text(),
            'date_consulting' => $faker->dateTimeBetween('-1 years', 'now'),
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->post(route('patient.appointment.store', $data));

        $response->assertStatus(302);
        $response->assertSessionHas('_token');
    }
}
