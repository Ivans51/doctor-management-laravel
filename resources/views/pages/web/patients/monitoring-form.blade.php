@extends('layouts.home')

@section('content')
    <h3 class="font-bold text-lg mb-10">Log New Consulting</h3>

    <div class="flex items-start space-x-0 md:space-x-4">
        <section class="w-3/5">
            <x-utils.message-component/>

            <form action="{{ route('appointment-store') }}" method="post" enctype="multipart/form-data">
                @csrf <!-- add this to protect against CSRF attacks -->
                @method('POST')

                <div class="space-y-6">
                    <div class="grid grid-cols-1 gap-x-4">
                        <div>
                            <label for="patient_name">Patient Name</label>
                            <input type="hidden" id="patient_id" name="patient_id" value="{{ $patient->id }}">
                            <input
                                class="border w-full bg-transparent"
                                type="text"
                                name="patient_name"
                                id="patient_name"
                                value="{{ $patient->name }}"
                                readonly
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-x-4">
                        <div>
                            <label for="medical_specialty_id">Medical Specialties</label>
                            <select
                                name="medical_specialty_id"
                                id="medical_specialty_id"
                                class="border w-full bg-transparent"
                                required
                            >
                                <option value="">Select</option>
                                @foreach($medicalSpecialties as $medicalSpecialty)
                                    <option value="{{ $medicalSpecialty->id }}">
                                        {{ $medicalSpecialty->name }}
                                    </option>
                                @endforeach
                            </select>
                            Price: <span id="price">$0</span>
                        </div>

                        <div>
                            <label for="doctor_id">Doctors</label>
                            <select
                                name="doctor_id"
                                id="doctor_id"
                                class="border w-full bg-transparent"
                                required
                            >
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-x-4">
                        <div>
                            <label for="healthcare_provider">Healthcare Provider</label>
                            <input
                                class="border w-full bg-transparent"
                                type="text"
                                name="healthcare_provider"
                                id="healthcare_provider"
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-x-4">
                        <div>
                            <label for="start_time">Start Time</label>
                            <input
                                class="border w-full bg-transparent"
                                type="time"
                                name="start_time"
                                id="start_time"
                                required
                            >
                        </div>

                        <div>
                            <label for="end_time">End Time</label>
                            <input
                                class="border w-full bg-transparent"
                                type="time"
                                name="end_time"
                                id="end_time"
                                required
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-x-4">
                        <div>
                            <label for="date_consulting">Date of Consulting</label>
                            {{-- input date not before today --}}
                            <input
                                class="border w-full bg-transparent"
                                type="date"
                                name="date_consulting"
                                id="date_consulting"
                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                required
                            >
                        </div>
                    </div>

                    <div>
                        <label for="description">Reason for Consulting</label>
                        <textarea
                            class="border w-full bg-transparent p-2"
                            rows="5"
                            name="description"
                            id="description"
                        ></textarea>
                    </div>

                    <div>
                        <label for="notes">Review Notes</label>
                        <textarea
                            class="border w-full bg-transparent p-2"
                            rows="5"
                            name="notes"
                            id="notes"
                        ></textarea>
                    </div>

                    <div>
                        <label for="file" class="block">Upload File</label>
                        <p class="text-sm text-zinc-400">File must be in .pdf, .docx format</p>
                        <input
                            class="border w-full bg-transparent"
                            type="file"
                            name="file"
                            id="file"
                            accept=".pdf,.doc,.docx"
                        >
                    </div>
                </div>

                <div class="flex items-center space-x-2 mt-10">
                    <button
                        type="submit"
                        class="rounded text-white bg-sky-500 px-4 py-1 text-sm ml-2 flex items-center flex-row w-full justify-center"
                    >
                        <x-ri-heart-add-fill class="w-6 h-6 mr-2"/>
                        Set monitoring Plane
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
                @if($patient->profile == null)
                    <img
                        class="mb-2 w-24 h-24"
                        src="{{ Vite::asset('resources/img/icons8-male-user.png') }}"
                        alt="profile patient"
                        style="border-radius: 50%"
                    >
                @else
                    <img
                        class="h-10 mr-3"
                        src="{{asset('storage/'.$patient->profile)}}"
                        alt="profile patient"
                        style="border-radius: 50%"
                    >
                @endif
                <p class="font-bold">{{ $patient->name }}</p>
                <p class="text-zinc-400">{{ $patient->years_old }} Years, {{ $patient->gender }}</p>
            </div>

            <hr class="my-4">

            <div class="space-y-4">
                <div>
                    <p class="text-zinc-400">Email</p>
                    <p>{{ $patient->user->email }}</p>
                </div>
                <div>
                    <p class="text-zinc-400">Phone</p>
                    <p>{{ $patient->phone }}</p>
                </div>
                <div>
                    <p class="text-zinc-400">Date of Birth</p>
                    <p>
                        {{ \Carbon\Carbon::parse($patient->date_of_birth)->format('d F Y') }}
                    </p>
                </div>
                <div>
                    <p class="text-zinc-400">Diseases</p>
                    <p>Not found</p>
                </div>
            </div>

            <hr class="my-4">
        </section>
    </div>
@endsection

@push('scripts-bottom')
    <script>
        // detect change medical specialty
        $('#medical_specialty_id').change(function () {
            // set price
            const medicalSpecialties = @json($medicalSpecialties);
            const medicalSpecialty = medicalSpecialties.find(medicalSpecialty => {
                return medicalSpecialty.id === parseInt($(this).val());
            });
            $('#price').text(formatNumber(medicalSpecialty.price));

            let medicalSpecialtyId = $(this).val();
            let url = '{{ route('doctor-list') }}' + '?medical_specialty_id=' + medicalSpecialtyId;

            $.ajax({
                url: url,
                type: 'GET',
                success: function (data) {
                    const doctorId = $('#doctor_id');
                    doctorId.empty();
                    doctorId.append('<option value="">Select Doctor</option>');

                    $.each(data.data, function (key, value) {
                        doctorId.append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                }
            });
        });
    </script>
@endpush
