<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Mail\MailClass;
use App\Models\Doctor;
use App\Models\Role;
use App\Models\User;
use App\Utils\Constants;
use App\Utils\TurnstileHelper;
use Illuminate\Foundation\Application;
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
            $request->validate([
                'cf-turnstile-response' => 'required|string',
                'email' => 'required|email',
                'password' => 'required|min:8',
            ]);

            if (!TurnstileHelper::validateTurnstile($request->input('cf-turnstile-response'))) {
                return back()->withErrors(['turnstile' => 'Turnstile verification failed. Please try again.']);
            }

            $credentials = $request->only('email', 'password');
            $isSuccess = Auth::attempt($credentials);

            if (!$isSuccess) {
                return back()->withErrors(['login' => 'User or password Incorrect']);
            }

            return redirect($this->getRedirectRoute())->with('success', 'You are logged in!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['login' => 'User or password Incorrect']);
        }
    }

    /**
     * @param AuthRequest $request
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function register(AuthRequest $request): RedirectResponse
    {
        try {
            $request->validate([
                'cf-turnstile-response' => 'required|string',
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8|confirmed',
            ]);

            \DB::beginTransaction();

            // Validate Turnstile
            if (!TurnstileHelper::validateTurnstile($request->input('cf-turnstile-response'))) {
                return response()->json(['message' => 'Turnstile verification failed.'], 401);
            }

            $user = new User();
            $user->name = $request->get('name');
            $user->email = $request->get('email');
            $user->password = bcrypt($request->get('password'));
            $user->role_id = Role::query()->where('name', Constants::$DOCTOR)->first()->id;
            $user->save();

            // create doctor
            $doctor = new Doctor();
            $doctor->user_id = $user->valueUuid;
            $doctor->name = $request->get('name');
            $doctor->speciality = 'default';
            $doctor->phone = 'default';
            $doctor->save();

            Auth::login($user);
            \DB::commit();
            return redirect($this->getRedirectRoute())->with('success', 'Thanks for your registration!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \DB::rollBack();
            return response()->json(['message' => $e->errors()], 401);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['message' => 'Registration failed.'], 401);
        }
    }

    public function forgot(AuthRequest $request): RedirectResponse
    {
        try {
            $request->validate([
                'cf-turnstile-response' => 'required|string',
                'email' => 'required|email',
            ]);

            // Validate Turnstile
            if (!TurnstileHelper::validateTurnstile($request->input('cf-turnstile-response'))) {
                return back()->withErrors(['turnstile' => 'Turnstile verification failed.']);
            }

            $user = User::query()->where('email', $request->get('email'))->first();
            if (!$user) {
                return back()->withErrors(['email' => 'Email not found.']);
            }

            // Optional: Send recovery email
            // $this->sendEmail($request->get('email'));

            return back()->with('success', 'Password reset link sent!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred.']);
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
     * @param Request $request
     * @return Application|Redirector|RedirectResponse|\Illuminate\Contracts\Foundation\Application
     */
    public function logout(Request $request): Application|Redirector|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        try {
            $routeTo = $this->getRedirectRoute();
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect($routeTo);
        } catch (\Exception $e) {
            return redirect('login');
        }
    }

    /**
     * Determine the redirect route based on the authenticated user's role.
     *
     * @return string
     */
    private function getRedirectRoute(): string
    {
        if (!Auth::check() || !Auth::user()->roles) {
            return 'login';
        }

        return match (Auth::user()->roles->name) {
            Constants::$ADMIN  => 'admin',
            Constants::$PATIENT => 'patient',
            Constants::$DOCTOR => '',
            default => 'login',
        };
    }
}
