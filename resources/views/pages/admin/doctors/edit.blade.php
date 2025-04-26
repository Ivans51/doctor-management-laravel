@php use App\Utils\Constants; @endphp
@extends('layouts.admin')

@section('content')
    <section class="max-w-3xl mx-auto mt-10 px-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Edit Doctor</h1>
            <a href="{{ route('admin.doctors.index') }}" class="flex items-center text-gray-600 hover:text-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                        clip-rule="evenodd" />
                </svg>
                Back
            </a>
        </div>

        <x-utils.message-component />

        <div class="bg-white p-6 rounded-lg shadow-md">
            <form action="{{ route('admin.doctors.update', $doctor->user->id) }}" method="post">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="w-full">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                        <input
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            type="text" name="name" id="name" value="{{ $doctor->name }}" required>
                    </div>
                    <div class="w-full">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            type="email" name="email" id="email" value="{{ $doctor->user->email }}" required>
                    </div>
                    <div class="w-full md:col-span-2">
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                        <input
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            name="location" type="text" autocomplete="shipping address-line1" id="location"
                            value="{{ $doctor->address }}">
                    </div>
                    <div class="w-full">
                        <label for="speciality" class="block text-sm font-medium text-gray-700 mb-1">Speciality</label>
                        <input
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            type="text" name="speciality" id="speciality" value="{{ $doctor->speciality }}">
                    </div>
                    <div class="w-full">
                        <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <input
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            type="tel" name="phone_number" id="phone_number" value="{{ $doctor->phone }}">
                        <input type="hidden" name="full_phone_number" id="full_phone_number" value="{{ $doctor->phone }}">
                    </div>
                    <div class="w-full md:col-span-2">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Selection</option>
                            <option value="{{ Constants::$ACTIVE }}"
                                {{ $doctor->status == Constants::$ACTIVE ? 'selected' : '' }}>Active</option>
                            <option value="{{ Constants::$INACTIVE }}"
                                {{ $doctor->status == Constants::$INACTIVE ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="w-full">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            type="password" name="password" id="password" placeholder="*********">
                    </div>
                    <div class="w-full">
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm
                            Password</label>
                        <input
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            type="password" name="confirm_password" id="confirm_password" placeholder="*********">
                    </div>
                </div>

                <div class="mt-8">
                    <button type="submit"
                        class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md shadow-sm transition duration-150 ease-in-out">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('scripts-bottom')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/css/intlTelInput.css">
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/intlTelInput.min.js"></script>
    <script src="{{ Vite::asset('resources/js/phone-input.js') }}"></script>
    <script id="search-js" defer src="https://api.mapbox.com/search-js/v1.0.0-beta.16/web.js"></script>
    <script>
        const script = document.getElementById('search-js');
        script.onload = function() {
            mapboxsearch.autofill({
                accessToken: '{{ config('services.mapbox.token') }}',
                options: {
                    language: 'es',
                },
            })
        };
        document.querySelector('input[name="location"]').addEventListener('input', event => {});

        initPhoneInput();
    </script>
@endpush
