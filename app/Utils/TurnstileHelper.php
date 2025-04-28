<?php

namespace App\Utils;

use Illuminate\Support\Facades\Http;

class TurnstileHelper
{
    public static function validateTurnstile(string $token): bool
    {
        $secret = config('services.turnstile.secret');
        $response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            'secret' => $secret,
            'response' => $token,
        ]);
        return $response->status() == 200;
    }
}
