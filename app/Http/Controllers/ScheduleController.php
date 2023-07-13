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
        $doctor_id = \Auth::user()->doctor->id;
        $start_date = $request->start;
        $end_date = $request->end;

        $schedule = Schedule::query()
            ->with([
                'appointment',
                'appointment.patient',
            ])
            ->where('doctor_id', $doctor_id)
            ->whereBetween('date', [$start_date, $end_date])
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'success get schedule and appointment by doctor id',
            'data' => $schedule
        ]);
    }
}
