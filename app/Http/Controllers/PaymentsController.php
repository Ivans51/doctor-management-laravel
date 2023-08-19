<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Payment;
use App\Models\User;
use DB;
use Faker\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    /**
     * @param Request $request
     * @return View|Application|Factory|\Illuminate\Contracts\Foundation\Application
     */
    public function index(Request $request): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $doctorId = $request->query('doctorId');
        return view('pages/admin/payments/index', compact('doctorId'));
    }

    /**
     * Search user with parameter email and name
     * @param Request $request
     * @return JsonResponse
     */
    public function searchByDoctor(Request $request): JsonResponse
    {
        $doctorId = \Auth::user()->doctor->id;
        return $this->getPayments($request, $doctorId);
    }

    /**
     * Search user with parameter email and name
     * @param Request $request
     * @return JsonResponse
     */
    public function searchByPatient(Request $request): JsonResponse
    {
        $patientId = \Auth::user()->patient->id;
        return $this->getPayments($request, null, $patientId);
    }

    /**
     * Search user with parameter email and name
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $doctorId = $request->query('doctorId', '');
        return $this->getPayments($request, $doctorId);
    }

    /**
     * @param Request $request
     * @param string $doctorId
     * @param string|null $patientId
     * @return JsonResponse
     */
    private function getPayments(Request $request, mixed $doctorId, string $patientId = null): JsonResponse
    {
        $limit = $request->query('limit', 10);

        if ($request->query('search')) {
            $payments = Payment::query()
                ->with([
                    'patient',
                    'doctor',
                ])
                ->when($doctorId, function ($query, $doctorId) {
                    return $query->where('doctor_id', $doctorId);
                })
                ->when($patientId, function ($query, $patientId) {
                    return $query->where('patient_id', $patientId);
                })
                ->where('payment_method', 'LIKE', "%{$request->search}%")
                ->orWhere('payment_status', 'LIKE', "%{$request->search}%")
                ->orderBy('payment_date', 'desc')
                ->paginate($limit);
        } else {
            $payments = Payment::query()
                ->with([
                    'patient',
                    'doctor',
                ])
                ->when($doctorId, function ($query, $doctorId) {
                    return $query->where('doctor_id', $doctorId);
                })
                ->when($patientId, function ($query, $patientId) {
                    return $query->where('patient_id', $patientId);
                })
                ->orderBy('payment_date', 'desc')
                ->paginate($limit);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data retrieved successfully',
            'data' => $payments
        ]);
    }

    public function create()
    {

    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'appointment_id' => 'required',
                'patient_id' => 'required',
                'doctor_id' => 'required',
                'amount' => 'required',
                'payment_method' => 'required',
                'payment_status' => 'required',
                'payment_date' => 'required',
            ]);

            Payment::query()
                ->create([
                    'appointment_id' => $request->appointment_id,
                    'patient_id' => $request->patient_id,
                    'doctor_id' => $request->doctor_id,
                    'amount' => $request->amount,
                    'payment_method' => $request->payment_method,
                    'payment_status' => $request->payment_status,
                    'payment_date' => $request->payment_date,
                ]);

            DB::commit();

            return redirect()->back()->with('success', 'Created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function show($id)
    {

    }

    /**
     * @param $id
     */
    public function edit($id)
    {
    }

    /**
     * @throws \Throwable
     */
    public function update(Request $request, $id): RedirectResponse
    {
    }

    /**
     * @param $id
     * @return JsonResponse
     * @throws \Throwable
     */
    public function destroy($id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $patient = Patient::query()->where('id', $id);

            User::query()
                ->where('id', $patient->first()->user_id)
                ->delete();

            $patient->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Data deleted successfully',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Data failed to delete',
                'error' => $e->getMessage()
            ]);
        }
    }
}
