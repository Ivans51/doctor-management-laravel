<?php

namespace Tests\Feature;

use App\Utils\Constants;
use App\Utils\Testing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SchedulePatientControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        (new Testing())->createFakeUserWithRole(Constants::$PATIENT);
    }

    /**
     */
    public function test_api_schedule_timing(): void
    {
        $data = [
            'start' => '2021-10-01',
            'end' => '2021-10-31',
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->post(route('patient.api.schedule.timing', $data));

        $response->assertJsonStructure([
            'status',
            'message',
            'data'
        ]);
        $response->assertStatus(200);
    }
}
