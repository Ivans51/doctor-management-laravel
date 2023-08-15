<?php

use App\Utils\Constants;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int)$user->id === (int)$id;
});

Broadcast::channel(Constants::$CHAT_CHANNEL . '.{userId}', function ($user, $userId) {
    \Log::info('info', [$user->id == $userId]);
    return $user->id == $userId;
});

/* TODO: authorize channel */
Broadcast::channel(Constants::$CHAT_CHANNEL, function ($user) {
    return true;
});
