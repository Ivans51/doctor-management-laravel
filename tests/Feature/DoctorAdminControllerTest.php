<?php

namespace Tests\Feature;

use App\Models\Doctor;
use App\Models\MedicalSpecialty;
use App\Models\Patient;
use App\Utils\Constants;
use App\Utils\Testing;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DoctorAdminControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        (new Testing())->createFakeUserWithRole(Constants::$ADMIN);
    }

    /**
     */
    public function test_doctors_index(): void
    {
        $data = [
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->get(route('admin.doctors.index', $data));

        $response->assertStatus(200);
    }

    /**
     */
    public function test_doctors_create(): void
    {
        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->get(route('admin.doctors.create'));

        $response->assertStatus(200);
    }

    /**
     */
    public function test_doctors_store(): void
    {
        $faker = Factory::create();
        $medicalSpecialtyId = MedicalSpecialty::query()->inRandomOrder()->first()->id;

        $data = [
            'name' => 'test',
            'email' => $faker->email,
            'password' => 'test1234',
            'password_confirmation' => 'test1234',
            'speciality' => $medicalSpecialtyId,
            'phone_number' => $faker->phoneNumber,
            'location_address-search' => $faker->address,
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->post(route('admin.doctors.store', $data));

        $response->assertRedirect();
        $response->assertStatus(302);
        $response->assertSessionHas('success', 'User created successfully');
    }

    /**
     */
    public function test_doctors_show(): void
    {
        $doctorId = Doctor::query()->inRandomOrder()->first()->id;

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->get(route('admin.doctors.show', $doctorId));

        $response->assertStatus(200);
    }

    /**
     */
    public function test_doctors_edit(): void
    {
        $doctorId = Doctor::query()->inRandomOrder()->first()->id;

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->get(route('admin.doctors.edit', $doctorId));

        $response->assertStatus(200);
    }

    /**
     */
    public function test_doctors_update(): void
    {
        $faker = Factory::create();
        $doctorUserId = Doctor::query()->first()->user_id;

        $data = [
            'name' => 'test',
            'email' => $faker->email . $faker->randomDigitNotNull,
            'password' => 'test1234',
            'password_confirmation' => 'test1234',
            'speciality' => MedicalSpecialty::query()->inRandomOrder()->first()->id,
            'phone_number' => $faker->phoneNumber,
            'location_address-search' => $faker->address,
            'status' => Constants::$ACTIVE,
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->put(route('admin.doctors.update', $doctorUserId), $data);

        $response->assertRedirect();
        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Updated successfully');
    }

    /**
     */
    public function test_doctors_destroy(): void
    {
        $doctorId = Doctor::query()->orderBy('id', 'desc')->first()->id;

        $data = [
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->delete(route('admin.doctors.destroy', $doctorId), $data);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
        ]);
    }

    /**
     */
    public function test_search_doctors(): void
    {
        $data = [
            'limit' => 2,
            'search' => 'test',
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->post(route('admin.search.doctor', $data));

        $response->assertJsonStructure([
            'status',
            'message',
            'data'
        ]);
        $response->assertStatus(200);
    }
}
