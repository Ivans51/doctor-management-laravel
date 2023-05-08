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

            <h2 class="font-bold mt-8">Change Password</h2>
            <form action="" method="post">
                <div class="space-y-6">
                    <div class="w-full">
                        <label for="password">Password</label>
                        <input class="border w-full" type="password" name="password" id="password">
                    </div>

                    <div class="w-full">
                        <label for="confirm_password">Confirm Password</label>
                        <input class="border w-full" type="password" name="confirm_password" id="confirm_password">
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
