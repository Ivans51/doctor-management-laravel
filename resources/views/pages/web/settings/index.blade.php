@extends('layouts.home')

@section('content')
    <div class="max-w-5xl mx-auto px-4">
        <h3 class="font-bold text-2xl text-gray-800 mb-10">My Profile</h3>

        <div id="settings" class="flex flex-col md:flex-row items-start space-x-0 md:space-x-6">
            <x-settings.profile-component :user="$user" :route="route('doctor.settings')"></x-settings.profile-component>

            <section class="w-full md:w-3/5 mt-10 md:mt-0">
                <div class="bg-white rounded-lg shadow-sm">
                    <ul id="menu-setting" class="flex space-x-2 md:space-x-5 p-3 border-b overflow-x-auto">
                        <li class="px-3 py-2 whitespace-nowrap"><a href="{{ route('doctor.settings') }}"
                                class="text-gray-600 hover:text-gray-900">My Profile</a></li>
                        <li class="px-3 py-2 whitespace-nowrap"><a href="{{ route('doctor.change.password') }}"
                                class="text-gray-600 hover:text-gray-900">Change Password</a></li>
                        <li class="px-3 py-2 whitespace-nowrap"><a href="{{ route('doctor.notifications') }}"
                                class="text-gray-600 hover:text-gray-900">Notifications</a></li>
                        <li class="px-3 py-2 whitespace-nowrap"><a href="{{ route('doctor.reviews') }}"
                                class="text-gray-600 hover:text-gray-900">Reviews</a></li>
                    </ul>

                    <div class="p-6">
                        <h2 class="font-bold text-lg text-gray-800 mb-6">My information</h2>

                        <x-utils.message-component />

                        <form action="{{ route('doctor.update.profile') }}" method="post">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="w-full">
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                    <input
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                        type="text" name="name" id="name" value="{{ $user->name }}" required>
                                </div>

                                <div class="w-full">
                                    <label for="address"
                                        class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                                    <input
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                        name="address" type="text" autocomplete="shipping address-line1" id="address"
                                        value="{{ $user->address }}" required>
                                </div>

                                <div class="w-full">
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                        type="email" name="email" id="email" value="{{ Auth::user()->email }}"
                                        required>
                                </div>

                                <div class="w-full">
                                    <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Phone
                                        Number</label>
                                    <input
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                        type="tel" name="phone_number" id="phone_number" value="{{ $user->phone }}"
                                        required>
                                </div>

                                <div class="w-full md:col-span-2">
                                    <label for="specialties" class="block text-sm font-medium text-gray-700 mb-1">Medical Specialties</label>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                        @foreach ($medicalSpecialties as $medicalSpecialty)
                                            <label class="flex items-center space-x-2">
                                                <input type="checkbox"
                                                       name="specialties[]"
                                                       value="{{ $medicalSpecialty->id }}"
                                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                                       {{ $myMedicalSpecialties->contains($medicalSpecialty->id) ? 'checked' : '' }}>
                                                <span>{{ $medicalSpecialty->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
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
                </div>
            </section>
        </div>
    </div>
@endsection

@push('scripts-bottom')
    <script src="{{ Vite::asset('resources/js/settings-menu.js') }}"></script>
    <script id="search-js" defer src="https://api.mapbox.com/search-js/v1.0.0/web.js"></script>
    <script>
        setMenuSetting(2)
    </script>

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

        document.querySelector('input[name="address"]').addEventListener('input', event => {});
    </script>
@endpush
