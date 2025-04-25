@extends('layouts.patient')

@section('content')
    <div class="flex items-start space-x-0 md:space-x-4 justify-center">
        <section class="bg-white rounded-lg p-8 shadow-sm w-full max-w-xl mx-auto">
            <p class="text-zinc-400 text-base font-semibold mb-2">
                Monitoring Plan
            </p>

            <h3 class="font-bold text-xl my-8 text-gray-800">
                Thanks for your purchase, we will email you with the details of your appointment!
            </h3>

            <div class="flex justify-center mb-8">
                <a href="{{ route('patient.appointments') }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white rounded-md px-6 py-2 font-medium transition-colors">
                    Back to home
                </a>
            </div>

            <div class="mb-2">
                <h5 class="font-semibold text-gray-700 mb-3">Resume:</h5>
                <ul class="divide-y divide-gray-200">
                    <li class="py-3">
                        <p class="text-zinc-400 text-sm mb-1">Date</p>
                        <p class="font-medium text-gray-800">{{ date('d F Y', strtotime($appointment->schedule->date)) }}
                        </p>
                    </li>
                    <li class="py-3">
                        <p class="text-zinc-400 text-sm mb-1">Time</p>
                        <p class="font-medium text-gray-800">{{ $appointment->schedule->start_time }}</p>
                    </li>
                    <li class="py-3">
                        <p class="text-zinc-400 text-sm mb-1">Doctor</p>
                        <p class="font-medium text-gray-800">{{ $appointment->doctor->name }}</p>
                    </li>
                    <li class="py-3">
                        <p class="text-zinc-400 text-sm mb-1">Specialty</p>
                        <p class="font-medium text-gray-800">{{ $appointment->medicalSpecialty->name }}</p>
                    </li>
                    <li class="py-3">
                        <p class="text-zinc-400 text-sm mb-1">Price</p>
                        <p class="font-medium text-green-600">${{ number_format($appointment->medicalSpecialty->price, 2) }}
                        </p>
                    </li>
                </ul>
            </div>
        </section>
    </div>
@endsection
