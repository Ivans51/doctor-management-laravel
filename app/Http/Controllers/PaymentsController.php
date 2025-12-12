<?php

namespace App\Http\Controllers;

use App\Http\Requests\SavePaymentRequest;
use App\Models\Payment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $doctorId = $request->query("doctorId");
        return view("pages/admin/payments/index", compact("doctorId"));
    }

    /**
     * Search payments for the authenticated doctor.
     */
    public function searchByDoctor(Request $request): JsonResponse
    {
        $doctorId = Auth::user()->doctor->id;
        return $this->getPayments($request, $doctorId);
    }

    /**
     * Search payments for the authenticated patient.
     */
    public function searchByPatient(Request $request): JsonResponse
    {
        $patientId = Auth::user()->patient->id;
        return $this->getPayments($request, null, $patientId);
    }

    /**
     * Search payments, optionally filtered by a doctor.
     */
    public function search(Request $request): JsonResponse
    {
        $doctorId = $request->query("doctorId");
        return $this->getPayments($request, $doctorId);
    }

    /**
     * Get payments with optional filtering.
     */
    private function getPayments(
        Request $request,
        ?int $doctorId = null,
        ?int $patientId = null
    ): JsonResponse {
        $limit = $request->query('limit', 10);
        $search = $request->query('search');
        $cacheKey = "payments_{$doctorId}_{$patientId}_{$search}_{$limit}";

        // Cache for 5 minutes to improve performance
        $payments = Cache::remember($cacheKey, 300, function () use ($request, $doctorId, $patientId, $limit, $search) {
            $query = Payment::query()
                ->with(['patient', 'doctor'])
                ->when($doctorId, fn($q, $id) => $q->where('doctor_id', $id))
                ->when($patientId, fn($q, $id) => $q->where('patient_id', $id))
                ->when($search, fn($q, $term) => $q->where(function($subQ) use ($term) {
                    $subQ->where('payment_method', 'ILIKE', "%{$term}%")
                         ->orWhere('payment_status', 'ILIKE', "%{$term}%");
                }))
                ->orderBy('payment_date', 'desc');

            return $query->paginate($limit);
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Payments retrieved successfully',
            'data' => $payments,
            'meta' => [
                'current_page' => $payments->currentPage(),
                'last_page' => $payments->lastPage(),
                'per_page' => $payments->perPage(),
                'total' => $payments->total()
            ]
        ]);
    }

    /**
     * Store a newly created payment in storage.
     * @throws \Throwable
     */
    public function store(SavePaymentRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();
            Payment::create($validated);

            DB::commit();
            return redirect()->back()->with('success', 'Payment created successfully');
        } catch (ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment creation failed: ' . $e->getMessage(), [
                'data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    /**
     * Update the specified payment in storage.
     * @throws \Throwable
     */
    public function update(SavePaymentRequest $request, Payment $payment): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $payment->update($request->validated());
            DB::commit();

            return redirect()->back()->with('success', 'Payment updated successfully');
        } catch (ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment update failed: ' . $e->getMessage(), [
                'payment_id' => $payment->getKey(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    /**
     * Remove the specified payment from storage.
     * @throws \Throwable
     */
    public function destroy(Payment $payment): JsonResponse
    {
        try {
            DB::beginTransaction();
            $payment->delete();
            DB::commit();

            return response()->json([
                "status" => "success",
                "message" => "Payment deleted successfully"
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Payment deletion failed: ' . $e->getMessage(), [
                'payment_id' => $payment->getAttribute('id'),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                "status" => "error",
                "message" => "Failed to delete payment"
            ], 500);
        }
    }
}
