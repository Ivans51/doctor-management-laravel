@extends('home')

@section('content')
    <h3 class="font-bold text-lg mb-10">Notifications</h3>

    <div class="flex flex-col md:flex-row items-start space-x-0 md:space-x-4">
        <x-settings.profile-component></x-settings.profile-component>

        <section class="w-full md:w-3/5 mt-10 md:mt-0">
            <ul class="bg-white px-2 py-2 flex space-x-5">
                <li><a href="{{ route('settings') }}">My Profile</a></li>
                <li><a href="{{ route('change-password') }}">Change Password</a></li>
                <li><a href="{{ route('notifications') }}">Notifications</a></li>
                <li><a href="{{ route('reviews') }}">Reviews</a></li>
            </ul>

            <h2 class="font-bold mt-8">Reviews</h2>
            @foreach($images as $image)
                <div class="bg-white rounded px-4 py-2 my-3">
                    <div class="flex justify-between items-center bg-white px-2 py-2 cursor-pointer">
                        <div class="flex items-center">
                            <img class="h-10 mr-3" src="{{$image}}" alt="image animal" style="border-radius: 50%">
                            <div>
                                <p>Jenny Wilson</p>
                                <p class="text-xs mt-1">Message</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="flex space-x-2">
                                <x-ri-star-fill class="w-4 h-4 text-yellow-400"/>
                                <x-ri-star-fill class="w-4 h-4 text-yellow-400"/>
                                <x-ri-star-fill class="w-4 h-4 text-yellow-400"/>
                                <x-ri-star-half-fill class="w-4 h-4 text-yellow-400"/>
                                <x-ri-star-line class="w-4 h-4 text-yellow-400"/>
                            </div>
                            <span class="text-xs">04/04/23</span>
                        </div>
                    </div>
                    <p class="p-4">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet dolor ipsa laboriosam
                        obcaecati tempore vitae voluptate. Ab ad aperiam impedit ipsa magnam molestiae, natus nulla
                        porro repellendus, soluta unde, velit.
                    </p>
                </div>
            @endforeach
        </section>
    </div>
@endsection
