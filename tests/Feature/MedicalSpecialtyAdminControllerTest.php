<?php

namespace Tests\Feature;

use App\Models\MedicalSpecialty;
use App\Utils\Constants;
use App\Utils\Testing;
use Faker\Factory;
use Tests\TestCase;

class MedicalSpecialtyAdminControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        (new Testing())->createFakeUserWithRole(Constants::$ADMIN);
    }

    /**
     */
    public function test_medical_index(): void
    {
        $data = [
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->get(route('admin.medical.index', $data));

        $response->assertStatus(200);
    }

    /**
     */
    public function test_medical_create(): void
    {
        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->get(route('admin.medical.create'));

        $response->assertStatus(200);
    }

    /**
     */
    public function test_medical_store(): void
    {
        $faker = Factory::create();

        $data = [
            'name' => 'test',
            'price' => $faker->randomFloat(2, 0, 100),
            'status' => Constants::$ACTIVE,
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->post(route('admin.medical.store', $data));

        $response->assertRedirect();
        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Created successfully');
    }

    /**
     */
    public function test_medical_show(): void
    {
        $medicalId = MedicalSpecialty::query()->inRandomOrder()->first()->id;

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->get(route('admin.medical.show', $medicalId));

        $response->assertStatus(200);
    }

    /**
     */
    public function test_medical_edit(): void
    {
        $medicalId = MedicalSpecialty::query()->inRandomOrder()->first()->id;

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->get(route('admin.medical.edit', $medicalId));

        $response->assertStatus(200);
    }

    /**
     */
    public function test_medical_update(): void
    {
        $faker = Factory::create();
        $medicalId = MedicalSpecialty::query()->inRandomOrder()->first()->id;

        $data = [
            'name' => 'test',
            'price' => $faker->randomFloat(2, 0, 100),
            'status' => Constants::$ACTIVE,
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->put(route('admin.medical.update', $medicalId), $data);

        $response->assertRedirect();
        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Updated successfully');
    }

    /**
     */
    public function test_medical_destroy(): void
    {
        $medicalId = MedicalSpecialty::query()->orderBy('id', 'desc')->first()->id;

        $data = [
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->delete(route('admin.medical.destroy', $medicalId), $data);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
        ]);
    }

    /**
     */
    public function test_search_medical(): void
    {
        $data = [
            'limit' => 2,
            'search' => 'test',
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->post(route('admin.search.medical', $data));

        $response->assertJsonStructure([
            'status',
            'message',
            'data'
        ]);
        $response->assertStatus(200);
    }
}
