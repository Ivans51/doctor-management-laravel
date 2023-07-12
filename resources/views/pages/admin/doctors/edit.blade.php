@php use App\Utils\Constants; @endphp
@extends('layouts.admin')

@section('content')
    <x-utils.message-component/>

    <section class="mt-10">
        <form action="{{ route('doctors.update', $doctor->user->id) }}" method="post">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div class="grid grid-cols-2 gap-x-4">
                    <div>
                        <label for="name">First Name</label>
                        <input
                            class="border w-full"
                            type="text"
                            name="name"
                            id="name"
                            value="{{ $doctor->name }}"
                        >
                    </div>
                    <div>
                        <label for="email">Email</label>
                        <input
                            class="border w-full"
                            type="email"
                            name="email"
                            id="email"
                            value="{{ $doctor->user->email }}"
                        >
                    </div>
                </div>

                <div class="w-full">
                    <label for="location">Location</label>
                    <input
                        class="border w-full"
                        name="location"
                        type="text"
                        autocomplete="shipping address-line1"
                        id="location"
                        value="{{ $doctor->address }}"
                    >
                </div>

                <div class="grid grid-cols-2 gap-x-4">
                    <div>
                        <label for="speciality">Speciality</label>
                        <input
                            class="border w-full"
                            type="text"
                            name="speciality"
                            id="speciality"
                            value="{{ $doctor->speciality }}"
                        >
                    </div>

                    <div>
                        <label for="phone_number">Phone Number</label>
                        <input
                            class="border w-full"
                            type="tel"
                            name="phone_number"
                            id="phone_number"
                            value="{{ $doctor->phone }}"
                        >
                    </div>
                </div>

                <div class="w-full">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="border w-full">
                        <option value="">Selection</option>
                        <option
                            value="{{ Constants::$ACTIVE }}"
                            {{ $doctor->status == Constants::$ACTIVE ? 'selected' : '' }}
                        >
                            Active
                        </option>
                        <option
                            value="{{ Constants::$INACTIVE }}"
                            {{ $doctor->status == Constants::$INACTIVE ? 'selected' : '' }}
                        >
                            Inactive
                        </option>
                    </select>
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
                        <label for="confirm_password">Confirm Password</label>
                        <input
                            class="border w-full"
                            type="password"
                            name="confirm_password"
                            id="confirm_password"
                            placeholder="*********"
                        >
                    </div>
                </div>
            </div>

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
            /*console.log(`${event.target.value}`);*/
        });
    </script>
@endpush
