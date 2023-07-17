@extends('layouts.patient')

@section('content')
    <h3 class="font-bold text-lg mb-10">My Profile</h3>

    <div id="settings" class="flex flex-col md:flex-row items-start space-x-0 md:space-x-4">
        <x-settings.profile-component
            :user="$user"
            :route="route('patient.settings')"
        ></x-settings.profile-component>

        <section class="w-full md:w-3/5 mt-10 md:mt-0">
            <ul id="menu-setting" class="bg-white flex space-x-5">
                <li class="px-2 py-2"><a href="{{ route('patient.settings') }}">My Profile</a></li>
                <li class="px-2 py-2"><a href="{{ route('patient.change.password') }}">Change Password</a></li>
            </ul>

            <h2 class="font-bold mt-8 mb-4">My information</h2>

            <x-utils.message-component/>

            <form action="{{ route('patient.update.profile') }}" method="post">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div class="w-full">
                        <label for="name">Name</label>
                        <input
                            class="border w-full"
                            type="text"
                            name="name"
                            id="name"
                            value="{{ $user->name }}"
                        >
                    </div>

                    <div class="w-full">
                        <label for="address">Location</label>
                        <input
                            class="border w-full"
                            name="address"
                            type="text"
                            autocomplete="shipping address-line1"
                            id="address"
                            value="{{ $user->address }}"
                        >
                    </div>

                    <div class="w-full">
                        <label for="email">Email</label>
                        <input
                            class="border w-full"
                            type="email"
                            name="email"
                            id="email"
                            value="{{ Auth::user()->email }}"
                        >
                    </div>

                    <div class="w-full">
                        <label for="phone_number">Phone Number</label>
                        <input
                            class="border w-full"
                            type="tel"
                            name="phone_number"
                            id="phone_number"
                            value="{{ $user->phone }}"
                        >
                    </div>
                </div>

                <div class="flex items-center space-x-2 mt-10">
                    <button
                        type="submit"
                        class="rounded text-white bg-blue-500 px-4 py-1 w-full"
                    >
                        Save
                    </button>
                    <button
                        type="button"
                        class="rounded bg-white-500 px-4 py-1 w-full border modal-close"
                    >
                        Cancel
                    </button>
                </div>
            </form>
        </section>
    </div>
@endsection

@push('scripts-bottom')
    <script src="{{ Vite::asset('resources/js/settings-menu.js') }}"></script>
    <script id="search-js" defer src="https://api.mapbox.com/search-js/v1.0.0-beta.16/web.js"></script>
    <script>
        setMenuSetting(3)
    </script>

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

        document.querySelector('input[name="address"]').addEventListener('input', event => {
            /*console.log(`${event.target.value}`);*/
        });
    </script>
@endpush
