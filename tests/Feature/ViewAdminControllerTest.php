<?php

namespace Tests\Feature;

use App\Utils\Constants;
use App\Utils\Testing;
use Tests\TestCase;

class ViewAdminControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        (new Testing())->createFakeUserWithRole(Constants::$ADMIN);
    }

    /**
     * A basic feature test example.
     */
    public function test_dashboard(): void
    {
        $response = $this->get(route('admin.home'));

        $response->assertStatus(200);
    }
}
