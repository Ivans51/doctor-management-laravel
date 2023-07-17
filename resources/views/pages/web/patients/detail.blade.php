@extends('layouts.home')

@section('content')
    <div class="flex items-start space-x-0 md:space-x-4">
        <section class="bg-white rounded p-6">
            <p class="text-zinc-400">
                Monitoring Plan
            </p>

            <h3 class="font-bold text-lg my-10">
                Thanks for your purchase, we will email you with the details of your appointment!.
            </h3>

            <div class="flex justify-center mb-6">
                <a href="{{ route('doctor.appointments') }}" class="bg-blue-400 text-white rounded px-4 py-1">
                    Back to home
                </a>
            </div>

            <div class="space-y-4 mb-6">
                <h5>Resume:</h5>
                <ul>
                    <li class="border-b border-gray-200">
                        <p class="text-zinc-400">Date</p>
                        <p>{{ date('d F Y', strtotime($appointment->schedule->date)) }}</p>
                    </li>
                    <li class="border-b border-gray-200">
                        <p class="text-zinc-400">Time</p>
                        <p>{{ $appointment->schedule->start_time }}</p>
                    </li>
                    <li class="border-b border-gray-200">
                        <p class="text-zinc-400">Doctor</p>
                        <p>{{ $appointment->doctor->name }}</p>
                    </li>
                    <li class="border-b border-gray-200">
                        <p class="text-zinc-400">Specialty</p>
                        <p>{{ $appointment->medicalSpecialty->name }}</p>
                    </li>
                    <li class="border-b border-gray-200">
                        <p class="text-zinc-400">Price</p>
                        <p>{{ $appointment->medicalSpecialty->price }}</p>
                    </li>
                </ul>
            </div>
        </section>
    </div>

@endsection
