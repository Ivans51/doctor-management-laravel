@extends('layouts.admin')

@section('content')
    <div class="max-w-5xl mx-auto px-4">
        <h3 class="font-bold text-2xl text-gray-800 mb-10">Change Password</h3>

        <div id="settings" class="flex flex-col md:flex-row items-start space-x-0 md:space-x-6">
            <x-settings.profile-component
                :user="$user"
                :route="route('admin.settings')"
            ></x-settings.profile-component>

            <section class="w-full md:w-3/5 mt-10 md:mt-0">
                <div class="bg-white rounded-lg shadow-sm">
                    <ul id="menu-setting" class="flex space-x-2 md:space-x-5 p-3 border-b overflow-x-auto">
                        <li class="px-3 py-2 whitespace-nowrap"><a href="{{ route('admin.settings') }}" class="text-gray-600 hover:text-gray-900">My Profile</a></li>
                        <li class="px-3 py-2 whitespace-nowrap"><a href="{{ route('admin.change.password') }}" class="text-gray-600 hover:text-gray-900">Change Password</a></li>
                    </ul>

                    <div class="p-6">
                        <h2 class="font-bold text-lg text-gray-800 mb-6">Change Password</h2>

                        <x-utils.message-component/>

                        <form action="{{ route('admin.update.password') }}" method="post">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 gap-6">
                                <div class="w-full">
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                    <input
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                        type="password"
                                        name="password"
                                        id="password"
                                        required
                                    >
                                </div>

                                <div class="w-full">
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                                    <input
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                        type="password"
                                        name="password_confirmation"
                                        id="password_confirmation"
                                        required
                                    >
                                </div>
                            </div>

                            <div class="mt-8">
                                <button
                                    type="submit"
                                    class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md shadow-sm transition duration-150 ease-in-out"
                                >
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
    <script>
        setMenuSetting(3)
    </script>
@endpush
