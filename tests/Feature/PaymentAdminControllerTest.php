<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Payment;
use App\Utils\Constants;
use App\Utils\Testing;
use Faker\Factory;
use Tests\TestCase;

class PaymentAdminControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        (new Testing())->createFakeUserWithRole(Constants::$ADMIN);
    }

    /**
     */
    public function test_payment_index(): void
    {
        $data = [
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->get(route('admin.payments.index', $data));

        $response->assertStatus(200);
    }

    /**
     */
    public function test_payment_create(): void
    {
        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->get(route('admin.payments.create'));

        $response->assertStatus(200);
    }

    /**
     */
    public function test_payment_store(): void
    {
        $faker = Factory::create();
        $doctorId = Doctor::query()->inRandomOrder()->first()->id;
        $patientId = Patient::query()->inRandomOrder()->first()->id;
        $appointmentId = Appointment::query()->inRandomOrder()->first()->id;

        $data = [
            'doctor_id' => $doctorId,
            'patient_id' => $patientId,
            'appointment_id' => $appointmentId,
            'amount' => $faker->randomFloat(2, 0, 100),
            'payment_method' => $faker->randomElement(Constants::$PAYMENT_METHODS),
            'payment_status' => $faker->randomElement([
                Constants::$PAYMENT_STATUS_CANCEL, Constants::$PAYMENT_STATUS_PAID
            ]),
            'payment_date' => $faker->date(),
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->post(route('admin.payments.store'), $data);

        $response->assertRedirect();
        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Created successfully');
    }

    /**
     */
    public function test_payment_show(): void
    {
        $paymentId = Payment::query()->inRandomOrder()->first()->id;

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->get(route('admin.payments.show', $paymentId));

        $response->assertStatus(200);
    }

    /**
     */
    public function test_payment_edit(): void
    {
        $paymentId = Payment::query()->inRandomOrder()->first()->id;

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->get(route('admin.payments.edit', $paymentId));

        $response->assertStatus(200);
    }

    /**
     */
    public function test_payment_destroy(): void
    {
        $paymentId = Payment::query()->orderBy('id', 'desc')->first()->id;

        $data = [
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->delete(route('admin.payments.destroy', $paymentId), $data);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
        ]);
    }

    /**
     */
    public function test_search_payments(): void
    {
        $data = [
            'limit' => 2,
            'search' => 'test',
            '_token' => Constants::$CSRF_TOKEN,
        ];

        $response = $this
            ->withSession(['_token' => Constants::$CSRF_TOKEN])
            ->post(route('admin.search.payment', $data));

        $response->assertJsonStructure([
            'status',
            'message',
            'data'
        ]);
        $response->assertStatus(200);
    }
}
