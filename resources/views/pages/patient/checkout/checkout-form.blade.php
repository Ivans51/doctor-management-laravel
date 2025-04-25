@php
    use Carbon\Carbon;
@endphp
@extends('layouts.patient')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h3 class="font-bold text-lg">Payment Info</h3>
        <a href="{{ url()->previous() }}"
            class="rounded text-gray-600 border border-gray-300 hover:bg-gray-100 px-4 py-2 text-sm flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 19l-7-7 7-7" />
            </svg>
            Back
        </a>
    </div>

    <x-utils.message-component />

    <div class="flex items-start space-x-0 md:space-x-4">
        <section class="w-3/5">
            <div class="bg-white rounded-lg p-6 shadow-sm mb-6">
                <h3 class="font-bold text-lg mb-4">Payment Details</h3>
                <ul class="space-y-4">
                    <li class="pb-3 border-b">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">ID</span>
                            <span class="font-medium">{{ $appointment->id }}</span>
                        </div>
                    </li>
                    <li class="pb-3 border-b">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Date</span>
                            <span class="font-medium">{{ $appointment->schedule->date }}</span>
                        </div>
                    </li>
                    <li class="pb-3 border-b">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Start time</span>
                            <span class="font-medium">{{ $appointment->schedule->start_time }}</span>
                        </div>
                    </li>
                    <li class="pb-3 border-b">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">End time</span>
                            <span class="font-medium">{{ $appointment->schedule->end_time }}</span>
                        </div>
                    </li>
                    <li class="pb-3 border-b">
                        <div class="flex flex-col">
                            <span class="text-sm text-gray-500">Healthcare Provider</span>
                            <span class="font-medium mt-1">{{ $appointment->healthcare_provider }}</span>
                        </div>
                    </li>
                    <li class="pb-3 border-b">
                        <div class="flex flex-col">
                            <span class="text-sm text-gray-500">Reason for Consulting</span>
                            <span class="font-medium mt-1">{{ $appointment->description }}</span>
                        </div>
                    </li>
                    <li class="pb-3">
                        <div class="flex flex-col">
                            <span class="text-sm text-gray-500">Review Notes</span>
                            <span class="font-medium mt-1">{{ $appointment->notes }}</span>
                        </div>
                    </li>
                    @if ($appointment->file)
                        <li class="pt-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">File</span>
                                <a href="{{ storage_path('app/public/files/' . $appointment->file) }}" target="_blank"
                                    class="rounded-md bg-red-100 hover:bg-red-200 text-red-800 px-4 py-2 text-sm flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                        <polyline points="7 10 12 15 17 10" />
                                        <line x1="12" y1="15" x2="12" y2="3" />
                                    </svg>
                                    Download
                                </a>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>

            <div class="bg-white rounded-lg p-6 shadow-sm">
                <h3 class="font-bold text-lg mb-4">Payment Method</h3>
                @if ($appointment->doctorMedicalSpecialty && $appointment->doctorMedicalSpecialty->medicalSpecialty)
                    @if ($appointment->medicalSpecialty)
                        <p class="text-gray-500 mb-4">Price: <span
                                class="font-medium text-green-600">${{ $appointment->medicalSpecialty->price }} USD</span>
                        </p>
                    @endif

                    <div class="flex space-x-4 mt-6">
                        <form action="{{ route('patient.payment-stripe') }}" method="post">
                            @csrf
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                            <button type="submit" id="checkout-live-button"
                                class="transition-transform hover:scale-105 border rounded-lg p-3 hover:border-blue-300 hover:shadow-md">
                                <img src="{{ Vite::asset('resources/img/checkout/icons8-stripe.png') }}"
                                    alt="Pay with Stripe" class="h-16">
                            </button>
                        </form>

                        <form action="{{ route('patient.payment-paypal') }}" method="post">
                            @csrf
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                            <button type="submit" id="checkout-live-button"
                                class="transition-transform hover:scale-105 border rounded-lg p-3 hover:border-blue-300 hover:shadow-md">
                                <img src="{{ Vite::asset('resources/img/checkout/icons8-paypal.png') }}"
                                    alt="Pay with PayPal" class="h-16">
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </section>

        <section class="w-2/5 bg-white rounded-lg p-6 shadow-sm">
            @if ($appointment->patient)
                <div class="flex flex-col items-center">
                    @if ($appointment->patient->profile == null)
                        <img class="mb-3 w-24 h-24 object-cover"
                            src="{{ Vite::asset('resources/img/icons8-male-user.png') }}" alt="profile patient"
                            style="border-radius: 50%">
                    @else
                        <img class="mb-3 w-24 h-24 object-cover"
                            src="{{ asset('storage/' . $appointment->patient->profile) }}" alt="profile patient"
                            style="border-radius: 50%">
                    @endif
                    <p class="font-bold text-lg">{{ $appointment->patient->name }}</p>
                    <p class="text-zinc-500 text-sm">{{ $appointment->patient->years_old }}
                        Years, {{ $appointment->patient->gender }}</p>
                </div>

                <hr class="my-4">

                <div class="space-y-4">
                    <div>
                        <p class="text-zinc-500 text-sm">Email</p>
                        <p class="font-medium">{{ $appointment->patient->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-zinc-500 text-sm">Phone</p>
                        <p class="font-medium">{{ $appointment->patient->phone }}</p>
                    </div>
                    <div>
                        <p class="text-zinc-500 text-sm">Date of Birth</p>
                        <p class="font-medium">
                            {{ Carbon::parse($appointment->patient->date_of_birth)->format('d F Y') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-zinc-500 text-sm">Diseases</p>
                        <p class="font-medium text-gray-600">Not found</p>
                    </div>
                </div>

                <hr class="my-4">
            @endif
        </section>
    </div>
@endsection
