@extends('home')

@section('content')
    <h3 class="font-bold text-lg mb-10">My Profile</h3>

    <div class="flex flex-col md:flex-row items-start space-x-0 md:space-x-4">
        <x-settings.profile-component></x-settings.profile-component>

        <section class="w-full md:w-3/5 mt-10 md:mt-0">
            <ul class="bg-white px-2 py-2 flex space-x-5">
                <li><a href="{{ route('settings') }}">My Profile</a></li>
                <li><a href="{{ route('change-password') }}">Change Password</a></li>
                <li><a href="{{ route('notifications') }}">Notifications</a></li>
                <li><a href="{{ route('reviews') }}">Reviews</a></li>
            </ul>

            <h2 class="font-bold mt-8 mb-4">My information</h2>
            <form action="" method="post">
                <div class="space-y-6">
                    <div class="w-full">
                        <label for="first_name">First Name</label>
                        <input class="border w-full" type="text" name="first_name" id="first_name">
                    </div>

                    <div class="w-full">
                        <label for="last_name">Last Name</label>
                        <input class="border w-full" type="text" name="last_name" id="last_name">
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

                    <div class="w-full">
                        <label for="email">Email</label>
                        <input class="border w-full" type="email" name="email" id="email">
                    </div>

                    <div class="w-full">
                        <label for="phone_number">Phone Number</label>
                        <input class="border w-full" type="tel" name="phone_number" id="phone_number">
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
    <script id="search-js" defer src="https://api.mapbox.com/search-js/v1.0.0-beta.16/web.js"></script>

    <script>
        const script = document.getElementById('search-js');
        script.onload = function () {
            mapboxsearch.autofill({
                accessToken: 'pk.eyJ1IjoiaXZhbnM1MSIsImEiOiJjbGhmY21kN3kxOGJyM2VrMXRveHFicDJ4In0.TrpXPqd_UM9tC66Tnq_hLQ',
                options: {
                    language: 'es',
                },
            })
        };

        document.querySelector('input[name="location"]').addEventListener('input', event => {
            console.log(`${event.target.value}`);
        });
    </script>
@endpush
