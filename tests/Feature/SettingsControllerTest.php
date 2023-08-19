<?php

namespace Tests\Feature;

use App\Models\MedicalSpecialty;
use App\Utils\Constants;
use App\Utils\Testing;
use Faker\Factory;
use Tests\TestCase;

class SettingsControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        (new Testing())->createFakeUserWithRole(Constants::$DOCTOR);
    }

    /**
     * A basic feature test example.
     */
    public function test_settings(): void
    {
        $response = $this->get(route('doctor.settings'));

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     */
    public function test_change_password(): void
    {
        $response = $this->get(route('doctor.change.password'));

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     */
    public function test_notifications(): void
    {
        $response = $this->get(route('doctor.notifications'));

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     */
    public function test_reviews(): void
    {
        $response = $this->get(route('doctor.reviews'));

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     */
    public function test_update_profile(): void
    {
        $faker = Factory::create();

        $data = [
            'name' => 'Test',
            'email' => 'test@test.com' . $faker->randomDigitNotNull,
            'address_address-search' => 'Test',
            'specialties' => [
                MedicalSpecialty::query()->first()->id,
            ],
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->put(route('doctor.update.profile'), $data);

        $response->assertRedirect();
        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Profile updated successfully');
    }

    /**
     * Update password.
     */
    public function test_update_password(): void
    {
        $data = [
            'password' => 'password',
            'password_confirmation' => 'password',
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->put(route('doctor.update.password'), $data);

        $response->assertRedirect();
        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Password changed successfully');
    }
}
