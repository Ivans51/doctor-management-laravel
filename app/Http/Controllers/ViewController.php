<?php

namespace App\Http\Controllers;

use App\Charts\HomeChart;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Faker\Factory as Faker;

class ViewController extends Controller
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

        return view('pages/web/main')->with([
            'images' => $files,
            'chart' => $chart,
        ]);
    }

    public function getLogin(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages/web/auth/login');
    }

    public function getRegister(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages/web/auth/register');
    }

    public function getForgot(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages/web/auth/forgot');
    }

    public function getAppointments(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $files = [];

        $faker = Faker::create();

        for ($i = 0; $i <= 10; $i++) {
            $files[] = $faker->imageUrl(200, 200, 'people', false, true, 'lightblue');
        }

        return view('pages/web/appointments')->with([
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

        return view('pages/web/patients/index')->with([
            'images' => $files,
        ]);
    }

    public function getPatientsDetail(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $files = [];

        $faker = Faker::create();

        for ($i = 0; $i <= 10; $i++) {
            $files[] = $faker->imageUrl(200, 200, 'people', false, true, 'lightblue');
        }

        return view('pages/web/patients/detail')->with([
            'images' => $files,
        ]);
    }

    public function getMonitoringForm(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages/web/patients/monitoring-form');
    }

    public function getCheckoutForm(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages/web/patients/checkout-form');
    }

    public function getScheduleTiming(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages/web/schedule-timing');
    }

    public function getPayments(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $files = [];

        $faker = Faker::create();

        for ($i = 0; $i <= 10; $i++) {
            $files[] = $faker->imageUrl(200, 200, 'people', false, true, 'lightblue');
        }

        return view('pages/web/payments')->with([
            'images' => $files,
        ]);
    }

    public function getMessages(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $files = [];

        $faker = Faker::create();

        for ($i = 0; $i <= 10; $i++) {
            $files[] = $faker->imageUrl(200, 200, 'people', false, true, 'lightblue');
        }

        return view('pages/web/messages/index')->with([
            'images' => $files,
        ]);
    }

    public function getBlog(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages/web/blog');
    }

    public function getSettings(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $files = [];

        $faker = Faker::create();

        for ($i = 0; $i <= 10; $i++) {
            $files[] = $faker->imageUrl(200, 200, 'people', false, true, 'lightblue');
        }

        return view('pages/web/settings/index')->with([
            'images' => $files,
        ]);
    }

    public function getChangePassword(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages/web/settings/change-password');
    }

    public function getNotifications(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $files = [];

        $faker = Faker::create();

        for ($i = 0; $i <= 10; $i++) {
            $files[] = $faker->imageUrl(200, 200, 'people', false, true, 'lightblue');
        }

        return view('pages/web/settings/notifications')->with([
            'images' => $files,
        ]);
    }

    public function getReviews(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $files = [];

        $faker = Faker::create();

        for ($i = 0; $i <= 10; $i++) {
            $files[] = $faker->imageUrl(200, 200, 'people', false, true, 'lightblue');
        }

        return view('pages/web/settings/reviews')->with([
            'images' => $files,
        ]);
    }

    public function postSettings(): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('my-patients-checkout');
    }
}
