<?php

namespace App\Http\Controllers;

use App\Charts\HomeChart;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class ViewAdminController extends Controller
{
    public function getDashBoard(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $files = [];

        $faker = Faker::create();

        for ($i = 0; $i <= 10; $i++) {
            $files[] = $faker->imageUrl(200, 200, 'people', false, true, 'lightblue');
        }

        $chart = new HomeChart;
        $chart->labels(['One', 'Two', 'Three', 'Four']);
        $chart->dataset('My dataset', 'pie', [1, 2, 3, 4]);

        return view('pages/admin/main')->with([
            'images' => $files,
            'chart' => $chart,
        ]);
    }

    public function getSignIn(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages/admin/auth/login');
    }

    public function login(Request $request): \Illuminate\Http\RedirectResponse
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
            return back()->withErrors(['captcha' => 'ReCaptcha Error']);
        }
        if ($resultJson->score >= 0.3) {
            //Validation was successful, add your form submission logic here
            return back()->with('auth_message', 'Thanks for your login!');
        } else {
            return back()->withErrors(['captcha' => 'ReCaptcha Error']);
        }
    }

    public function register(Request $request): \Illuminate\Http\RedirectResponse
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
            return back()->withErrors(['captcha' => 'ReCaptcha Error']);
        }
        if ($resultJson->score >= 0.3) {
            //Validation was successful, add your form submission logic here
            return back()->with('auth_message', 'Thanks for your login!');
        } else {
            return back()->withErrors(['captcha' => 'ReCaptcha Error']);
        }
    }

    public function forgot(Request $request): \Illuminate\Http\RedirectResponse
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
            return back()->withErrors(['captcha' => 'ReCaptcha Error']);
        }
        if ($resultJson->score >= 0.3) {
            //Validation was successful, add your form submission logic here
            return back()->with('auth_message', 'Thanks for your login!');
        } else {
            return back()->withErrors(['captcha' => 'ReCaptcha Error']);
        }
    }

    public function getSignUp(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages/admin/auth/register');
    }

    public function getForgot(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages/admin/auth/forgot');
    }

    public function getAdmins(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $files = [];

        $faker = Faker::create();

        for ($i = 0; $i <= 10; $i++) {
            $files[] = $faker->imageUrl(200, 200, 'people', false, true, 'lightblue');
        }

        $title = 'Delete User!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);

        return view('pages/admin/admins/index')->with([
            'images' => $files,
        ]);
    }

    public function getPatients(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $files = [];

        $faker = Faker::create();

        for ($i = 0; $i <= 10; $i++) {
            $files[] = $faker->imageUrl(200, 200, 'people', false, true, 'lightblue');
        }

        return view('pages/admin/patients/index')->with([
            'images' => $files,
        ]);
    }

    public function getPayments(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $files = [];

        $faker = Faker::create();

        for ($i = 0; $i <= 10; $i++) {
            $files[] = $faker->imageUrl(200, 200, 'people', false, true, 'lightblue');
        }

        return view('pages/admin/payments/index')->with([
            'images' => $files,
        ]);
    }

    public function getDoctors(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $files = [];

        $faker = Faker::create();

        for ($i = 0; $i <= 10; $i++) {
            $files[] = $faker->imageUrl(200, 200, 'people', false, true, 'lightblue');
        }

        return view('pages/admin/doctors/index')->with([
            'images' => $files,
        ]);
    }

    function deleteUser(User $user)
    {
        $user->delete();
        return redirect()->back();
    }
}
