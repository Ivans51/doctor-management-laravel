@extends('home')

@section('content')
    <h3 class="font-bold text-lg mb-10">Payment Info</h3>

    <div class="flex items-start space-x-0 md:space-x-4">
        <section class="w-3/5">
            <form action="{{ route('my-patient-detail-post') }}" method="post">
                @csrf <!-- add this to protect against CSRF attacks -->
                <div class="space-y-6">
                    <div class="w-full">
                        <label for="full_name">Full Name</label>
                        <input
                            class="border w-full bg-transparent"
                            type="text"
                            name="full_name"
                            id="full_name"
                        >
                    </div>

                    <div class="w-full">
                        <label for="credit_card_number">Credit Card Number</label>
                        <input
                            class="border w-full bg-transparent"
                            type="text"
                            name="credit_card_number"
                            id="credit_card_number"
                        >
                    </div>

                    <div class="grid grid-cols-2 gap-x-4">
                        <div>
                            <label for="exp_date">Exp Date</label>
                            <input
                                class="border w-full bg-transparent"
                                type="date"
                                name="exp_date"
                                id="exp_date"
                            >
                        </div>

                        <div>
                            <label for="cvv">CVV</label>
                            <input class="border w-full bg-transparent" type="text" name="cvv" id="cvv">
                        </div>
                    </div>

                    <div class="w-full">
                        <label for="zip_code">Zip Code</label>
                        <input
                            class="border w-full bg-transparent"
                            type="text"
                            name="zip_code"
                            id="zip_code"
                        >
                    </div>
                </div>

                <div class="flex items-center space-x-2 mt-10">
                    <button
                        type="submit"
                        class="rounded text-white bg-sky-500 px-4 py-1 text-sm ml-2 flex items-center flex-row w-full justify-center"
                    >
                        <x-ri-heart-add-fill class="w-6 h-6 mr-2"/>
                        Confirm Payment
                    </button>
                    <a
                        href="{{ route('my-patients-detail') }}"
                        class="rounded bg-white-500 px-4 py-1 w-full border text-center"
                    >
                        Cancel
                    </a>
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
