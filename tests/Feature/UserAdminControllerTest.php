<?php

namespace Tests\Feature;

use App\Models\User;
use App\Utils\Constants;
use App\Utils\Testing;
use Faker\Factory;
use Tests\TestCase;

class UserAdminControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        (new Testing())->createFakeUserWithRole(Constants::$ADMIN);
    }

    /**
     */
    public function test_admin_index(): void
    {
        $data = [
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->get(route('admin.admins.index', $data));

        $response->assertStatus(200);
    }

    /**
     */
    public function test_admin_create(): void
    {
        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->get(route('admin.admins.create'));

        $response->assertStatus(200);
    }

    /**
     */
    public function test_admin_store(): void
    {
        $faker = Factory::create();

        $data = [
            'name' => 'test',
            'email' => $faker->email,
            'password' => 'test1234',
            'password_confirmation' => 'test1234',
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->post(route('admin.admins.store', $data));

        $response->assertRedirect();
        $response->assertStatus(302);
        $response->assertSessionHas('success', 'User created successfully');
    }

    /**
     */
    public function test_admin_show(): void
    {
        $userId = User::query()->inRandomOrder()->first()->id;

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->get(route('admin.admins.show', $userId));

        $response->assertStatus(200);
    }

    /**
     */
    public function test_admin_edit(): void
    {
        $userId = User::query()->inRandomOrder()->first()->id;

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->get(route('admin.admins.edit', $userId));

        $response->assertStatus(200);
    }

    /**
     */
    public function test_admin_update(): void
    {
        $faker = Factory::create();
        $userId = User::query()->inRandomOrder()->first()->id;

        $data = [
            'name' => 'test',
            'email' => $faker->email,
            'password' => 'test1234',
            'password_confirmation' => 'test1234',
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->put(route('admin.admins.update', $userId), $data);

        $response->assertRedirect();
        $response->assertStatus(302);
        $response->assertSessionHas('success', 'User updated successfully');
    }

    /**
     */
    public function test_admin_destroy(): void
    {
        $userId = User::query()->orderBy('id', 'desc')->first()->id;

        $data = [
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->delete(route('admin.admins.destroy', $userId), $data);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
        ]);
    }

    /**
     */
    public function test_search_users(): void
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
