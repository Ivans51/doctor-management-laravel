<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Mail\MailClass;
use App\Models\Role;
use App\Models\User;
use App\Utils\Constants;
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
        $user = new User();
        $user->name = $request->get('name');
        $user->email = $request->get('email');

        // Hash password
        $user->password = bcrypt($request->get('password'));
        $user->role_id = Role::query()->where('name', Constants::$ADMIN)->first()->id;
        $user->save();

        // Login user
        Auth::login($user);

        $isSuccessCaptcha = $this->validateRecaptcha($request);

        if ($isSuccessCaptcha) {
            return back()->with('auth_message', 'Thanks for your login!');
        } else {
            return back()->withErrors(['captcha' => 'ReCaptcha Error']);
        }
    }

    public function forgot(AuthRequest $request): RedirectResponse
    {
        // check email exists
        $user = User::query()->where('email', $request->get('email'))->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email not found']);
        }

        $isSuccessCaptcha = $this->validateRecaptcha($request);

        if ($isSuccessCaptcha) {
            $this->sendEmail($request->get('email'));

            return back()->with('auth_message', 'Thanks for your message!');
        } else {
            return back()->withErrors(['captcha' => 'ReCaptcha Error']);
        }
    }

    /**
     * @param $email
     * @return void
     */
    private function sendEmail($email): void
    {
        $data = [
            'name' => 'Recovery Password',
            'message' => 'This is a test email from Laravel 10.'
        ];

        \Mail::to($email)->send(new MailClass($data));
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
