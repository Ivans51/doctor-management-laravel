<?php

namespace App\Events;

use App\Models\Chat;
use App\Utils\Constants;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $userId;
    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct($userId, $chatId)
    {
        $this->userId = $userId;
        $chat = Chat::query()->find($chatId)->messages()->latest()->first();
        $this->message = $chat;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return PrivateChannel
     */
    public function broadcastOn(): PrivateChannel
    {
        $name = Constants::$CHAT_CHANNEL . '.' . $this->userId;
        return new PrivateChannel($name);
    }
}
