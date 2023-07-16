<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class ViewPatientController extends Controller
{
    public function getDashBoard(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages/patient/main');
    }

    public function getLogin(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages/patient/auth/login');
    }

    public function getRegister(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages/patient/auth/register');
    }

    public function getForgot(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages/patient/auth/forgot');
    }
}
