@extends('home')

@section('content')
    <h3 class="font-bold text-lg mb-10">Monitoring Plan</h3>

    <div class="flex items-start space-x-0 md:space-x-4">
        <section class="w-3/5">
            <form action="" method="post">
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-x-4">
                        <div>
                            <label for="first_name">First Name</label>
                            <input class="border w-full" type="text" name="first_name" id="first_name">
                        </div>

                        <div>
                            <label for="last_name">Last Name</label>
                            <input class="border w-full" type="text" name="last_name" id="last_name">
                        </div>
                    </div>

                    <div class="w-full">
                        <label for="location">Location</label>
                        <input class="border w-full" type="text" name="location" id="location">
                    </div>

                    <div class="grid grid-cols-2 gap-x-4">
                        <div>
                            <label for="email">Email</label>
                            <input class="border w-full" type="email" name="email" id="email">
                        </div>

                        <div>
                            <label for="phone_number">Phone Number</label>
                            <input class="border w-full" type="tel" name="phone_number" id="phone_number">
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-2 mt-10">
                    <button
                        type="submit"
                        class="rounded text-white bg-blue-500 px-4 py-1 w-full"
                    >
                        <x-ri-heart-add-fill/>
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

        <section class="w-2/5 bg-white rounded p-6">
            <div class="flex flex-col items-center">
                <img
                    class="mb-2 w-24 h-24"
                    src="{{ Vite::asset('resources/img/home/logo.png') }}"
                    alt="patient profile image"
                    style="border-radius: 50%"
                >
                <p class="font-bold">Mr. Jone Martin</p>
                <p class="text-zinc-400">22 Years, Male</p>
            </div>

            <hr class="my-4">

            <div class="space-y-4">
                <div>
                    <p class="text-zinc-400">Email</p>
                    <p>test@gmail.com</p>
                </div>
                <div>
                    <p class="text-zinc-400">Phone</p>
                    <p>(707) 555-0710</p>
                </div>
                <div>
                    <p class="text-zinc-400">Date of Birth</p>
                    <p>14 February 2021</p>
                </div>
                <div>
                    <p class="text-zinc-400">Diseases</p>
                    <p>Cardiology</p>
                </div>
            </div>

            <hr class="my-4">
        </section>
    </div>

@endsection
