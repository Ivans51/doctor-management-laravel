<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Payment;
use App\Utils\Constants;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;

class StripeController extends Controller
{
    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ApiErrorException
     */
    public function checkout(Request $request): RedirectResponse
    {
        try {
            $appointmentId = $request->appointment_id;

            if (!$appointmentId) {
                abort(404, 'Page not found');
            }

            $apiKey = config('environment.stripe.STRIPE_SECRET_KEY');
            $currency = config('environment.stripe.CURRENCY');
            Stripe::setApiKey($apiKey);

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
                'success_url' => route('patient.payment-stripe-success'),
                'cancel_url' => url()->previous(),
            ]);

            $request->session()->put('appointment_id', $appointmentId);
            $request->session()->put('transaction_id', $session->id);

            return redirect()->away($session->url);
        } catch (\Exception $e) {
            return redirect()
                ->route('patient.checkout', ['appointment_id' => $appointmentId ?? ''])
                ->with('error', $e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return View|Application|Factory|RedirectResponse|\Illuminate\Contracts\Foundation\Application
     */
    public function success(Request $request): View|Application|Factory|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        try {
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

            $appointment->update([
                'is_paid' => true,
            ]);

            // TODO: send email
            /*$email = '';*/
            /*$email = $appointment->patient->user->email;
            \Mail::to($email)->send(new PaymentSuccess($appointment));*/

            return view('pages/patient/checkout/detail')->with([
                'appointment' => $appointment,
            ]);
        } catch (\Exception $e) {
            return redirect()
                ->route('patient.checkout', ['appointment_id' => $appointmentId ?? ''])
                ->with('error', $e->getMessage());
        }
    }
}
