<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Mail\MailClass;
use App\Models\Role;
use App\Models\User;
use App\Utils\Constants;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Login user
     * @param AuthRequest $request
     * @return RedirectResponse
     */
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

    /**
     * @param AuthRequest $request
     * @return JsonResponse
     */
    public function register(AuthRequest $request): JsonResponse
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
            return response()->json(['message' => 'Thanks for your registration!']);
        } else {
            return response()->json(['message' => 'ReCaptcha Error'], 401);
        }
    }

    public function forgot(AuthRequest $request): JsonResponse
    {
        // check email exists
        $user = User::query()->where('email', $request->get('email'))->first();

        if (!$user) {
            return response()->json(['message' => 'Email not found'], 401);
        }

        $isSuccessCaptcha = $this->validateRecaptcha($request);

        if ($isSuccessCaptcha) {
            $this->sendEmail($request->get('email'));

            return response()->json(['message' => 'Thanks for your message!']);
        } else {
            return response()->json(['message' => 'ReCaptcha Error'], 401);
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
