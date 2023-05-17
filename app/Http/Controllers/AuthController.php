<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(AuthRequest $request): RedirectResponse
    {
        // Get the user's email and password from the request
        $email = $request->input('email');
        $password = $request->input('password');

        // Get the user from the database
        $user = User::query()->where('email', $email)->first();

        // Check if the user exists
        if (!$user) {
            return back()->withErrors(['login' => 'User not found']);
        }

        // Check if the password matches the hash
        if (!Hash::check($password, $user->password)) {
            return back()->withErrors(['login' => 'Incorrect password']);
        }

        $isSuccessCaptcha = $this->validateRecaptcha($request);

        if ($isSuccessCaptcha) {
            return redirect('admin');
        } else {
            return back()->withErrors(['captcha' => 'ReCaptcha Error']);
        }
    }

    public function register(AuthRequest $request): RedirectResponse
    {
        $isSuccessCaptcha = $this->validateRecaptcha($request);

        if ($isSuccessCaptcha) {
            return back()->with('auth_message', 'Thanks for your login!');
        } else {
            return back()->withErrors(['captcha' => 'ReCaptcha Error']);
        }
    }

    public function forgot(AuthRequest $request): RedirectResponse
    {
        $isSuccessCaptcha = $this->validateRecaptcha($request);

        if ($isSuccessCaptcha) {
            return back()->with('auth_message', 'Thanks for your login!');
        } else {
            return back()->withErrors(['captcha' => 'ReCaptcha Error']);
        }
    }

    /**
     * @param AuthRequest $request
     * @return true
     */
    private function validateRecaptcha(AuthRequest $request): bool
    {
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $remoteip = $_SERVER['REMOTE_ADDR'];
        $data = [
            'secret' => config('services.recaptcha.secret'),
            'response' => $request->get('recaptcha'),
            'remoteip' => $remoteip
        ];
        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $resultJson = json_decode($result);
        if (!$resultJson->success) {
            return false;
        }
        if ($resultJson->score >= 0.3) {
            //Validation was successful, add your form submission logic here
            return true;
        } else {
            return false;
        }
    }
}
