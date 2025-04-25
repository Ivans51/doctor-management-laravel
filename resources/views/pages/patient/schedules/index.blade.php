@extends('layouts.patient')

@section('content')
    <div class="container">
        <div id="calendar"></div>

        <button id="btn-hide" class="hidden">Hide</button>
    </div>

    <x-modal.appointment-detail />
@endsection

@push('scripts-bottom')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.5/index.global.min.js'></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // initialize calendar
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: function(info, successCallback, failureCallback) {
                    // get the start and end dates of the current month
                    const start = info.startStr;
                    const end = info.endStr;

                    $.ajax({
                        url: '{{ route('patient.api.schedule.timing') }}',
                        type: 'POST',
                        data: {
                            start: start,
                            end: end,
                            _token: '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(data) {
                            const schedules = data.data.map(function(schedule) {
                                const scheduleName = schedule.appointment.patient ?
                                    schedule.appointment.patient.name :
                                    'No name'
                                return {
                                    title: `${scheduleName} - ${schedule.start_time} - ${schedule.end_time}`,
                                    start: schedule.date,
                                    id: JSON.stringify(schedule),
                                    backgroundColor: schedule.appointment.status ===
                                        CONST_APPROVED ?
                                        '#90cdf4' : schedule.appointment.status ===
                                        CONST_PENDING ?
                                        '#faf089' : '#f56565',
                                    borderColor: schedule.appointment.status ===
                                        CONST_APPROVED ?
                                        '#90cdf4' : schedule.appointment.status ===
                                        CONST_PENDING ?
                                        '#faf089' : '#f56565',
                                    textColor: schedule.appointment.status ===
                                        CONST_APPROVED || schedule.appointment
                                        .status === CONST_REJECTED ?
                                        '#FFFFFF' : '#000000',
                                }
                            })
                            successCallback(schedules);
                        },
                        error: function() {
                            failureCallback();
                        }
                    });
                },
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    if (info.event.id) {
                        // force click btn-hide
                        $(`#btn-hide`).click()
                        const item = JSON.parse(info.event.id);
                        openAppointmentDetailModal(item);
                    }
                },
            });
            calendar.render();

            function openAppointmentDetailModal(item) {
                const appointment = item.appointment;
                const patient = appointment.patient;
                const schedule = item;

                // Profile image logic
                const profileImage = patient.profile ?
                    `<img src="/storage/${patient.profile}" class="h-10 w-10 rounded-full mr-3" alt="Profile">` :
                    `<img src="{{ Vite::asset('resources/img/icons8-male-user.png') }}" class="h-10 w-10 rounded-full mr-3" alt="Default Profile">`;

                // Status badges
                const status = getStatusBadge(appointment.status);
                const paymentStatus = getPaymentBadge(appointment.payment);

                // Action buttons (e.g., Pay, Cancel)
                const actionButtons = getActionButtons(appointment);

                // Modal content with status in same row
                const modalContent = `
                    <div class="space-y-4">
                        <div class="flex justify-between items-start pb-4 border-b border-gray-200">
                            <div class="flex items-start">
                                ${profileImage}
                                <div class="flex flex-col">
                                    <p class="font-medium">${patient.name}</p>
                                    <p class="text-xs mt-1">${patient.gender}, ${patient.years_old} years</p>
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
                $('#modalContent').html(modalContent);
                $('#appointmentDetailModal').removeClass('hidden');
            }

            // Helper function to get status badge
            function getStatusBadge(status) {
                if (status === 'approved' || status === 'APPROVED') {
                    return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Confirmed</span>`;
                } else if (status === 'pending' || status === 'PENDING') {
                    return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>`;
                } else if (status === 'rejected' || status === 'REJECTED') {
                    return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Cancelled</span>`;
                }
                return '';
            }

            // Helper function to get payment badge
            function getPaymentBadge(isPaid) {
                return isPaid ?
                    `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Paid</span>` :
                    `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Unpaid</span>`;
            }

            // Helper function to get action buttons (e.g., Pay, Cancel)
            function getActionButtons(appointment) {
                let buttons = '';

                // Add Cancel button for pending or approved appointments
                if ((appointment.status === 'pending' || appointment.status === 'PENDING' ||
                        appointment.status === 'approved' || appointment.status === 'APPROVED') &&
                    appointment.encrypted_id) {
                    buttons += `
                        <button
                            onclick="changeStatus('${appointment.encrypted_id}', '${CONST_REJECTED}')"
                            class="rounded text-white bg-red-500 px-3 py-1.5 text-sm hover:bg-red-600 mr-2"
                        >
                            Cancel
                        </button>
                    `;
                }

                // Add Pay button for unpaid, non-cancelled appointments
                if (!appointment.payment && appointment.encrypted_id &&
                    appointment.status !== 'rejected' && appointment.status !== 'REJECTED') {
                    buttons += `
                        <a href="/patient/checkout/${appointment.encrypted_id}"
                           class="rounded text-white bg-green-500 px-3 py-1.5 text-sm hover:bg-green-600">
                            Pay
                        </a>
                    `;
                }

                return buttons ? `
                    <div class="flex justify-end pt-4">
                        ${buttons}
                    </div>
                ` : '';
            }
        });

        function changeStatus(id, status) {
            const route = '{{ route('patient.appointment.status') }}';
            changeStatusAppointment(id, status, route).then(() => {
                window.location.reload()
            })
        }
    </script>
@endpush
