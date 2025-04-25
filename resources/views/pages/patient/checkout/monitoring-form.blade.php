@extends('layouts.patient')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h3 class="font-bold text-lg">Log New Consulting</h3>
        <a href="{{ url()->previous() }}" class="rounded text-gray-600 border border-gray-300 hover:bg-gray-100 px-4 py-2 text-sm flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            Back
        </a>
    </div>

    <div class="flex items-start space-x-0 md:space-x-4">
        <section class="w-3/5">
            <x-utils.message-component />

            <form action="{{ route('patient.appointment.store') }}" method="post" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-sm">
                @csrf <!-- add this to protect against CSRF attacks -->
                @method('POST')

                <div class="space-y-6">
                    <div class="grid grid-cols-1 gap-x-4">
                        <div>
                            <label for="patient_name" class="block text-sm font-medium mb-1">Patient Name</label>
                            <input type="hidden" id="patient_id" name="patient_id" value="{{ $patient->id }}">
                            <input class="border rounded w-full bg-transparent px-3 py-2" type="text" name="patient_name" id="patient_name"
                                value="{{ $patient->name }}" readonly>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-x-4">
                        <div>
                            <label for="medical_specialty_id" class="block text-sm font-medium mb-1">Medical Specialties</label>
                            <select name="medical_specialty_id" id="medical_specialty_id"
                                class="border rounded w-full bg-transparent px-3 py-2" required>
                                <option value="">Select</option>
                                @foreach ($medicalSpecialties as $medicalSpecialty)
                                    <option value="{{ $medicalSpecialty->id }}">
                                        {{ $medicalSpecialty->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-sm font-medium">Price: <span id="price" class="text-green-600">$0</span></p>
                        </div>

                        <div>
                            <label for="doctor_id" class="block text-sm font-medium mb-1">Doctors</label>
                            <select name="doctor_id" id="doctor_id" class="border rounded w-full bg-transparent px-3 py-2" required>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-x-4">
                        <div>
                            <label for="healthcare_provider" class="block text-sm font-medium mb-1">Healthcare Provider</label>
                            <input class="border rounded w-full bg-transparent px-3 py-2" type="text" name="healthcare_provider"
                                id="healthcare_provider">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-x-4">
                        <div>
                            <label for="start_time" class="block text-sm font-medium mb-1">Start Time</label>
                            <input class="border rounded w-full bg-transparent px-3 py-2" type="time" name="start_time" id="start_time"
                                required>
                        </div>

                        <div>
                            <label for="end_time" class="block text-sm font-medium mb-1">End Time</label>
                            <input class="border rounded w-full bg-transparent px-3 py-2" type="time" name="end_time" id="end_time"
                                required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-x-4">
                        <div>
                            <label for="date_consulting" class="block text-sm font-medium mb-1">Date of Consulting</label>
                            {{-- input date not before today --}}
                            <input class="border rounded w-full bg-transparent px-3 py-2" type="date" name="date_consulting"
                                id="date_consulting" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium mb-1">Reason for Consulting</label>
                        <textarea class="border rounded w-full bg-transparent p-3" rows="4" name="description" id="description"></textarea>
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium mb-1">Review Notes</label>
                        <textarea class="border rounded w-full bg-transparent p-3" rows="4" name="notes" id="notes"></textarea>
                    </div>

                    <div>
                        <label for="file" class="block text-sm font-medium mb-1">Upload File</label>
                        <p class="text-sm text-zinc-400 mb-1">File must be in .pdf, .docx format</p>
                        <input class="border rounded w-full bg-transparent px-3 py-2" type="file" name="file" id="file"
                            accept=".pdf,.doc,.docx">
                    </div>
                </div>

                <div class="mt-8">
                    <button type="submit"
                        class="rounded-md text-white bg-sky-500 hover:bg-sky-600 px-4 py-2 text-sm w-full flex items-center justify-center">
                        <x-ri-heart-add-fill class="w-5 h-5 mr-2" />
                        Set Monitoring Plan
                    </button>
                </div>
            </form>
        </section>

        <section class="w-2/5 bg-white rounded-lg p-6 shadow-sm">
            <div class="flex flex-col items-center">
                @if ($patient->profile == null)
                    <img class="mb-3 w-24 h-24 object-cover" src="{{ Vite::asset('resources/img/icons8-male-user.png') }}"
                        alt="profile patient" style="border-radius: 50%">
                @else
                    <img class="mb-3 w-24 h-24 object-cover" src="{{ asset('storage/' . $patient->profile) }}" alt="profile patient"
                        style="border-radius: 50%">
                @endif
                <p class="font-bold text-lg">{{ $patient->name }}</p>
                <p class="text-zinc-500 text-sm">{{ $patient->years_old }} Years, {{ $patient->gender }}</p>
            </div>

            <hr class="my-4">

            <div class="space-y-4">
                <div>
                    <p class="text-zinc-500 text-sm">Email</p>
                    <p class="font-medium">{{ $patient->user->email }}</p>
                </div>
                <div>
                    <p class="text-zinc-500 text-sm">Phone</p>
                    <p class="font-medium">{{ $patient->phone }}</p>
                </div>
                <div>
                    <p class="text-zinc-500 text-sm">Date of Birth</p>
                    <p class="font-medium">
                        {{ \Carbon\Carbon::parse($patient->date_of_birth)->format('d F Y') }}
                    </p>
                </div>
                <div>
                    <p class="text-zinc-500 text-sm">Diseases</p>
                    <p class="font-medium text-gray-600">Not found</p>
                </div>
            </div>

            <hr class="my-4">
        </section>
    </div>
@endsection

@push('scripts-bottom')
    <script>
        // detect change medical specialty
        $('#medical_specialty_id').change(function() {
            // set price
            const medicalSpecialties = @json($medicalSpecialties);
            const medicalSpecialty = medicalSpecialties.find(medicalSpecialty => {
                return medicalSpecialty.id === $(this).val();
            });
            $('#price').text(formatNumber(medicalSpecialty.price));

            let medicalSpecialtyId = $(this).val();
            let url = '{{ route('patient.doctor.list') }}' + '?medical_specialty_id=' + medicalSpecialtyId;

            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    const doctorId = $('#doctor_id');
                    doctorId.empty();
                    doctorId.append('<option value="">Select Doctor</option>');

                    $.each(data.data, function(key, value) {
                        doctorId.append('<option value="' + value.id + '">' + value.name +
                            '</option>');
                    });
                }
            });
        });
    </script>
@endpush
