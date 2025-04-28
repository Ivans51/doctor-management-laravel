<?php

namespace App\Http\Controllers;

use App\Charts\HomeChart;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Utils\Constants;
use Auth;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;

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
        $doctorId = Auth::user()->doctor->id;

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
        return view('pages/web/messages/index');
    }

    public function getBlog(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('pages/web/blog/index');
    }

    public function getDoctorResetPassword(Request $request):
        \Illuminate\Contracts\View\View|
        \Illuminate\Foundation\Application|
        \Illuminate\Contracts\View\Factory|
        \Illuminate\Contracts\Foundation\Application|
        \Illuminate\Http\RedirectResponse
    {
        $token = $request->query('token');
        $email = $request->query('email');

        if (!$token || !$email) {
            return redirect()->route('doctor.forgot')->withErrors(['error' => 'Invalid or missing password reset token.']);
        }

        $reset = \DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', $token)
            ->first();

        if (!$reset) {
            return redirect()->route('doctor.forgot')->withErrors(['error' => 'Invalid or expired password reset token.']);
        }

        return view('pages.web.auth.reset', compact('token', 'email'));
    }
}
