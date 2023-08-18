@php
    use Carbon\Carbon;
@endphp
@extends('layouts.patient')

@section('content')
    <x-utils.message-component/>

    <div class="flex items-start space-x-0 md:space-x-4">
        <section class="w-3/5">
            <h3 class="font-bold text-lg mb-2">Payment Info</h3>
            <ul class="space-y-2">
                <li class="border-b-2"><strong>ID: </strong> {{$appointment->id}}</li>
                <li class="border-b-2">
                    <strong>Date: </strong> {{$appointment->schedule->date}}
                </li>
                <li class="border-b-2">
                    <strong>Start time: </strong> {{$appointment->schedule->start_time}}
                </li>
                <li class="border-b-2">
                    <strong>End time: </strong> {{$appointment->schedule->end_time}}
                </li>
                <li class="border-b-2">
                    <strong>Healthcare Provider: </strong> {{$appointment->healthcare_provider}}
                </li>
                <li class="border-b-2">
                    <strong>Reason for Consulting: </strong> {{$appointment->description}}
                </li>
                <li>
                    <strong>Review Notes: </strong> {{$appointment->notes}}
                </li>
                @if($appointment->file)
                    <li>
                        <strong>File: </strong>
                        <a
                            href="{{ storage_path('app/public/files/' . $appointment->file) }}"
                            target="_blank"
                            class="rounded text-red-900 bg-red-100 px-4 py-1 text-sm"
                        >
                            Download
                        </a>
                    </li>
                @endif
            </ul>

            <h3 class="font-bold text-lg mb-2 mt-10">Method Payment</h3>
            @if($appointment->doctorMedicalSpecialty && $appointment->doctorMedicalSpecialty->medicalSpecialty)
                @if($appointment->medicalSpecialty)
                    <p class="text-zinc-400">Price: ${{ $appointment->medicalSpecialty->price }} USD</p>
                @endif

                <div class="flex space-x-2">
                    <form action="{{ route('patient.payment-stripe') }}" method="post">
                        @csrf <!-- add this to protect against CSRF attacks -->
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input
                            type="hidden"
                            name="appointment_id"
                            value="{{ $appointment->id }}"
                        >
                        <button type="submit" id="checkout-live-button">
                            <img
                                src="{{ Vite::asset('resources/img/checkout/icons8-stripe.png') }}"
                                alt="icons stripe"
                                class="h-20"
                            >
                        </button>
                    </form>

                    <form action="{{ route('patient.payment-paypal') }}" method="post">
                        @csrf <!-- add this to protect against CSRF attacks -->
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input
                            type="hidden"
                            name="appointment_id"
                            value="{{ $appointment->id }}"
                        >
                        <button type="submit" id="checkout-live-button">
                            <img
                                src="{{ Vite::asset('resources/img/checkout/icons8-paypal.png') }}"
                                alt="icons paypal"
                                class="h-20"
                            >
                        </button>
                    </form>
                </div>
            @endif
        </section>

        <section class="w-2/5 bg-white rounded p-6">
            <div class="flex flex-col items-center">
                @if($appointment->patient->profile == null)
                    <img
                        class="mb-2 w-24 h-24"
                        src="{{ Vite::asset('resources/img/icons8-male-user.png') }}"
                        alt="profile patient"
                        style="border-radius: 50%"
                    >
                @else
                    <img
                        class="h-10 mr-3"
                        src="{{asset('storage/'.$appointment->patient->profile)}}"
                        alt="profile patient"
                        style="border-radius: 50%"
                    >
                @endif
                <p class="font-bold">{{ $appointment->patient->name }}</p>
                <p class="text-zinc-400">{{ $appointment->patient->years_old }}
                    Years, {{ $appointment->patient->gender }}</p>
            </div>

            <hr class="my-4">

            <div class="space-y-4">
                <div>
                    <p class="text-zinc-400">Email</p>
                    <p>{{ $appointment->patient->user->email }}</p>
                </div>
                <div>
                    <p class="text-zinc-400">Phone</p>
                    <p>{{ $appointment->patient->phone }}</p>
                </div>
                <div>
                    <p class="text-zinc-400">Date of Birth</p>
                    <p>
                        {{ Carbon::parse($appointment->patient->date_of_birth)->format('d F Y') }}
                    </p>
                </div>
                <div>
                    <p class="text-zinc-400">Diseases</p>
                    <p>Not found</p>
                </div>
            </div>

            <hr class="my-4">
        </section>
    </div>

@endsection
