@extends('layouts.home')

@section('content')
    <h3 class="font-bold text-lg mb-10">Notifications</h3>

    <div id="settings" class="flex flex-col md:flex-row items-start space-x-0 md:space-x-4">
        <x-settings.profile-component
            :user="$user"
        ></x-settings.profile-component>

        <section class="w-full md:w-3/5 mt-10 md:mt-0">
            <ul id="menu-setting" class="bg-white flex space-x-5">
                <li class="px-2 py-2"><a href="{{ route('doctor.settings') }}">My Profile</a></li>
                <li class="px-2 py-2"><a href="{{ route('doctor.change.password') }}">Change Password</a></li>
                <li class="px-2 py-2"><a href="{{ route('doctor.notifications') }}">Notifications</a></li>
                <li class="px-2 py-2"><a href="{{ route('doctor.reviews') }}">Reviews</a></li>
            </ul>

            <h2 class="font-bold mt-8">Change Password</h2>

            <x-utils.message-component/>

            <form action="{{ route('doctor.update.password') }}" method="post">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div class="w-full">
                        <label for="password">Password</label>
                        <input
                            class="border w-full"
                            type="password"
                            name="password"
                            id="password"
                        >
                    </div>

                    <div class="w-full">
                        <label for="password_confirmation">Confirm Password</label>
                        <input
                            class="border w-full"
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
                        >
                    </div>
                </div>

                <div class="flex items-center space-x-20 mt-10">
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
@endpush
