<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Schedule;
use App\Utils\Constants;
use Auth;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Throwable;

class AppointmentController extends Controller
{
    // cancel appointment
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function changeStatusAppointment(Request $request): JsonResponse
    {
        $appointmentId = $request->input('appointment_id');
        $status = $request->input('status');
        $appointment = Appointment::query()->find($appointmentId);

        if ($appointment) {
            $appointment->status = $status;
            $appointment->save();

            return response()->json([
                'success' => true,
                'message' => 'Appointment cancelled successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Appointment not found'
        ]);
    }

    // get appointments by doctor id
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getAppointmentsByDoctor(Request $request): JsonResponse
    {
        $limit = $request->query('limit', 10);
        $doctorId = Auth::user()->doctor->id;

        $appointments = Appointment::query()
            ->with([
                'patient',
                'schedule',
            ])
            ->where('doctor_id', $doctorId)
            ->orderBy('created_at', 'desc')
            ->paginate($limit);

        return response()->json([
            'success' => true,
            'data' => $appointments
        ]);
    }

    /** get appointments by patient id
     * @param Request $request
     * @return JsonResponse
     */
    public function getAppointmentsByPatient(Request $request): JsonResponse
    {
        $limit = $request->query('limit', 10);
        $patientId = Auth::user()->patient->id;

        $appointments = Appointment::query()
            ->with([
                'patient',
                'schedule',
            ])
            ->where('patient_id', $patientId)
            ->orderBy('created_at', 'desc')
            ->paginate($limit);

        return response()->json([
            'success' => true,
            'data' => $appointments
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws Throwable
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'patient_id' => 'required',
            'doctor_id' => 'required',
            'description' => 'required',
            'date_consulting' => 'required|after:today',
            'start_time' => 'required|before:end_time',
            'end_time' => 'required',
        ]);

        try {
            DB::beginTransaction();

            // upload file if exist
            if ($request->hasFile('file')) {
                \Storage::disk('public')->putFileAs(
                    'files',
                    $request->file('file'),
                    $request->file('file')->getClientOriginalName()
                );
                $request->request->add(['file' => $request->file('file')->getClientOriginalName()]);
            }

            $schedule = Schedule::query()
                ->create([
                    'patient_id' => $request->patient_id,
                    'doctor_id' => $request->doctor_id,
                    'date' => $request->date_consulting,
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                ]);

            $appointment = Appointment::query()
                ->create([
                    'patient_id' => $request->patient_id,
                    'doctor_id' => $request->doctor_id,
                    'schedule_id' => $schedule->id,
                    'medical_specialty_id' => $request->medical_specialty_id,
                    'healthcare_provider' => $request->healthcare_provider,
                    'description' => $request->description,
                    'notes' => $request->notes,
                    'file' => $request->file,
                ]);

            DB::commit();

            return redirect()->route('patient.checkout', [
                'appointment_id' => $appointment->id,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
}
