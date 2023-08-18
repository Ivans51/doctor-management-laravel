<?php

namespace Tests\Feature;

use App\Models\Chat;
use App\Models\User;
use App\Utils\Constants;
use App\Utils\Testing;
use Tests\TestCase;

class ChatsPatientControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        (new Testing())->createFakeUserWithRole(Constants::$PATIENT);
    }

    /**
     */
    public function test_chats_list(): void
    {
        $data = [
            'chat' => Chat::query()->inRandomOrder()->first()->id,
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->get(route('patient.chats.list', $data));

        $response->assertJsonStructure([
            'messages' => [],
        ]);
        $response->assertStatus(200);
    }

    /**
     */
    public function test_search_chat(): void
    {
        $data = [
            'search' => 'test',
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->post(route('patient.search.chat', $data));

        $response->assertStatus(200);
    }

    /**
     */
    public function test_send_message(): void
    {
        $data = [
            'chat_id' => Chat::query()->inRandomOrder()->first()->id,
            'message' => 'test',
            'user_id2' => User::query()->inRandomOrder()->first()->id,
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->post(route('patient.send.message', $data));

        $response->assertStatus(200);
    }
}
