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
    // get appointments by doctor id
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getAppointmentsByDoctor(Request $request): JsonResponse
    {
        $limit = $request->query('limit', 10);
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

        // add in request status
        $request->request->add(['status' => Constants::$PENDING]);

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

            Appointment::query()
                ->create([
                    'patient_id' => $request->patient_id,
                    'doctor_id' => $request->doctor_id,
                    'schedule_id' => $schedule->id,
                    'status' => $request->status,
                    'healthcare_provider' => $request->healthcare_provider,
                    'description' => $request->description,
                    'notes' => $request->notes,
                    'file' => $request->file,
                ]);

            DB::commit();

            return redirect()->back()->with('success', 'Appointment created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
}
