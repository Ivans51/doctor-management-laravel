@extends('layouts.admin')

@section('content')
    <h3 class="font-bold text-lg mb-10">My Profile</h3>

    <div id="settings" class="flex flex-col md:flex-row items-start space-x-0 md:space-x-4">
        <x-settings.profile-component
            :user="$user"
            :route="route('admin.settings')"
        ></x-settings.profile-component>

        <section class="w-full md:w-3/5 mt-10 md:mt-0">
            <ul id="menu-setting" class="bg-white flex space-x-5">
                <li class="px-2 py-2"><a href="{{ route('admin.settings') }}">My Profile</a></li>
                <li class="px-2 py-2"><a href="{{ route('admin.change.password') }}">Change Password</a></li>
            </ul>

            <h2 class="font-bold mt-8 mb-4">My information</h2>

            <x-utils.message-component/>

            <form action="{{ route('admin.update.profile') }}" method="post">
                @csrf
                @method('PUT')

                <div class="space-y-6">
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
    <script>
        setMenuSetting(3)
    </script>
@endpush
