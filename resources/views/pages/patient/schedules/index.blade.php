@extends('layouts.patient')

@section('content')
    <div class="container">
        <div id="calendar"></div>

        <button id="btn-hide" class="hidden">Hide</button>

        <x-modal.modal-component
            title="Appointment details"
            modalClass="modal"
        >
            <x-slot name="content">
                <div id="modal-detail" class="p-4"></div>
            </x-slot>
        </x-modal.modal-component>
    </div>
@endsection

@push('scripts-bottom')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.5/index.global.min.js'></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // initialize calendar
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: function (info, successCallback, failureCallback) {
                    // get the start and end dates of the current month
                    const start = info.startStr;
                    const end = info.endStr;

                    $.ajax({
                        url: '{{ route('schedule-timing-doctor') }}',
                        type: 'POST',
                        data: {
                            start: start,
                            end: end,
                            _token: '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function (data) {
                            const schedules = data.data.map(function (schedule) {
                                configModal('modal', `btn-hide`)
                                return {
                                    title: `${schedule.appointment.patient.name} - ${schedule.start_time} - ${schedule.end_time}`,
                                    start: schedule.date,
                                    id: JSON.stringify(schedule)
                                }
                            })
                            successCallback(schedules);
                        },
                        error: function () {
                            failureCallback();
                        }
                    });
                },
                eventClick: function (info) {
                    info.jsEvent.preventDefault();
                    if (info.event.id) {
                        // force click btn-hide
                        $(`#btn-hide`).click()
                        const item = JSON.parse(info.event.id);
                        console.log(item)
                        setDataModal(item)
                    }
                },
            });
            calendar.render();

            function setDataModal(item) {
                let image
                let status
                const patient = item.appointment.patient
                const statusData = item.appointment.status
                const linkDownloadFileAppointment = `${window.location.origin}/storage/files/${item.appointment.file}`

                if (patient.profile == null) {
                    const urlImage = '{{ Vite::asset('resources/img/icons8-male-user.png') }}'
                    image = `<img
                        class="h-10 mr-3"
                        src="${urlImage}"
                        alt="profile patient"
                        style="border-radius: 50%"
                    >`
                } else {
                    const urlImage = `{{ Vite::asset('storage/') }}/${patient.profile}`
                    image = `<img
                        class="h-10 mr-3"
                        src="${urlImage}"
                        alt="profile patient"
                        style="border-radius: 50%"
                    >`
                }

                if (statusData === CONST_APPROVED) {
                    status = `<span class="rounded text-blue-900 bg-blue-100 px-4 py-1 text-sm">
                    Confirmed
                    </span>`
                } else if (statusData === CONST_PENDING) {
                    status = `<span class="rounded text-yellow-900 bg-yellow-100 px-4 py-1 text-sm">
                        Pending
                    </span>`
                } else if (statusData === CONST_REJECTED) {
                    status = `<span class="rounded text-red-900 bg-red-100 px-4 py-1 text-sm">
                        Cancelled
                    </span>`
                }

                $('#modal-detail').html(`
                    <ul class="space-y-2">
                        <li class="border-b-2"><strong>ID: </strong> ${item.id}</li>
                        <li>
                        <div class="flex justify-between items-center my-4">
                            <div class="flex items-start">
                                        ${image}
                                        <div class="flex flex-col">
                                            <p>${patient.name}</p>
                                            <p class="text-xs mt-1">
                                                ${patient.gender}, ${formatDate(item.date)}
                                            </p>
                                        <div>
                                    </div>
                                </div>
                            </div>
                            ${status}
                        </div>
                    </li>
                    <li class="border-b-2">
                        <strong>Start time: </strong> ${item.start_time}
                    </li>
                    <li class="border-b-2">
                        <strong>End time: </strong> ${item.end_time}
                    </li>
                    <li class="border-b-2">
                        <strong>Healthcare Provider: </strong> ${item.appointment.healthcare_provider}
                    </li>
                    <li class="border-b-2">
                        <strong>Reason for Consulting: </strong> ${item.appointment.description}
                    </li>
                    <li>
                        <strong>Review Notes: </strong> ${item.appointment.notes}
                    </li>
                    ${item.appointment.file ? `
                        <li>
                            <strong>File: </strong>
                            <a
                                href="${linkDownloadFileAppointment}"
                                target="_blank"
                                class="rounded text-red-900 bg-red-100 px-4 py-1 text-sm"
                            >
                                Download
                            </a>
                        </li>
                    ` : ''}
                </ul>
            `)
            }
        });
    </script>
@endpush
