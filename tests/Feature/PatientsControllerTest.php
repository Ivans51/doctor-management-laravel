<?php

namespace Tests\Feature;

use App\Models\Doctor;
use App\Models\Patient;
use App\Utils\Constants;
use App\Utils\Testing;
use Faker\Factory;
use Tests\TestCase;

class PatientsControllerTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        (new Testing())->createFakeUserWithRole(Constants::$DOCTOR);
    }

    /**
     */
    public function test_my_patients_doctor_index(): void
    {
        $data = [
            'doctorId' => Doctor::query()->inRandomOrder()->first()->id,
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->get(route('doctor.my-patients-doctor.index', $data));

        $response->assertStatus(200);
    }

    /**
     */
    public function test_my_patients_doctor_create(): void
    {
        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->get(route('doctor.my-patients-doctor.create'));

        $response->assertStatus(200);
    }

    /**
     */
    public function test_my_patients_doctor_store(): void
    {
        $faker = Factory::create();

        $data = [
            'doctor_id' => Doctor::query()->first()->id,
            'name' => 'test',
            'email' => $faker->email,
            'password' => 'test1234',
            'password_confirmation' => 'test1234',
            'phone_number' => '1212',
            'location_address-search' => 'test',
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->post(route('doctor.my-patients-doctor.store', $data));

        $response->dump();

        $response->assertRedirect();
        $response->assertStatus(302);
        /*$response->assertSessionHas('success', 'User created successfully');*/
    }

    /**
     */
    public function test_my_patients_doctor_show(): void
    {
        $doctorId = Doctor::query()->inRandomOrder()->first()->id;

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->get(route('doctor.my-patients-doctor.show', $doctorId));

        $response->assertStatus(200);
    }

    /**
     */
    public function test_my_patients_doctor_edit(): void
    {
        $patientId = Patient::query()->inRandomOrder()->first()->id;

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->get(route('doctor.my-patients-doctor.edit', $patientId));

        $response->assertStatus(200);
    }

    /**
     */
    public function test_my_patients_doctor_update(): void
    {
        $faker = Factory::create();
        $patientId = Patient::query()->inRandomOrder()->first()->user_id;

        $data = [
            'name' => 'test',
            'email' => $faker->email,
            'password' => 'test1234',
            'password_confirmation' => 'test1234',
            'phone_number' => '1212',
            'location_address-search' => 'test',
            'status' => Constants::$ACTIVE,
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->put(route('doctor.my-patients-doctor.update', $patientId), $data);

        $response->assertRedirect();
        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Updated successfully');
    }

    /**
     */
    public function test_my_patients_doctor_destroy(): void
    {
        $patientId = Patient::query()->orderBy('id', 'desc')->first()->id;

        $data = [
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->delete(route('doctor.my-patients-doctor.destroy', $patientId), $data);

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
            ->post(route('doctor.search.patients', $data));

        $response->assertJsonStructure([
            'status',
            'message',
            'data'
        ]);
        $response->assertStatus(200);
    }
}
