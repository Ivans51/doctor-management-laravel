<?php

namespace App\Http\Controllers;

use Faker\Factory;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{

    public function index()
    {
        $files = [];

        $faker = Factory::create();

        for ($i = 0; $i <= 10; $i++) {
            $files[] = $faker->imageUrl(200, 200, 'people', false, true, 'lightblue');
        }

        return view('pages/admin/payments/index')->with([
            'images' => $files,
        ]);
    }

    public function create()
    {
        return view('pages/admin/payments/create');
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        return view('pages/admin/payments/show');
    }

    public function edit($id)
    {
        return view('pages/admin/payments/edit');
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
