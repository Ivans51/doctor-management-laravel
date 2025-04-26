@php use App\Constants\AppointmentStatus; @endphp
@extends('layouts.home')

@section('content')
    <div class="container">
        <div id="calendar"></div>

        <button id="btn-hide" class="hidden">Hide</button>

        <x-modal.appointment-detail />
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
                        url: '{{ route('doctor.api.schedule.timing') }}',
                        type: 'POST',
                        data: {
                            start: start,
                            end: end,
                            _token: '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function (data) {
                            const schedules = data.data.map(function (schedule) {
                                // No need for configModal here anymore
                                return {
                                    title: `${schedule.appointment.patient.name} - ${schedule.start_time} - ${schedule.end_time}`,
                                    start: schedule.date,
                                    id: JSON.stringify(schedule), // Keep stringifying the whole schedule object
                                    backgroundColor: schedule.appointment.status === CONST_APPROVED ?
                                        '#90cdf4'
                                        : schedule.appointment.status === CONST_PENDING
                                            ? '#faf089'
                                            : '#f56565',
                                    borderColor: schedule.appointment.status === CONST_APPROVED ?
                                        '#90cdf4'
                                        : schedule.appointment.status === CONST_PENDING
                                            ? '#faf089'
                                            : '#f56565',
                                    textColor: schedule.appointment.status === CONST_APPROVED || schedule.appointment.status === CONST_REJECTED
                                        ? '#FFFFFF' : '#000000',
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
                        // No need to force click btn-hide anymore
                        const item = JSON.parse(info.event.id);
                        // Call the new modal function
                        openAppointmentDetailModal(item);
                    }
                },
            });
            calendar.render();

            function openAppointmentDetailModal(item) {
                const appointment = item.appointment;
                const patient = appointment.patient;
                const schedule = item;

                // Profile image logic (remains the same)
                const profileImage = patient.profile ?
                    `<img src="/storage/${patient.profile}" class="h-10 w-10 rounded-full mr-3" alt="Profile">` :
                    `<img src="{{ Vite::asset('resources/img/icons8-male-user.png') }}" class="h-10 w-10 rounded-full mr-3" alt="Default Profile">`;

                const status = getStatusBadge(appointment.status);
                const paymentStatus = getPaymentBadge(appointment.payment);
                const actionButtons = getActionButtons(appointment);

                // Modal content structure (copied from patient view)
                const modalContent = `
                    <div class="space-y-4">
                        <div class="flex justify-between items-start pb-4 border-b border-gray-200">
                            <div class="flex items-start">
                                ${profileImage}
                                <div class="flex flex-col">
                                    <p class="font-medium">${patient.name}</p>
                                    <p class="text-xs mt-1">${patient.gender || 'N/A'}, ${patient.years_old || 'N/A'} years</p>
                                </div>
                            </div>
                            <div class="flex flex-col items-end space-y-1">
                                <div class="flex items-center space-x-2">
                                    ${status}
                                    ${paymentStatus}
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 pb-4 border-b border-gray-200">
                            <div>
                                <p class="font-medium text-gray-500">Date:</p>
                                <p>${formatDate(schedule.date)}</p>
                            </div>
                            <div>
                                <p class="font-medium text-gray-500">Healthcare Provider:</p>
                                <p>${appointment.healthcare_provider || 'N/A'}</p>
                            </div>
                            <div>
                                <p class="font-medium text-gray-500">Start Time:</p>
                                <p>${schedule.start_time}</p>
                            </div>
                            <div>
                                <p class="font-medium text-gray-500">End Time:</p>
                                <p>${schedule.end_time}</p>
                            </div>
                        </div>
                        <div class="pb-4 border-b border-gray-200">
                            <p class="font-medium text-gray-500">Reason for Consulting:</p>
                            <p>${appointment.description || 'N/A'}</p>
                        </div>
                        <div class="pb-4 border-b border-gray-200">
                            <p class="font-medium text-gray-500">Notes:</p>
                            <p>${appointment.notes || 'N/A'}</p>
                        </div>
                        ${appointment.file ? `
                                    <div>
                                        <p class="font-medium text-gray-500">File:</p>
                                        <a href="/storage/files/${appointment.file}"
                                           target="_blank"
                                           class="inline-flex items-center text-blue-500 hover:text-blue-700">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                            Download
                                        </a>
                                    </div>
                                ` : ''}
                        ${actionButtons}
                    </div>
                `;
                // Populate the modal content area (assuming the component has an ID 'modalContent')
                $('#modalContent').html(modalContent);
                // Show the modal (assuming the component has an ID 'appointmentDetailModal')
                $('#appointmentDetailModal').removeClass('hidden');
            }

            // Helper function to get status badge (copied)
            function getStatusBadge(status) {
                if (status === CONST_APPROVED) {
                    return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Confirmed</span>`;
                } else if (status === CONST_PENDING) {
                    return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>`;
                } else if (status === CONST_REJECTED) {
                    return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Cancelled</span>`;
                }
                return '';
            }

            // Helper function to get payment badge (copied)
            function getPaymentBadge(isPaid) {
                // Assuming isPaid is a boolean or similar truthy/falsy value
                return isPaid ?
                    `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Paid</span>` :
                    `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Unpaid</span>`;
            }

            // Helper function to get action buttons (adapted for doctor)
            function getActionButtons(appointment) {
                let buttons = '';
                 if (isMayorDate(appointment.schedule.date)) {
                    console.log(appointment.id)
                    if (appointment.status === CONST_PENDING) {
                        buttons += `
                            <button
                                onclick="changeStatus('${appointment.encrypted_id}', '${CONST_APPROVED}')"
                                class="rounded text-white bg-green-500 px-3 py-1.5 text-sm hover:bg-green-600 mr-2"
                            >
                                Accept
                            </button>
                            <button
                                onclick="changeStatus('${appointment.encrypted_id}', '${CONST_REJECTED}')"
                                class="rounded text-white bg-red-500 px-3 py-1.5 text-sm hover:bg-red-600"
                            >
                                Reject
                            </button>
                        `;
                    } else if (appointment.status === CONST_APPROVED) {
                         buttons += `
                            <button
                                onclick="changeStatus('${appointment.encrypted_id}', '${CONST_REJECTED}')"
                                class="rounded text-white bg-red-500 px-3 py-1.5 text-sm hover:bg-red-600"
                            >
                                Reject
                            </button>
                        `;
                     }
                 }

                return buttons ? `
                    <div class="flex justify-end pt-4">
                        ${buttons}
                    </div>
                ` : '';
            }
        });

        function changeStatus(id, status) {
            const route = '{{ route('doctor.appointment.status') }}';
            changeStatusAppointment(id, status, route).then(() => {
                 $('#appointmentDetailModal').addClass('hidden');
                location.reload()
            })
        }

    </script>
@endpush
