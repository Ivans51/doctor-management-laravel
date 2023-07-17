<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DoctorsController;
use App\Http\Controllers\MedicalSpecialtyController;
use App\Http\Controllers\PatientsController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ViewAdminController;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\ViewPatientController;
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

/* Doctors */
Route::middleware('user')->group(function () {
    Route::get('/', [ViewController::class, 'getDashBoard'])->name('home');
    Route::get('/appointments', [ViewController::class, 'getAppointments'])->name('appointments');
    Route::get('/my-patients', [ViewController::class, 'getPatients'])->name('my-patients');
    Route::get('/my-patients/monitoring', [ViewController::class, 'getMonitoringForm'])->name('my-patients-monitoring');
    Route::get('/my-patients/checkout', [ViewController::class, 'getCheckoutForm'])->name('my-patients-checkout');
    Route::get('/schedule-timing', [ViewController::class, 'getScheduleTiming'])->name('schedule-timing');
    Route::get('/payments', [ViewController::class, 'getPayments'])->name('payments');
    Route::get('/messages', [ViewController::class, 'getMessages'])->name('messages');
    Route::get('/blog', [ViewController::class, 'getBlog'])->name('blog');
    Route::get('/settings', [SettingsController::class, 'getSettings'])->name('settings');
    Route::get('/settings/change-password', [SettingsController::class, 'getChangePassword'])->name('change-password');
    Route::get('/settings/notifications', [SettingsController::class, 'getNotifications'])->name('notifications');
    Route::get('/settings/reviews', [SettingsController::class, 'getReviews'])->name('reviews');
    Route::get('/logout', [AuthController::class, 'logout'])->name('web-logout');

    /* PAYMENTS */
    /* STRIPE */
    Route::post('/payment/stripe', [StripeController::class, 'checkout'])->name('payment-stripe');
    Route::get('/payment/stripe/success', [StripeController::class, 'success'])->name('payment-stripe-success');

    /* PAYPAL */
    Route::post('/payment/paypal', [PaypalController::class, 'checkout'])->name('payment-paypal');
    Route::get('/payment/paypal/success', [PaypalController::class, 'success'])->name('payment-paypal-success');
    Route::get('/payment/paypal/cancel', [PaypalController::class, 'cancel'])->name('payment-paypal-cancel');

    /* RESOURCE */
    Route::resource('my-patients-doctor', PatientsController::class);
    Route::post('appointment', [AppointmentController::class, 'store'])->name('appointment-store');

    /* JSON */
    Route::get('/doctor/list', [DoctorsController::class, 'doctorList'])->name('doctor-list');
    Route::get('/appointments/doctor', [AppointmentController::class, 'getAppointmentsByDoctor'])->name('appointments-doctor');

    /* POST */
    Route::post('/payments/search', [PaymentsController::class, 'searchByDoctor'])->name('search-payment-doctor');
    Route::post('/schedule-timing/doctor', [ScheduleController::class, 'getScheduleByDoctorId'])->name('schedule-timing-doctor');
    Route::post('/patients/doctor/search', [PatientsController::class, 'searchByDoctor'])->name('search-patient-doctor');
    Route::put('/settings/update/profile', [SettingsController::class, 'updateProfileDoctor'])->name('settings.update.profile');
    Route::put('/settings/update/password', [SettingsController::class, 'updatePassword'])->name('settings.update.password');
});

Route::middleware('user.auth')->group(function () {
    Route::get('/login', [ViewController::class, 'getLogin'])->name('login');
    Route::get('/register', [ViewController::class, 'getRegister'])->name('register');
    Route::get('/forgot', [ViewController::class, 'getForgot'])->name('forgot');

    Route::post('/login', [AuthController::class, 'login'])->name('web-form-login');
    Route::post('/register', [AuthController::class, 'register'])->name('web-form-register');
    Route::post('/forgot', [AuthController::class, 'forgot'])->name('web-form-forgot');
});

/* Patients */
Route::group(['prefix' => 'patient'], function () {
    Route::middleware('patient')->group(function () {
        Route::get('/', [ViewPatientController::class, 'getDashBoard'])->name('patient-home');
        Route::get('/logout', [AuthController::class, 'logout'])->name('patient-logout');
    });

    Route::middleware('patient.auth')->group(function () {
        Route::get('/login', [ViewPatientController::class, 'getLogin'])->name('patient-login');
        Route::get('/forgot', [ViewPatientController::class, 'getForgot'])->name('patient-forgot');

        Route::post('/login', [AuthController::class, 'login'])->name('patient-form-login');
        Route::post('/forgot', [AuthController::class, 'forgot'])->name('patient-form-forgot');
    });
});

/* Admins */
Route::group(['prefix' => 'admin'], function () {
    Route::middleware(['admin'])->group(function () {
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

        Route::resource('medical', MedicalSpecialtyController::class);
        Route::post('/medical/search', [MedicalSpecialtyController::class, 'search'])->name('search-medical');
    });

    Route::middleware('admin.auth')->group(function () {
        Route::get('/login', [ViewAdminController::class, 'getSignIn'])->name('admin-sign-in');
        Route::get('/forgot', [ViewAdminController::class, 'getForgot'])->name('admin-forgot');

        Route::post('/login', [AuthController::class, 'login'])->name('admin-form-login');
        Route::post('/forgot', [AuthController::class, 'forgot'])->name('admin-form-forgot');
    });
});
