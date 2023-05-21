@extends('layouts.admin')

@section('content')
    <section class="mt-10">
        <div class="flex justify-between items-center">
            <h3 class="my-2 font-bold text-lg">Doctors list</h3>
            <button
                onclick="openModal()"
                class="rounded text-white bg-blue-500 px-4 py-1 text-sm ml-2 flex items-center flex-row"
            >
                <span>Add</span>
            </button>
        </div>

        <button id="modal-open" class="hidden"></button>

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
                <input class="w-full" type="search" placeholder="Name, email">
            </label>
            <label class="w-full lg:w-1/3">
                Date
                <input class="w-full" type="date">
            </label>
        </div>

        <div class="overflow-x-auto">
            <table class="table-auto border-separate w-full border-spacing-x-0 border-spacing-y-2">
                <tr class="bg-zinc-100">
                    <th class="px-4 py-1 text-left">Name</th>
                    <th class="px-4 py-1">Email</th>
                    <th class="px-4 py-1">Speciality</th>
                    <th class="px-4 py-1">Data</th>
                    <th class="px-4 py-1">Actions</th>
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
                            <a
                                href="{{ route('patients.show', 1) }}"
                                class="rounded text-blue-900 bg-blue-100 px-4 py-1 text-sm ml-2"
                            >
                                Patients
                            </a>
                            <a
                                href="{{ route('payments.index') }}"
                                class="rounded text-blue-900 bg-blue-100 px-4 py-1 text-sm ml-2"
                            >
                                Payments
                            </a>
                        </td>
                        <td class="px-4 py-2 text-center">
                            <a
                                onclick="openModal('{{ $image }}')"
                                class="rounded text-green-900 bg-green-100 px-4 py-1 text-sm ml-2 cursor-pointer"
                            >
                                Edit
                            </a>
                            <a
                                href="{{ route('doctors.destroy', $image) }}"
                                class="rounded text-red-900 bg-red-100 px-4 py-1 text-sm ml-2"
                                data-confirm-delete="true"
                            >
                                Delete
                            </a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </section>

    <x-modal.modal-component
        title="Form"
        modalClass="modal"
    >
        <x-slot name="content">
            <form action="" method="post" class="p-10">
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
                        <input
                            class="border w-full"
                            name="location"
                            type="text"
                            autocomplete="shipping address-line1"
                            id="location">
                    </div>

                    <div class="grid grid-cols-2 gap-x-4">
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
    </x-modal.modal-component>

@endsection

@push('scripts-bottom')
    <script>
        configModal('modal', 'modal-open')

        function openModal(data) {
            if (data) {
                $('#name').val(data)
                $('#email').val(data)
                $('#password').val(data)
                $('#confirm_password').val(data)
            } else {
                $('#name').val('')
                $('#email').val('')
                $('#password').val('')
                $('#confirm_password').val('')
            }
            document.getElementById('modal-open').click();
        }
    </script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/css/intlTelInput.css">
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/intlTelInput.min.js"></script>
    <script src="{{ Vite::asset('resources/js/phone-input.js') }}"></script>
    <script>
        configModal('modal', 'modal-open')
    </script>

    <script id="search-js" defer src="https://api.mapbox.com/search-js/v1.0.0-beta.16/web.js"></script>

    <script>
        const script = document.getElementById('search-js');
        script.onload = function () {
            mapboxsearch.autofill({
                accessToken: 'pk.eyJ1IjoiaXZhbnM1MSIsImEiOiJjbGhmY21kN3kxOGJyM2VrMXRveHFicDJ4In0.TrpXPqd_UM9tC66Tnq_hLQ',
                options: {
                    language: 'es',
                },
            })
        };

        document.querySelector('input[name="location"]').addEventListener('input', event => {
            console.log(`${event.target.value}`);
        });
    </script>
@endpush
