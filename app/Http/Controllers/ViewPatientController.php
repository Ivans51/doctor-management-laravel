<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\MedicalSpecialty;
use App\Models\Patient;
use Auth;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

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

    public function getAppointments(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $patientId = Auth::user()->patient->id;

        $appointments = Appointment::query()
            ->with([
                'patient',
                'schedule',
            ])
            ->where('patient_id', $patientId)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('pages/patient/appointments/index')->with([
            'appointments' => $appointments,
        ]);
    }

    /**
     * @param Request $request
     * @return View|Application|Factory|\Illuminate\Contracts\Foundation\Application
     */
    public function getMonitoringForm(Request $request): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $patientId = Auth::user()->patient->id;

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

        return view('pages/patient/checkout/monitoring-form', compact([
            'patient', 'medicalSpecialties'
        ]));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|Factory|View|Application|\Illuminate\Http\RedirectResponse
     */
    public function getCheckoutForm()
    {
        try {
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

            return view('pages/patient/checkout/checkout-form', compact('appointment'));
        } catch (\Exception $e) {
            return redirect()->route('patient.appointments')->with([
                'error' => 'Error: ' . $e->getMessage(),
            ]);
        }
    }

    public function getScheduleTiming(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages/patient/schedules/index');
    }

    public function getPayments(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages/patient/payments/index');
    }

    public function getMessages(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages/patient/messages/index');
    }
}
