@extends('layouts.home')

@section('content')
    <div class="max-w-5xl mx-auto px-4">
        <h3 class="font-bold text-2xl text-gray-800 mb-10">Reviews</h3>

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
                        <h2 class="font-bold text-lg text-gray-800 mb-6">Reviews</h2>
                        @if (sizeof($reviews) == 0)
                            <x-utils.not-data title="No reviews" description="You don't have any reviews yet." />
                        @else
                            <div class="space-y-4">
                                @foreach ($reviews as $item)
                                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                                        <div class="flex justify-between items-center px-4 py-3 border-b border-gray-100">
                                            <div class="flex items-center">
                                                <img class="h-10 w-10 rounded-full object-cover mr-3"
                                                    src="{{ $item }}" alt="Profile image">
                                                <div>
                                                    <p class="font-medium text-gray-800">Jenny Wilson</p>
                                                    <p class="text-xs text-gray-500 mt-1">Message</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="flex space-x-1">
                                                    <x-ri-star-fill class="w-4 h-4 text-yellow-400" />
                                                    <x-ri-star-fill class="w-4 h-4 text-yellow-400" />
                                                    <x-ri-star-fill class="w-4 h-4 text-yellow-400" />
                                                    <x-ri-star-half-fill class="w-4 h-4 text-yellow-400" />
                                                    <x-ri-star-line class="w-4 h-4 text-yellow-400" />
                                                </div>
                                                <span class="text-xs text-gray-500 mt-1 block">04/04/23</span>
                                            </div>
                                        </div>
                                        <p class="p-4 text-gray-700">
                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet dolor ipsa
                                            laboriosam
                                            obcaecati tempore vitae voluptate. Ab ad aperiam impedit ipsa magnam molestiae,
                                            natus nulla
                                            porro repellendus, soluta unde, velit.
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('scripts-bottom')
    <script src="{{ Vite::asset('resources/js/settings-menu.js') }}"></script>
    <script>
        setMenuSetting(2)
    </script>
@endpush
