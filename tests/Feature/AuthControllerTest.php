<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Utils\Constants;
use App\Utils\Testing;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_logout(): void
    {
        (new Testing())->createFakeUserWithRole(Constants::$DOCTOR);
        $response = $this->get(route('doctor.logout'));

        $response->assertRedirect();
        $response->assertStatus(302);
    }

    /**
     * A basic feature test example.
     */
    public function test_form_login(): void
    {
        $roleId = Role::query()->where('name', Constants::$DOCTOR)->first()->id;
        $user = User::query()->where('role_id', $roleId)->first();

        $data = [
            'email' => $user->email,
            'password' => 'password',
            'recaptcha' => Constants::$CSRF_TOKEN,
            '_token' => Constants::$CSRF_TOKEN
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->post(route('doctor.form.login'), $data);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'You are logged in!');
    }

    /**
     * A basic feature test example.
     */
    public function test_form_register(): void
    {
        $faker = \Faker\Factory::create();

        $data = [
            'name' => $faker->name,
            'email' => $faker->email,
            'password' => 'password',
            'password_confirmation' => 'password',
            'recaptcha' => Constants::$CSRF_TOKEN,
            '_token' => Constants::$CSRF_TOKEN
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->post(route('doctor.form.register'), $data);

        $response->assertStatus(200);
        $response->assertJsonStructure(['message']);
    }

    /**
     * A basic feature test example.
     */
    public function test_form_forgot(): void
    {
        $roleId = Role::query()->where('name', Constants::$DOCTOR)->first()->id;
        $user = User::query()->where('role_id', $roleId)->first();

        $data = [
            'email' => $user->email,
            'recaptcha' => Constants::$CSRF_TOKEN,
            '_token' => Constants::$CSRF_TOKEN
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->post(route('doctor.form.forgot'), $data);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Thanks for your message!');
    }
}
