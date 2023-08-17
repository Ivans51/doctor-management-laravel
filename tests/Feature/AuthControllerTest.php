<?php

namespace Tests\Feature;

use App\Utils\Constants;
use App\Utils\Testing;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        (new Testing())->createFakeUserWithRole(Constants::$DOCTOR);
    }

    /**
     * A basic feature test example.
     */
    public function test_logout(): void
    {
        $response = $this->get(route('doctor.logout'));

        $response->assertRedirect();
        $response->assertStatus(302);
    }
}
