@extends('home')

@section('content')
    <h3 class="font-bold text-lg mb-10">Log New Consulting</h3>

    <div class="flex items-start space-x-0 md:space-x-4">
        <section class="w-3/5">
            <form action="{{ route('my-patient-detail-post') }}" method="post">
                @csrf <!-- add this to protect against CSRF attacks -->
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-x-4">
                        <div>
                            <label for="doctor_id">Healthcare Provider</label>
                            <input class="border w-full bg-transparent" type="text" name="doctor_id" id="doctor_id">
                        </div>

                        <div>
                            <label for="type">Type</label>
                            <select name="type" id="type" class="border w-full p-2 bg-transparent">
                                <option value="">Select</option>
                                <option value="1">1</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-x-4">
                        <div>
                            <label for="patient_id">Patient Name</label>
                            <select name="patient_id" id="patient_id" class="border w-full p-2 bg-transparent">
                                <option value="">Select</option>
                                <option value="1">1</option>
                            </select>
                        </div>

                        <div>
                            <label for="location_name">Location</label>
                            <select name="location_name" id="location_name" class="border w-full p-2 bg-transparent">
                                <option value="">Select</option>
                                <option value="1">1</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-x-4">
                        <div>
                            <label for="start_time">Start Time</label>
                            <input class="border w-full bg-transparent" type="time" name="start_time" id="start_time">
                        </div>

                        <div>
                            <label for="date_consulting">Date of Consulting</label>
                            <input
                                class="border w-full bg-transparent"
                                type="date"
                                name="date_consulting"
                                id="date_consulting"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="reason_consulting">Reason for Consulting</label>
                        <textarea
                            class="border w-full bg-transparent p-2"
                            rows="5"
                            name="reason_consulting"
                            id="date_consulting"
                        ></textarea>
                    </div>

                    <div>
                        <label for="review_notes">Review Notes</label>
                        <textarea
                            class="border w-full bg-transparent p-2"
                            rows="5"
                            name="review_notes"
                            id="date_consulting"
                        ></textarea>
                    </div>

                    <div class="dropzone" id="mydropzone"></div>
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

@push('scripts-bottom')
    <script src="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone-min.js"></script>
    <link href="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone.css" rel="stylesheet" type="text/css"/>

    <script>
        Dropzone.autoDiscover = false;
        const myDropzone = new Dropzone("div#mydropzone", {
            url: "/file/post",
            paramName: "file",
            acceptedFiles: 'application/pdf',
            maxFiles: 1,
            success: function (file, response) {
                console.log(response);
            }
        });
    </script>
@endpush
