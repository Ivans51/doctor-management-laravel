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
