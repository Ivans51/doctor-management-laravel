@extends('layouts.home')

@section('content')
    <x-utils.loading-component />

    <section class="space-x-0 lg:space-x-4 flex lg:flex-row flex-col items-start">
        <div class="w-full">
            <h3 class="my-2 font-bold text-lg">Appointment Request</h3>
            <div class="px-4 py-2 bg-white rounded">
                @if (sizeof($appointments) == 0)
                    <x-utils.not-data title="No Appointments" description="There are no appointments" />
                @else
                    <div id="content-body"></div>
                @endif
            </div>

            <x-utils.pagination-component />
        </div>
    </section>

    <x-modal.appointment-detail />
@endsection

@push('scripts-bottom')
    <script>
        let limit = 10;
        let search = '';

        searchData();

        // search data
        function searchData(page = 1) {
            showLoading()
            const route = '{{ route('doctor.appointments.doctor') }}'
            let url = `${route}?search=${search}&limit=${limit}&page=${page}`
            let token = $('meta[name="csrf-token"]').attr('content')

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                data: {
                    _token: token
                },
                success: function(response) {
                    setDataTable(response)
                    hideLoading()
                },
                error: function() {
                    hideLoading()
                }
            })
        }

        function setDataTable(response) {
            let html = '';
            setPagination(response);

            response.data.data.forEach((item) => {
                const image = getPatientImage(item);
                const status = getStatusBadge(item.status);
                const btnActions = getActionButtons(item);
                const paymentStatus = item.payment ?
                    `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Paid</span>` :
                    `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Unpaid</span>`;

                html += `
                    <div class="flex justify-between items-center my-4">
                        <div class="flex items-start">
                            ${image}
                            <div class="flex flex-col">
                                <p class="font-medium">${item.patient.name}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    ${item.patient.gender}, ${formatDate(item.schedule.date)}
                                </p>
                                <div class="flex items-center space-x-2 mt-2">
                                    ${status}
                                    ${paymentStatus}
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col items-end space-y-2">
                            ${btnActions}
                        </div>
                    </div>
                    <hr class="my-2 border-gray-200">
                `;
            });

            $('#content-body').html(html);
        }

        // Helper: Get patient image HTML
        function getPatientImage(item) {
            if (!item.patient.profile) return '';
            const urlImage = '{{ Vite::asset('resources/img/icons8-male-user.png') }}';
            return `<img class="h-10 w-10 rounded-full mr-3" src="${urlImage}" alt="profile patient">`;
        }

        // Helper: Get status badge HTML
        function getStatusBadge(status) {
            const statusConfig = {
                [CONST_APPROVED]: {
                    class: 'bg-green-100 text-green-800',
                    label: 'Confirmed'
                },
                [CONST_PENDING]: {
                    class: 'bg-yellow-100 text-yellow-800',
                    label: 'Pending'
                },
                [CONST_REJECTED]: {
                    class: 'bg-red-100 text-red-800',
                    label: 'Cancelled'
                },
            };
            const config = statusConfig[status] || {
                class: '',
                label: ''
            };
            return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${config.class}">
                ${config.label}
            </span>`;
        }

        // Helper: Get action buttons HTML
        function getActionButtons(item) {
            const detailButton = `
                <button
                    onclick="openAppointmentDetailModal('${item.encrypted_id}')"
                    class="rounded text-white bg-blue-500 px-2 py-1 text-xs"
                >
                    Detail
                </button>
            `;

            if (!isMayorDate(item.schedule.date)) {
                return `<div class="flex items-center space-x-2">${detailButton}</div>`;
            }

            let buttons = detailButton;
            if (item.status === CONST_PENDING) {
                buttons += `
                    <button
                        onclick="changeStatus('${item.encrypted_id}', '${CONST_APPROVED}')"
                        class="rounded text-white bg-green-500 px-2 py-1 text-xs"
                    >
                        Accept
                    </button>
                    <button
                        onclick="changeStatus('${item.encrypted_id}', '${CONST_REJECTED}')"
                        class="rounded text-white bg-red-500 px-2 py-1 text-xs"
                    >
                        Cancel
                    </button>
                `;
            } else if (item.status === CONST_APPROVED) {
                buttons += `
                    <button
                        onclick="changeStatus('${item.encrypted_id}', '${CONST_REJECTED}')"
                        class="rounded text-white bg-red-500 px-2 py-1 text-xs"
                    >
                        Cancel
                    </button>
                `;
            }

            return `<div class="flex items-center space-x-2">${buttons}</div>`;
        }

        function openAppointmentDetailModal(appointmentId) {
            showLoading();
            const url = `/api/appointments/${appointmentId}`;
            const token = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                data: {
                    _token: token
                },
                success: function(response) {
                    const appointment = response.data;
                    const patient = appointment.patient;
                    const schedule = appointment.schedule;

                    // Profile icon logic
                    const profileImage = patient.profile ?
                        `<img src="{{ asset('storage/${patient.profile}') }}" class="h-10 w-10 rounded-full mr-3" alt="Profile">` :
                        `<img src="{{ Vite::asset('resources/img/icons8-male-user.png') }}" class="h-10 w-10 rounded-full mr-3" alt="Default Profile">`;

                    // Status and payment badges
                    let statusBadge = '';
                    if (appointment.status === 'approved' || appointment.status === 'APPROVED') {
                        statusBadge =
                            `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Confirmed</span>`;
                    } else if (appointment.status === 'pending' || appointment.status === 'PENDING') {
                        statusBadge =
                            `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>`;
                    } else if (appointment.status === 'rejected' || appointment.status === 'REJECTED') {
                        statusBadge =
                            `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Cancelled</span>`;
                    }

                    const paymentBadge = appointment.payment ?
                        `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Paid</span>` :
                        `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Unpaid</span>`;

                    const modalContent = `
                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                        <div class="flex items-center">
                            ${profileImage}
                            <div>
                                <p class="font-medium">${patient.name}</p>
                                <p class="text-sm text-gray-500">${patient.gender}</p>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            ${statusBadge}
                            ${paymentBadge}
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
                                                <a href="{{ storage_path('app/public/files/${appointment.file}') }}"
                                                   target="_blank"
                                                   class="inline-flex items-center text-blue-500 hover:text-blue-700">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                    </svg>
                                                    Download
                                                </a>
                                            </div>
                                        ` : ''}
                                </div>
                            `;
                    $('#modalContent').html(modalContent);
                    $('#appointmentDetailModal').removeClass('hidden');
                    hideLoading();
                },
                error: function() {
                    hideLoading();
                    alert('Failed to load appointment details.');
                }
            });
        }

        function changeStatus(id, status) {
            const route = '{{ route('doctor.appointment.status') }}';
            changeStatusAppointment(id, status, route).then(() => {
                searchData()
            })
        }
    </script>
@endpush
