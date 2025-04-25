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
        try {
            $request->validate([
                'start' => 'required|date',
                'end' => 'required|date|after_or_equal:start',
            ]);

            $doctorId = \Auth::user()->doctor->id;
            $start_date = $request->input('start');
            $end_date = $request->input('end');

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
                'message' => 'Successfully retrieved schedule and appointments by doctor ID',
                'data' => $schedule
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    // get schedule and appointment by doctor id

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getScheduleByPatientId(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'start' => 'required|date',
                'end' => 'required|date|after_or_equal:start',
            ]);

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
                'message' => 'Successfully retrieved schedule and appointments by patient ID',
                'data' => $schedule
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
