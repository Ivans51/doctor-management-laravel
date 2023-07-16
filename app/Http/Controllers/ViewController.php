<?php

namespace App\Http\Controllers;

use App\Charts\HomeChart;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\MedicalSpecialty;
use App\Models\Patient;
use App\Utils\Constants;
use Auth;
use Faker\Factory as Faker;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

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
        $userId = Auth::user()->id;
        $doctorId = Doctor::query()
            ->where('user_id', $userId)
            ->first()
            ->id;

        $appointments = Appointment::query()
            ->with([
                'patient',
                'schedule',
            ])
            ->where('doctor_id', $doctorId)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('pages/web/appointments/index')->with([
            'appointments' => $appointments,
        ]);
    }

    public function getPatients(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages/web/patients/index');
    }

    /**
     * @param Request $request
     * @return View|Application|Factory|\Illuminate\Contracts\Foundation\Application
     */
    public function getMonitoringForm(Request $request): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        /*$patientId = Auth::user()->patient->id;*/
        $patientId = $request->query('patient_id', '');

        if (!$patientId) {
            abort('404', 'The post you are looking for was not found');
        }

        $patient = Patient::query()
            ->with([
                'user',
                'doctorPatient',
                'doctorPatient.doctor',
            ])
            ->where('id', $patientId)
            ->first();

        $medicalSpecialties = MedicalSpecialty::query()
            ->orderBy('name')
            ->get();

        if ($patient && $patient->date_of_birth) {
            $patient->years_old = date_diff(date_create($patient->date_of_birth), date_create())->y;
        }

        return view('pages/web/patients/monitoring-form', compact([
            'patient', 'medicalSpecialties'
        ]));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View|Application
     */
    public function getCheckoutForm(): \Illuminate\Contracts\Foundation\Application|Factory|View|Application
    {
        $appointmentId = request()->query('appointment_id');

        if (!$appointmentId) {
            abort('404', 'The post you are looking for was not found');
        }

        $appointment = Appointment::query()
            ->with([
                'patient',
                'schedule',
                'medicalSpecialty',
            ])
            ->where('id', $appointmentId)
            ->orderBy('created_at', 'desc')
            ->first();

        return view('pages/web/patients/checkout-form', compact('appointment'));
    }

    public function getScheduleTiming(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages/web/schedules/index');
    }

    public function getPayments(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages/web/payments/index');
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
}
