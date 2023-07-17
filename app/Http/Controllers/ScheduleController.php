<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    // get schedule and appointment by doctor id
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getScheduleByDoctorId(Request $request): JsonResponse
    {
        $doctorId = \Auth::user()->doctor->id;
        $start_date = $request->start;
        $end_date = $request->end;

        $schedule = Schedule::query()
            ->with([
                'appointment',
                'appointment.patient',
            ])
            ->where('doctor_id', $doctorId)
            ->whereBetween('date', [$start_date, $end_date])
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'success get schedule and appointment by doctor id',
            'data' => $schedule
        ]);
    }

    // get schedule and appointment by doctor id
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getScheduleByPatientId(Request $request): JsonResponse
    {
        $patientId = \Auth::user()->patient->id;
        $start_date = $request->start;
        $end_date = $request->end;

        $schedule = Schedule::query()
            ->with([
                'appointment',
                'appointment.patient',
            ])
            ->whereHas('appointment', function ($query) use ($patientId) {
                $query->where('patient_id', $patientId);
            })
            ->whereBetween('date', [$start_date, $end_date])
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'success get schedule and appointment by doctor id',
            'data' => $schedule
        ]);
    }
}
