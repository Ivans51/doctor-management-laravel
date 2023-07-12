<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
}
