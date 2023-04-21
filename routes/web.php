<?php

use App\Http\Controllers\ViewController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [ViewController::class, 'getDashBoard'])->name('home');
Route::get('/appointments', [ViewController::class, 'getAppointments'])->name('appointments');
Route::get('/my-patients', [ViewController::class, 'getScheduleTiming'])->name('my-patients');
Route::get('/schedule-timing', [ViewController::class, 'getScheduleTiming'])->name('schedule-timing');
Route::get('/payments', [ViewController::class, 'getScheduleTiming'])->name('payments');
Route::get('/messages', [ViewController::class, 'getScheduleTiming'])->name('messages');
Route::get('/blog', [ViewController::class, 'getScheduleTiming'])->name('blog');
Route::get('/settings', [ViewController::class, 'getScheduleTiming'])->name('settings');
