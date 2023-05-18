<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(AuthRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');
        $isSuccess = Auth::attempt($credentials);

        // Check if the user exists
        if (!$isSuccess) {
            return back()->withErrors(['login' => 'User or password Incorrect']);
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

    /**
     * @param Request $request
     * @return Application|Redirector|RedirectResponse|\Illuminate\Contracts\Foundation\Application
     */
    public function logout(Request $request): Application|Redirector|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }
}
