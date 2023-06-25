<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DoctorsController;
use App\Http\Controllers\PatientsController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\UserController;
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
Route::middleware('admin')->group(function () {
    Route::get('/', [ViewController::class, 'getDashBoard'])->name('home');
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
    Route::get('/logout', [AuthController::class, 'logout'])->name('web-logout');

    Route::post('/my-patient-detail-post', [ViewController::class, 'postSettings'])->name('my-patient-detail-post');
});

Route::middleware('admin.auth')->group(function () {
    Route::get('/login', [ViewController::class, 'getLogin'])->name('login');
    Route::get('/register', [ViewController::class, 'getRegister'])->name('register');
    Route::get('/forgot', [ViewController::class, 'getForgot'])->name('forgot');

    Route::post('/login', [AuthController::class, 'login'])->name('web-login');
    Route::post('/register', [AuthController::class, 'register'])->name('web-register');
    Route::post('/forgot', [AuthController::class, 'forgot'])->name('web-form-forgot');
});

Route::group(['prefix' => 'admin'], function () {
    Route::middleware(['auth'])->group(function () {
        Route::get('/', [ViewAdminController::class, 'getDashBoard'])->name('admin-home');
        Route::get('/logout', [AuthController::class, 'logout'])->name('admin-logout');

        Route::resource('admins', UserController::class);
        Route::post('/admins/search', [UserController::class, 'searchUser'])->name('search-user');

        Route::resource('patients', PatientsController::class);
        Route::post('/patients/search', [PatientsController::class, 'search'])->name('search-patient');

        Route::resource('doctors', DoctorsController::class);
        Route::post('/doctors/search', [DoctorsController::class, 'search'])->name('search-doctor');

        Route::resource('payments', PaymentsController::class);
        Route::post('/payments/search', [PaymentsController::class, 'search'])->name('search-payment');
    });

    Route::middleware('admin.auth')->group(function () {
        Route::get('/login', [ViewAdminController::class, 'getSignIn'])->name('admin-sign-in');
        Route::get('/register', [ViewAdminController::class, 'getSignUp'])->name('admin-sign-up');
        Route::get('/forgot', [ViewAdminController::class, 'getForgot'])->name('admin-forgot');

        Route::post('/login', [AuthController::class, 'login'])->name('admin-login');
        Route::post('/register', [AuthController::class, 'register'])->name('admin-register');
        Route::post('/forgot', [AuthController::class, 'forgot'])->name('admin-form-forgot');
    });
});
