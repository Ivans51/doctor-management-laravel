<?php

namespace App\Http\Controllers;

use App\Mail\PaymentSuccess;
use App\Models\Appointment;
use App\Models\Payment;
use App\Utils\Constants;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalController extends Controller
{
    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function checkout(Request $request): RedirectResponse
    {

        try {
            $appointmentId = $request->appointment_id;
            $currency = config('paypal.currency');
            $items = [];

            if (!$appointmentId) {
                abort(404, 'Page not found');
            }

            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            $appointment = Appointment::query()
                ->with([
                    'medicalSpecialty',
                ])
                ->where('id', $appointmentId)
                ->first();

            $amount = $appointment->medicalSpecialty->price;

            $items[] = [
                "reference_id" => 1,
                "items" => [
                    0 => [
                        "name" => $appointment->medicalSpecialty->name,
                        "quantity" => 1,
                        "unit_amount" => [
                            "currency_code" => $currency,
                            "value" => $amount
                        ]
                    ]
                ],
                "amount" => [
                    "currency_code" => $currency,
                    "value" => $amount,
                    "breakdown" => [
                        "item_total" => [
                            "currency_code" => $currency,
                            "value" => $amount
                        ]
                    ]
                ]
            ];

            $response = $provider->createOrder([
                "intent" => "CAPTURE",
                "application_context" => [
                    "return_url" => route('patient.payment-paypal-success'),
                    "cancel_url" => route('patient.payment-paypal-cancel'),
                ],
                "purchase_units" => $items
            ]);

            if (isset($response['id']) && $response['id'] != null) {
                foreach ($response['links'] as $links) {
                    if ($links['rel'] == 'approve') {
                        $request->session()->put('appointment_id', $appointmentId);
                        return redirect()->away($links['href']);
                    }
                }
                return redirect()
                    ->route('patient.checkout')
                    ->with('error', 'Something went wrong');
            } else {
                return redirect()
                    ->route('patient.checkout')
                    ->with('error', $response['message'] ?? 'Something went wrong');
            }
        } catch (\Exception $e) {
            return redirect()
                ->route('patient.checkout')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return View|Application|Factory|RedirectResponse|\Illuminate\Contracts\Foundation\Application
     * @throws \Throwable
     */
    public function success(Request $request): View|Application|Factory|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        try {
            $appointmentId = $request->session()->get('appointment_id');

            if (!$appointmentId) {
                abort(404, 'Page not found');
            }

            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            $response = $provider->capturePaymentOrder($request['token']);

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
                    'payment_method' => Constants::$PAYPAL,
                    'payment_status' => Constants::$PAYMENT_STATUS_PAID,
                    'payment_date' => Carbon::now(),
                    'transaction_id' => $response['id'],
                ]);

            $request->session()->forget('transaction_id');

            // send email
            $email = 'ivans51.test@gmail.com';
            /*$email = $appointment->patient->user->email;*/
            \Mail::to($email)->send(new PaymentSuccess($appointment));

            return view('pages/patient/checkout/detail')->with([
                'appointment' => $appointment,
            ]);
        } catch (\Exception $e) {
            return redirect()
                ->route('patient.checkout')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * @return string
     */
    public function cancel(): string
    {
        return 'Payment has been canceled';
    }
}
