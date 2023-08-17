<?php

namespace Tests\Feature;

use App\Utils\Constants;
use App\Utils\Testing;
use Tests\TestCase;

class PaymentsControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        (new Testing())->createFakeUserWithRole(Constants::$DOCTOR);
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
            ->post(route('doctor.search.payments', $data));

        $response->assertStatus(200);
    }
}
