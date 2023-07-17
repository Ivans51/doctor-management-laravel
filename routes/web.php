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
use App\Http\Controllers\SettingsPatientController;
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
Route::name('doctor.')->group(function () {
    Route::middleware('user')->group(function () {
        Route::get('/', [ViewController::class, 'getDashBoard'])->name('home');
        Route::get('/my-patients', [ViewController::class, 'getPatients'])->name('my-patients');
        Route::get('/blog', [ViewController::class, 'getBlog'])->name('blog');
        Route::get('/appointments', [ViewController::class, 'getAppointments'])->name('appointments');
        Route::get('/schedule-timing', [ViewController::class, 'getScheduleTiming'])->name('schedule-timing');
        Route::get('/payments', [ViewController::class, 'getPayments'])->name('payments');
        Route::get('/messages', [ViewController::class, 'getMessages'])->name('messages');
        Route::get('/settings', [SettingsController::class, 'getSettings'])->name('settings');
        Route::get('/settings/change-password', [SettingsController::class, 'getChangePassword'])->name('change.password');
        Route::get('/settings/notifications', [SettingsController::class, 'getNotifications'])->name('notifications');
        Route::get('/settings/reviews', [SettingsController::class, 'getReviews'])->name('reviews');
        Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

        /* RESOURCE */
        Route::resource('my-patients-doctor', PatientsController::class);

        /* JSON */
        Route::get('/appointments/doctor', [AppointmentController::class, 'getAppointmentsByDoctor'])->name('appointments-doctor');

        /* POST */
        Route::post('/payments/search', [PaymentsController::class, 'searchByDoctor']);
        Route::post('/schedule/timing/doctor', [ScheduleController::class, 'getScheduleByDoctorId'])->name('schedule.timing');
        Route::post('/patients/doctor/search', [PatientsController::class, 'searchByDoctor']);
        Route::put('/settings/update/profile', [SettingsController::class, 'updateProfileDoctor'])->name('update.profile');
        Route::put('/settings/update/password', [SettingsController::class, 'updatePassword'])->name('update.password');
    });

    Route::middleware('user.auth')->group(function () {
        Route::get('/login', [ViewController::class, 'getLogin'])->name('login');
        Route::get('/register', [ViewController::class, 'getRegister'])->name('register');
        Route::get('/forgot', [ViewController::class, 'getForgot'])->name('forgot');

        Route::post('/login', [AuthController::class, 'login'])->name('form.login');
        Route::post('/register', [AuthController::class, 'register'])->name('form.register');
        Route::post('/forgot', [AuthController::class, 'forgot'])->name('form.forgot');
    });
});

/* Patients */
Route::prefix('patient')->name('patient.')->group(function () {
    Route::middleware('patient')->group(function () {
        Route::get('/', [ViewPatientController::class, 'getDashBoard'])->name('home');
        Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/appointments', [ViewPatientController::class, 'getAppointments'])->name('appointments');
        Route::get('/schedule/timing', [ViewPatientController::class, 'getScheduleTiming'])->name('schedule.timing');
        Route::get('/payments', [ViewPatientController::class, 'getPayments'])->name('payments');
        Route::get('/messages', [ViewPatientController::class, 'getMessages'])->name('messages');
        Route::get('/settings', [SettingsPatientController::class, 'getSettings'])->name('settings');
        Route::get('/settings/change-password', [SettingsPatientController::class, 'getChangePassword'])->name('change.password');
        Route::get('/settings/notifications', [SettingsPatientController::class, 'getNotifications'])->name('notifications');
        Route::get('/settings/reviews', [SettingsPatientController::class, 'getReviews'])->name('reviews');

        /* Payments */
        Route::get('/monitoring', [ViewPatientController::class, 'getMonitoringForm'])->name('monitoring');
        Route::get('/checkout', [ViewPatientController::class, 'getCheckoutForm'])->name('checkout');
        /* STRIPE */
        Route::post('/payment/stripe', [StripeController::class, 'checkout'])->name('payment-stripe');
        Route::get('/payment/stripe/success', [StripeController::class, 'success'])->name('payment-stripe-success');
        /* PAYPAL */
        Route::post('/payment/paypal', [PaypalController::class, 'checkout'])->name('payment-paypal');
        Route::get('/payment/paypal/success', [PaypalController::class, 'success'])->name('payment-paypal-success');
        Route::get('/payment/paypal/cancel', [PaypalController::class, 'cancel'])->name('payment-paypal-cancel');

        /* JSON */
        Route::get('/doctor/list', [DoctorsController::class, 'doctorList'])->name('doctor.list');
        Route::get('/appointments/doctor', [AppointmentController::class, 'getAppointmentsByDoctor'])->name('appointments.doctor');

        /* POST */
        Route::post('appointment', [AppointmentController::class, 'store'])->name('appointment.store');
        Route::post('/payments/search', [PaymentsController::class, 'searchByDoctor'])->name('search-payment');
        Route::put('/settings/update/profile', [SettingsPatientController::class, 'updatePatient'])->name('settings.update.profile');
        Route::put('/settings/update/password', [SettingsPatientController::class, 'updatePassword'])->name('settings.update.password');
    });

    Route::middleware('patient.auth')->group(function () {
        Route::get('/login', [ViewPatientController::class, 'getLogin'])->name('login');
        Route::get('/forgot', [ViewPatientController::class, 'getForgot'])->name('forgot');

        Route::post('/login', [AuthController::class, 'login'])->name('form.login');
        Route::post('/forgot', [AuthController::class, 'forgot'])->name('form.forgot');
    });
});

/* Admins */
Route::name('admin.')->prefix('admin')->group(function () {
    Route::middleware(['admin'])->group(function () {
        Route::get('/', [ViewAdminController::class, 'getDashBoard'])->name('home');
        Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::resource('admins', UserController::class);
        Route::post('/admins/search', [UserController::class, 'searchUser'])->name('search.user');

        Route::resource('patients', PatientsController::class);
        Route::post('/patients/search', [PatientsController::class, 'search'])->name('search.patient');

        Route::resource('doctors', DoctorsController::class);
        Route::post('/doctors/search', [DoctorsController::class, 'search'])->name('search.doctor');

        Route::resource('payments', PaymentsController::class);
        Route::post('/payments/search', [PaymentsController::class, 'search'])->name('search.payment');

        Route::resource('medical', MedicalSpecialtyController::class);
        Route::post('/medical/search', [MedicalSpecialtyController::class, 'search'])->name('search.medical');
    });

    Route::middleware('admin.auth')->group(function () {
        Route::get('/login', [ViewAdminController::class, 'getSignIn'])->name('login');
        Route::get('/forgot', [ViewAdminController::class, 'getForgot'])->name('forgot');

        Route::post('/login', [AuthController::class, 'login'])->name('form.login');
        Route::post('/forgot', [AuthController::class, 'forgot'])->name('form.forgot');
    });
});
