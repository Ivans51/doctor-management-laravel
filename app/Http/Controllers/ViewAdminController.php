<?php

namespace App\Http\Controllers;

use App\Charts\HomeChart;
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

        return view('pages/main')->with([
            'images' => $files,
            'chart' => $chart,
        ]);
    }

    public function getSignIn(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages/auth/login');
    }

    public function getSignUp(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages/auth/register');
    }
}
