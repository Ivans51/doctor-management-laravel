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
        return view('pages/admin/payments/create');
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
                'amount' => 'required',
                'payment_method' => 'required',
                'payment_status' => 'required',
                'payment_date' => 'required',
            ]);

            Payment::query()
                ->create([
                    'patient_id' => $request->patient_id,
                    'doctor_id' => $request->doctor_id,
                    'amount' => $request->amount,
                    'payment_method' => $request->payment_method,
                    'payment_status' => $request->payment_status,
                    'payment_date' => $request->payment_date,
                ]);

            DB::commit();

            return redirect()->back()->with('success', 'User created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function show($id)
    {
        return view('pages/admin/patients/show');
    }

    /**
     * @param $id
     * @return View|Application|Factory|\Illuminate\Contracts\Foundation\Application
     */
    public function edit($id): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $payment = Payment::query()
            ->where('id', $id)
            ->first();

        return view('pages/admin/payments/edit', compact('payment'));
    }

    /**
     * @throws \Throwable
     */
    public function update(Request $request, $id): RedirectResponse
    {
        // validate fields
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|unique:users,email,' . $id,
            'password' => 'nullable|min:8|max:20',
        ]);

        try {
            DB::beginTransaction();
            $user = User::find($id);
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);
            if ($request->password) {
                $user->update([
                    'password' => bcrypt($request->password),
                ]);
            }

            Patient::query()
                ->where('user_id', $id)
                ->update([
                    'name' => $request->name,
                    'phone' => $request->phone_number,
                    'address' => $request->input('location_address-search'),
                    'status' => $request->status,
                ]);

            DB::commit();

            return redirect()->back()->with('success', 'Updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong');
        }
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
