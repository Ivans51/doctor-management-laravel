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

        return view('pages/main')->with([
            'images' => $files,
            'chart' => $chart,
        ]);
    }

    public function getAppointments(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $files = [];

        $faker = Faker::create();

        for ($i = 0; $i <= 10; $i++) {
            $files[] = $faker->imageUrl(200, 200, 'people', false, true, 'lightblue');
        }

        return view('pages/appointments')->with([
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

        return view('pages/patients/index')->with([
            'images' => $files,
        ]);
    }

    public function getPatientsDetail(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages/patients/detail');
    }

    public function getMonitoringForm(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages/patients/monitoring-form');
    }

    public function getScheduleTiming(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages/schedule-timing');
    }

    public function getPayments(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $files = [];

        $faker = Faker::create();

        for ($i = 0; $i <= 10; $i++) {
            $files[] = $faker->imageUrl(200, 200, 'people', false, true, 'lightblue');
        }

        return view('pages/payments')->with([
            'images' => $files,
        ]);
    }

    public function getMessages(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages/messages/index');
    }

    public function getBlog(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages/blog');
    }

    public function getSettings(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages/settings');
    }
}
