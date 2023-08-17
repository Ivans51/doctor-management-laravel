<?php

namespace Tests\Feature;

use App\Utils\Constants;
use App\Utils\Testing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewPatientControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        (new Testing())->createFakeUserWithRole(Constants::$PATIENT);
    }

    /**
     * A basic feature test example.
     */
    public function test_get_dashboard(): void
    {
        $response = $this->get(route('patient.home'));

        $response->assertStatus(200);
    }
}
