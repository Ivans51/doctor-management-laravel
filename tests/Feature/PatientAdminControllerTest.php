<?php

namespace Tests\Feature;

use App\Models\Doctor;
use App\Models\Patient;
use App\Utils\Constants;
use App\Utils\Testing;
use Faker\Factory;
use Tests\TestCase;

class PatientAdminControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        (new Testing())->createFakeUserWithRole(Constants::$ADMIN);
    }

    /**
     */
    public function test_patients_index(): void
    {
        $data = [
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->get(route('admin.patients.index', $data));

        $response->assertStatus(200);
    }

    /**
     */
    public function test_patients_create(): void
    {
        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->get(route('admin.patients.create'));

        $response->assertStatus(200);
    }

    /**
     */
    public function test_patients_store(): void
    {
        $faker = Factory::create();
        $doctorId = Doctor::query()->inRandomOrder()->first()->id;

        $data = [
            'name' => 'test',
            'email' => $faker->email,
            'password' => 'test1234',
            'password_confirmation' => 'test1234',
            'phone_number' => $faker->phoneNumber,
            'location_address-search' => $faker->address,
            'doctor_id' => $doctorId,
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->post(route('admin.patients.store', $data));

        $response->assertRedirect();
        $response->assertStatus(302);
        $response->assertSessionHas('success', 'User created successfully');
    }

    /**
     */
    public function test_patients_show(): void
    {
        $patientId = Patient::query()->inRandomOrder()->first()->id;

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->get(route('admin.patients.show', $patientId));

        $response->assertStatus(200);
    }

    /**
     */
    public function test_patients_edit(): void
    {
        $patientId = Patient::query()->inRandomOrder()->first()->id;

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->get(route('admin.patients.edit', $patientId));

        $response->assertStatus(200);
    }

    /**
     */
    public function test_patients_update(): void
    {
        $faker = Factory::create();
        $patientId = Patient::query()->inRandomOrder()->first()->user_id;
        $doctorId = Doctor::query()->inRandomOrder()->first()->id;

        $data = [
            'name' => 'test',
            'email' => $faker->email . $faker->randomDigitNotNull,
            'password' => 'test1234',
            'password_confirmation' => 'test1234',
            'phone_number' => $faker->phoneNumber,
            'location_address-search' => $faker->address,
            'doctor_id' => $doctorId,
            'status' => Constants::$ACTIVE,
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->put(route('admin.patients.update', $patientId), $data);

        $response->assertRedirect();
        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Updated successfully');
    }

    /**
     */
    public function test_patients_destroy(): void
    {
        $patientId = Patient::query()->orderBy('id', 'desc')->first()->id;

        $data = [
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->delete(route('admin.patients.destroy', $patientId), $data);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
        ]);
    }

    /**
     */
    public function test_search_patients(): void
    {
        $data = [
            'limit' => 2,
            'search' => 'test',
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->post(route('admin.search.user', $data));

        $response->assertJsonStructure([
            'status',
            'message',
            'data'
        ]);
        $response->assertStatus(200);
    }
}
