<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Payment;
use App\Utils\Constants;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeController extends Controller
{
    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function checkout(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'appointment_id' => 'required|exists:appointments,id',
            ]);

            $appointmentId = $request->appointment_id;
            $apiKey = config('environment.stripe.STRIPE_SECRET_KEY');
            $currency = config('environment.stripe.CURRENCY');
            Stripe::setApiKey($apiKey);

            $appointment = Appointment::query()
                ->with([
                    'medicalSpecialty',
                ])
                ->where('id', $appointmentId)
                ->firstOrFail();

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
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->route('patient.checkout')
                ->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()
                ->route('patient.checkout', ['appointment_id' => $request->appointment_id ?? ''])
                ->with('error', $e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function success(Request $request): RedirectResponse
    {
        try {
            $appointmentId = $request->session()->get('appointment_id');
            $transactionId = $request->session()->get('transaction_id');

            if (!$appointmentId || !$transactionId) {
                abort(404, 'Page not found');
            }

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
                ->firstOrFail();

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

            return redirect()->route('patient.payment-success');
        } catch (\Exception $e) {
            return redirect()
                ->route('patient.checkout', ['appointment_id' => $appointmentId ?? ''])
                ->with('error', $e->getMessage());
        }
    }
}
