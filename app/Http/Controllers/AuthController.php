<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Mail\MailClass;

class AuthController extends Controller
{
    /**
     * Login user
     * @param AuthRequest $request
     * @return RedirectResponse
     */
    public function login(AuthRequest $request): RedirectResponse
    {
        if (config('app.env') !== 'local' && !TurnstileHelper::validateTurnstile($request->input('cf-turnstile-response'))) {
            return back()->withErrors(['turnstile' => 'Turnstile verification failed. Please try again.']);
        }

        $credentials = $request->only('email', 'password');
        $isSuccess = Auth::attempt($credentials);

        if (!$isSuccess) {
            return back()->withErrors(['login' => 'User or password Incorrect']);
        }

        return redirect($this->getRedirectRoute())->with('success', 'You are logged in!');
    }

    /**
     * @param AuthRequest $request
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function register(AuthRequest $request): RedirectResponse
    {
        try {
            if (config('app.env') !== 'local' && !TurnstileHelper::validateTurnstile($request->input('cf-turnstile-response'))) {
                return back()->withErrors(['turnstile' => 'Turnstile verification failed. Please try again.']);
            }

            \DB::beginTransaction();

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
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->withErrors(['register' => 'Registration failed.']);
        }
    }

    public function forgot(AuthRequest $request): RedirectResponse
    {
        try {
            if (config('app.env') !== 'local' && !TurnstileHelper::validateTurnstile($request->input('cf-turnstile-response'))) {
                return back()->withErrors(['turnstile' => 'Turnstile verification failed.']);
            }

            $user = User::query()->where('email', $request->get('email'))->first();
            if (!$user) {
                return back()->withErrors(['email' => 'Email not found.']);
            }

            // Generate and store token
            $token = Str::random(60);
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email],
                [
                    'email' => $user->email,
                    'token' => $token,
                    'created_at' => Carbon::now(),
                ]
            );

            // Send recovery email using Resend.com
            $resetLink = route('doctor.password.reset', ['token' => $token, 'email' => $user->email]);

            return redirect($resetLink);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred.']);
        }
    }

    /**
     * @param $email
     * @return void
     * @throws \Exception
     */
    private function sendEmail($email, $link): void
    {
        try {
            $data = [
                'name' => 'Password Recovery',
                'message' => 'You requested a password reset. Click the button below to reset your password.',
                'action_url' => $link,
                'action_text' => 'Reset Password',
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

    /**
     * Handle doctor password reset
     * @param Request $request
     * @return RedirectResponse
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
            'token' => 'required|string',
        ]);

        if (config('app.env') !== 'local' && !TurnstileHelper::validateTurnstile($request->input('cf-turnstile-response'))) {
            return back()->withErrors(['turnstile' => 'Turnstile verification failed.']);
        }

        // Validate token and email
        $reset = \DB::table('password_reset_tokens')
            ->where('email', $request->get('email'))
            ->where('token', $request->get('token'))
            ->first();

        if (!$reset) {
            return back()->withErrors(['error' => 'Invalid or expired password reset token.']);
        }

        $user = User::query()->where('email', $request->get('email'))->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Email not found.']);
        }

        $user->password = bcrypt($request->get('password'));
        $user->save();

        // Delete the token after successful reset
        \DB::table('password_reset_tokens')
            ->where('email', $request->get('email'))
            ->delete();

        return redirect()->route('doctor.login')->with('success', 'Password reset successfully. You can now log in.');
    }
}
