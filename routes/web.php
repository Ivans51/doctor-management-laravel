<?php

use Illuminate\Support\Facades\Route;
use Faker\Factory as Faker;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $files = [];

    $faker = Faker::create();

    for ($i = 0; $i <= 10; $i++) {
        $files[] = $faker->imageUrl(200,200, 'people', false, true, 'lightblue');
    }

    return view('pages/main')->with([
        'images' => $files,
    ]);
});
