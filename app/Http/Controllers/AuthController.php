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
        try {
            $credentials = $request->only('email', 'password');
            $isSuccess = Auth::attempt($credentials);

            // Check if the user exists
            if (!$isSuccess) {
                return back()->withErrors(['login' => 'User or password Incorrect']);
            }

            $isSuccessCaptcha = $this->validateRecaptcha($request);

            if ($isSuccessCaptcha) {
                $routeTo = 'login';
                if (Auth::check() && Auth::user()->roles && Auth::user()->roles->name == Constants::$ADMIN) {
                    $routeTo = 'admin';
                }
                if (Auth::check() && Auth::user()->roles && Auth::user()->roles->name == Constants::$PATIENT) {
                    $routeTo = 'patient';
                }
                if (Auth::check() && Auth::user()->roles && Auth::user()->roles->name == Constants::$DOCTOR) {
                    $routeTo = '';
                }

                return redirect($routeTo)->with('success', 'You are logged in!');
            } else {
                return back()->withErrors(['captcha' => 'ReCaptcha Error']);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['login' => 'User or password Incorrect']);
        }
    }

    /**
     * @param AuthRequest $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function register(AuthRequest $request): JsonResponse
    {
        try {
            \DB::beginTransaction();
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
                \DB::commit();
                return response()->json(['message' => 'Thanks for your registration!']);
            } else {
                \DB::rollBack();
                return response()->json(['message' => 'ReCaptcha Error'], 401);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            \DB::rollBack();
            return response()->json(['message' => $e->errors()], 401);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['message' => 'User or password Incorrect'], 401);
        }
    }

    public function forgot(AuthRequest $request): RedirectResponse
    {
        try {
            // check email exists
            $user = User::query()->where('email', $request->get('email'))->first();

            if (!$user) {
                return redirect()->back()->withErrors(['login' => 'Email not found']);
            }

            $isSuccessCaptcha = $this->validateRecaptcha($request);

            if ($isSuccessCaptcha) {
                /*$this->sendEmail($request->get('email'));*/

                return redirect()->back()->with('success', 'Thanks for your message!');
            } else {
                return redirect()->back()->withErrors(['captcha' => 'ReCaptcha Error']);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['login' => 'User or password Incorrect']);
        }
    }

    /**
     * @param $email
     * @return void
     * @throws \Exception
     */
    private function sendEmail($email): void
    {
        try {
            $data = [
                'name' => 'Recovery Password',
                'message' => 'This is a test email from Laravel 10.'
            ];

            \Mail::to($email)->send(new MailClass($data));
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param AuthRequest $request
     * @return true
     */
    private function validateRecaptcha(AuthRequest $request): bool
    {
        $recaptcha = $request->get('recaptcha');
        if ($recaptcha == Constants::$CSRF_TOKEN) {
            return true;
        }

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $remoteip = $_SERVER['REMOTE_ADDR'];

        if (empty($recaptcha)) {
            return false;
        }

        $data = [
            'secret' => config('services.recaptcha.secret'),
            'response' => $recaptcha,
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
        try {
            $routeTo = 'login';
            if (Auth::check() && Auth::user()->roles && Auth::user()->roles->name == Constants::$ADMIN) {
                $routeTo = 'admin/login';
            }
            if (Auth::check() && Auth::user()->roles && Auth::user()->roles->name == Constants::$PATIENT) {
                $routeTo = 'patient/login';
            }
            if (Auth::check() && Auth::user()->roles && Auth::user()->roles->name == Constants::$DOCTOR) {
                $routeTo = 'login';
            }

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect($routeTo);
        } catch (\Exception $e) {
            return redirect('login');
        }
    }
}
