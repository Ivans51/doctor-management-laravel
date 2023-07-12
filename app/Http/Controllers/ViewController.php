<?php

namespace App\Http\Controllers;

use App\Charts\HomeChart;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Utils\Constants;
use Auth;
use Faker\Factory as Faker;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class ViewController extends Controller
{
    public function getDashBoard(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $userId = Auth::user()->id;

        $doctorId = Doctor::query()
            ->where('user_id', $userId)
            ->first()
            ->id;

        $patients = Patient::query()
            ->whereHas('doctorPatient', function ($query) use ($doctorId) {
                $query->where('doctor_id', $doctorId);
            })
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $patientsAll = Patient::query()
            ->whereHas('doctorPatient', function ($query) use ($doctorId) {
                $query->where('doctor_id', $doctorId);
            });

        $patientFemale = 0;
        $patientMale = 0;
        $collection = $patientsAll->get();
        foreach ($collection as $item) {
            if ($item->gender == Constants::$FEMALE) {
                $patientFemale++;
            } else {
                $patientMale++;
            }
        }

        $countPatients = $patientsAll
            ->count();

        $countAppointments = Appointment::query()
            ->where('doctor_id', $doctorId)
            ->count();

        $appointments = Appointment::query()
            ->with([
                'patient',
                'schedule',
            ])
            ->where('doctor_id', $doctorId)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $todayAppointments = Appointment::query()
            ->with([
                'patient',
                'schedule',
            ])
            ->whereHas('schedule', function ($query) {
                $query->whereDate('date', date('Y-m-d'));
            })
            ->where('doctor_id', $doctorId)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $chart = new HomeChart;
        $chart
            ->labels([Constants::$FEMALE, Constants::$MALE])
            ->dataset('My dataset', 'pie', [$patientFemale, $patientMale])
            ->backgroundColor(collect(['#ff6384', '#36a2eb']));

        return view('pages/web/main')->with([
            'patients' => $patients,
            'appointments' => $appointments,
            'appointmentsToday' => $todayAppointments,
            'countAppointments' => $countAppointments,
            'countPatients' => $countPatients,
            'chart' => $chart,
        ]);
    }

    public function getLogin(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages/web/auth/login');
    }

    public function getRegister(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages/web/auth/register');
    }

    public function getForgot(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages/web/auth/forgot');
    }

    public function getAppointments(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $files = [];

        $faker = Faker::create();

        for ($i = 0; $i <= 10; $i++) {
            $files[] = $faker->imageUrl(200, 200, 'people', false, true, 'lightblue');
        }

        return view('pages/web/appointments/index')->with([
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

        return view('pages/web/patients/index')->with([
            'images' => $files,
        ]);
    }

    public function getPatientsDetail(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $files = [];

        $faker = Faker::create();

        for ($i = 0; $i <= 10; $i++) {
            $files[] = $faker->imageUrl(200, 200, 'people', false, true, 'lightblue');
        }

        return view('pages/web/patients/detail')->with([
            'images' => $files,
        ]);
    }

    public function getMonitoringForm(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages/web/patients/monitoring-form');
    }

    public function getCheckoutForm(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages/web/patients/checkout-form');
    }

    public function getScheduleTiming(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages/web/schedules/index');
    }

    public function getPayments(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $files = [];

        $faker = Faker::create();

        for ($i = 0; $i <= 10; $i++) {
            $files[] = $faker->imageUrl(200, 200, 'people', false, true, 'lightblue');
        }

        return view('pages/web/payments/index')->with([
            'images' => $files,
        ]);
    }

    public function getMessages(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $files = [];

        $faker = Faker::create();

        for ($i = 0; $i <= 10; $i++) {
            $files[] = $faker->imageUrl(200, 200, 'people', false, true, 'lightblue');
        }

        return view('pages/web/messages/index')->with([
            'images' => $files,
        ]);
    }

    public function getBlog(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages/web/blog/index');
    }

    public function getSettings(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $files = [];

        $faker = Faker::create();

        for ($i = 0; $i <= 10; $i++) {
            $files[] = $faker->imageUrl(200, 200, 'people', false, true, 'lightblue');
        }

        return view('pages/web/settings/index')->with([
            'images' => $files,
        ]);
    }

    public function getChangePassword(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages/web/settings/change-password');
    }

    public function getNotifications(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $files = [];

        $faker = Faker::create();

        for ($i = 0; $i <= 10; $i++) {
            $files[] = $faker->imageUrl(200, 200, 'people', false, true, 'lightblue');
        }

        return view('pages/web/settings/notifications')->with([
            'images' => $files,
        ]);
    }

    public function getReviews(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $files = [];

        $faker = Faker::create();

        for ($i = 0; $i <= 10; $i++) {
            $files[] = $faker->imageUrl(200, 200, 'people', false, true, 'lightblue');
        }

        return view('pages/web/settings/reviews')->with([
            'images' => $files,
        ]);
    }

    public function postSettings(): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('my-patients-checkout');
    }
}
