@extends('home')

@section('content')
    <section class="mt-10">
        <div class="flex justify-between">
            <h3 class="my-2 font-bold text-lg">Patient list</h3>
            <button
                id="modal-open"
                class="rounded text-white bg-violet-500 px-4 py-1 text-sm ml-2 flex items-center flex-row"
            >
                <x-ri-heart-add-fill/>
                <span>Add Patient</span>
            </button>
        </div>

        <div class="my-4 flex flex-col lg:flex-row space-x-0 lg:space-x-4 space-y-2 lg:space-y-0 w-full">
            <label>Show
                <select class="bg-white p-1 border ml-2" name="show" id="show">
                    <option value="10">10</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </label>
        </div>

        <div class="my-4 flex flex-col lg:flex-row space-x-0 lg:space-x-4 space-y-2 lg:space-y-0 w-full">
            <label class="w-full lg:w-2/3">
                Search
                <input class="w-full" type="search" placeholder="Patient name, status, type">
            </label>
            <label class="w-full lg:w-1/3">
                Date
                <input class="w-full" type="date">
            </label>
        </div>

        <table class="w-full table-auto" style="border-collapse: separate; border-spacing: 0 8px;">
            <tr class="bg-zinc-100">
                <th class="px-4 py-1 text-left">Patient Name</th>
                <th class="px-4 py-1">Visit Id</th>
                <th class="px-4 py-1">Date</th>
                <th class="px-4 py-1">Gender</th>
                <th class="px-4 py-1">Diseases</th>
                <th class="px-4 py-1">Status</th>
            </tr>
            @foreach($images as $image)
                <tr class="bg-white rounded">
                    <td class="px-4 py-2 flex items-center">
                        <img class="h-10 mr-3" src="{{$image}}" alt="image animal" style="border-radius: 50%">
                        <a href="{{ route('my-patients-detail') }}">
                            Jenny Wilson
                        </a>
                    </td>
                    <td class="px-4 py-2 text-center">
                        1
                    </td>
                    <td class="px-4 py-2 text-center">
                        1
                    </td>
                    <td class="px-4 py-2 text-center">
                        1
                    </td>
                    <td class="px-4 py-2 text-center">
                        1
                    </td>
                    <td class="px-4 py-2 text-center">
                        <a
                            href="{{ route('my-patients-monitoring') }}"
                            class="rounded text-red-900 bg-red-100 px-4 py-1 text-sm ml-2"
                        >
                            Log monitoring
                        </a>
                        <a
                            href="{{ route('messages') }}"
                            class="rounded text-red-900 bg-red-100 px-4 py-1 text-sm ml-2"
                        >
                            Message
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>
    </section>

    <x-modal.create-patient-component
        title="Add new patient"
        modalClass="modal"
    >
        <x-slot name="content">
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
        </x-slot>
    </x-modal.create-patient-component>

@endsection

@push('scripts-bottom')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/css/intlTelInput.css">
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/intlTelInput.min.js"></script>
    <script>
        const input = document.querySelector("#phone_number");
        window.intlTelInput(input, {
            initialCountry: 'auto',
            geoIpLookup: callback => {
                fetch("https://ipapi.co/json")
                    .then(res => res.json())
                    .then(data => callback(data.country_code))
                    .catch(() => callback("us"));
            },
        });
    </script>

    <script src="{{ Vite::asset('resources/js/modal.js') }}"></script>
    <script>
        configModal('modal', 'modal-open')
    </script>
@endpush
