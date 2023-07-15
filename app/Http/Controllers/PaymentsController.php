<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\User;
use App\Utils\Constants;
use Carbon\Carbon;
use DB;
use Faker\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;

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
    public function search(Request $request): JsonResponse
    {
        $doctorId = $request->query('doctorId');
        return $this->getPayments($request, $doctorId);
    }

    /**
     * @param Request $request
     * @param string $doctorId
     * @return JsonResponse
     */
    private function getPayments(Request $request, string $doctorId): JsonResponse
    {
        $limit = $request->query('limit', 10);

        if ($request->search) {
            $payments = Payment::query()
                ->with([
                    'patient',
                    'doctor',
                ])
                ->when($doctorId, function ($query, $doctorId) {
                    return $query->where('doctor_id', $doctorId);
                })
                ->where('payment_method', 'LIKE', "%{$request->search}%")
                ->orWhere('payment_status', 'LIKE', "%{$request->search}%")
                ->orderBy('created_at', 'desc')
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
                ->orderBy('created_at', 'desc')
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

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ApiErrorException
     */
    public function stripePayment(Request $request): RedirectResponse
    {
        $apiKey = config('environment.stripe.STRIPE_SECRET_KEY');
        $currency = config('environment.stripe.CURRENCY');
        Stripe::setApiKey($apiKey);

        $appointmentId = $request->appointment_id;

        $appointment = Appointment::query()
            ->with([
                'medicalSpecialty',
            ])
            ->where('id', $appointmentId)
            ->first();

        $amount = $appointment->medicalSpecialty->price * 100;

        $session = Session::create([
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => $currency,
                        'product_data' => [
                            'name' => $appointment->medicalSpecialty->name,
                        ],
                        'unit_amount' => $amount,
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => route('payment-stripe-success'),
            'cancel_url' => url()->previous(),
        ]);

        $request->session()->put('appointment_id', $appointmentId);
        $request->session()->put('transaction_id', $session->id);

        return redirect()->away($session->url);
    }

    /**
     * @param Request $request
     * @return View|Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
     */
    public function stripeSuccess(Request $request): View|Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $appointmentId = $request->session()->get('appointment_id');
        $transactionId = $request->session()->get('transaction_id');

        if (!$appointmentId || !$transactionId) {
            abort(404, 'Page not found');
        }

        $request->session()->forget('appointment_id');
        $request->session()->forget('transaction_id');

        $appointment = Appointment::query()
            ->with([
                'schedule',
                'doctor',
                'doctor.user',
                'patient',
                'patient.user',
                'medicalSpecialty',
            ])
            ->where('id', $appointmentId)
            ->first();

        Payment::query()
            ->create([
                'patient_id' => $appointment->patient_id,
                'doctor_id' => $appointment->doctor_id,
                'appointment_id' => $appointmentId,
                'amount' => $appointment->medicalSpecialty->price,
                'payment_method' => Constants::$STRIPE,
                'payment_status' => Constants::$PAYMENT_STATUS_PAID,
                'payment_date' => Carbon::now(),
                'transaction_id' => $transactionId,
            ]);

        // send email
        /*$email = '';*/
        /*$email = $appointment->patient->user->email;
        \Mail::to($email)->send(new PaymentSuccess($appointment));*/

        return view('pages/web/patients/detail')->with([
            'appointment' => $appointment,
        ]);
    }
}
