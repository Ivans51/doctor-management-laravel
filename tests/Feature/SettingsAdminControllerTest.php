<?php

namespace Tests\Feature;

use App\Utils\Constants;
use App\Utils\Testing;
use Tests\TestCase;

class SettingsAdminControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        (new Testing())->createFakeUserWithRole(Constants::$ADMIN);
    }

    /**
     * A basic feature test example.
     */
    public function test_settings(): void
    {
        $response = $this->get(route('admin.settings'));

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     */
    public function test_change_password(): void
    {
        $response = $this->get(route('admin.change.password'));

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     */
    public function test_update_profile(): void
    {
        $data = [
            'name' => 'Test',
            'email' => 'test@test.com',
            'address_address-search' => 'Test',
            'specialties' => [
                10,
            ],
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->put(route('admin.update.profile'), $data);

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
            ->put(route('admin.update.password'), $data);

        $response->assertRedirect();
        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Password changed successfully');
    }
}
