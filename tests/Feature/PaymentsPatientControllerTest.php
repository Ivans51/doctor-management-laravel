<?php

namespace Tests\Feature;

use App\Utils\Constants;
use App\Utils\Testing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaymentsPatientControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        (new Testing())->createFakeUserWithRole(Constants::$PATIENT);
    }

    /**
     * A basic feature test example.
     */
    public function test_search_payments(): void
    {
        $data = [
            'search' => 'Test',
            'limit' => 10,
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->post(route('patient.search-payment', $data));

        $response->assertStatus(200);
        $response->assertJsonIsObject();
    }
}
