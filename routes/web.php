<?php

use App\Http\Controllers\ViewAdminController;
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
Route::get('/login', [ViewController::class, 'getSignIn'])->name('sign-in');
Route::get('/register', [ViewController::class, 'getSignUp'])->name('sign-up');
Route::get('/appointments', [ViewController::class, 'getAppointments'])->name('appointments');
Route::get('/my-patients', [ViewController::class, 'getPatients'])->name('my-patients');
Route::get('/my-patients/detail', [ViewController::class, 'getPatientsDetail'])->name('my-patients-detail');
Route::get('/my-patients/monitoring', [ViewController::class, 'getMonitoringForm'])->name('my-patients-monitoring');
Route::get('/my-patients/checkout', [ViewController::class, 'getCheckoutForm'])->name('my-patients-checkout');
Route::get('/schedule-timing', [ViewController::class, 'getScheduleTiming'])->name('schedule-timing');
Route::get('/payments', [ViewController::class, 'getPayments'])->name('payments');
Route::get('/messages', [ViewController::class, 'getMessages'])->name('messages');
Route::get('/blog', [ViewController::class, 'getBlog'])->name('blog');
Route::get('/settings', [ViewController::class, 'getSettings'])->name('settings');
Route::get('/settings/change-password', [ViewController::class, 'getChangePassword'])->name('change-password');
Route::get('/settings/notifications', [ViewController::class, 'getNotifications'])->name('notifications');
Route::get('/settings/reviews', [ViewController::class, 'getReviews'])->name('reviews');

Route::post('/my-patient-detail-post', [ViewController::class, 'postSettings'])->name('my-patient-detail-post');

Route::group(['prefix' => 'admin'], function () {
    Route::get('/', [ViewAdminController::class, 'getDashBoard'])->name('admin-home');
    Route::get('/login', [ViewAdminController::class, 'getSignIn'])->name('admin-sign-in');
    Route::get('/register', [ViewAdminController::class, 'getSignUp'])->name('admin-sign-up');
});
