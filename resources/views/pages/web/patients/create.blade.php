@extends('layouts.home')

@section('content')
    <section class="mt-10">
        <x-utils.message-component/>

        <form action="{{ route('doctor.my-patients-doctor.store') }}" method="post">
            @csrf

            <div class="space-y-6">
                <div class="w-full">
                    <label for="name">First Name</label>
                    <input class="border w-full" type="text" name="name" id="name">
                </div>

                <div class="w-full">
                    <label for="location">Location</label>
                    <input
                        class="border w-full"
                        name="location"
                        type="text"
                        autocomplete="shipping address-line1"
                        id="location">
                </div>

                <div class="grid grid-cols-2 gap-x-4">
                    <div>
                        <label for="email">Email</label>
                        <input class="border w-full" type="email" name="email" id="email">
                    </div>

                    <div>
                        <label for="phone_number">Phone Number</label>
                        <input class="border w-full" type="tel" name="phone_number" id="phone_number">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-x-4">
                    <div>
                        <label for="password">Password</label>
                        <input
                            class="border w-full"
                            type="password"
                            name="password"
                            id="password"
                            placeholder="*********"
                        >
                    </div>
                    <div>
                        <label for="password_confirmation">Confirm Password</label>
                        <input
                            class="border w-full"
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
                            placeholder="*********"
                        >
                    </div>
                </div>
            </div>

            <input type="hidden" name="doctor_id" value="{{ Auth::user()->doctor->id }}">

            <div class="flex items-center space-x-2 mt-10">
                <button
                    type="submit"
                    class="rounded text-white bg-blue-500 px-4 py-1 w-full"
                >
                    Save
                </button>
                <a
                    href="{{ url()->previous() }}"
                    class="rounded bg-white-500 px-4 py-1 w-full border modal-close text-center"
                >
                    Cancel
                </a>
            </div>
        </form>
    </section>
@endsection

@push('scripts-bottom')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/css/intlTelInput.css">
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/intlTelInput.min.js"></script>
    <script src="{{ Vite::asset('resources/js/phone-input.js') }}"></script>
    <script id="search-js" defer src="https://api.mapbox.com/search-js/v1.0.0-beta.16/web.js"></script>

    <script>
        const script = document.getElementById('search-js');

        script.onload = function () {
            mapboxsearch.autofill({
                accessToken: '{{ config('services.mapbox.token') }}',
                options: {
                    language: 'es',
                },
            })
        };

        document.querySelector('input[name="location"]').addEventListener('input', event => {
        });
    </script>
@endpush
