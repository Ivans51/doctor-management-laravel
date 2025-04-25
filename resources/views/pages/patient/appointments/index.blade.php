@extends('layouts.patient')

@section('content')
    <x-utils.loading-component />
    <x-utils.message-component />

    <section class="space-x-0 lg:space-x-4 flex lg:flex-row flex-col items-start">
        <div class="w-full">
            <div class="flex justify-between items-center mb-5">
                <h3 class="my-2 font-bold text-lg">Appointment Request</h3>
                <a href="{{ route('patient.monitoring') }}"
                    class="rounded text-white bg-blue-500 px-4 py-1 text-sm ml-2 flex items-center flex-row">
                    <x-ri-heart-add-fill />
                    <span>Add Appointment</span>
                </a>
            </div>

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
            let url = `/patient/api/appointments?search=${search}&limit=${limit}&page=${page}`
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
            let html = ''

            setPagination(response)

            if (response.data.data.length === 0) {
                html += `
                    <div class="flex justify-center items-center">
                        <p class="text-gray-500">No data found</p>
                    </div>
                `
            } else {
                response.data.data.forEach(function(item, index) {
                    let image = ''
                    let status
                    let btnActions = ''
                    let paymentStatus = ''

                    // Check if the appointment has a payment
                    if (item.payment) {
                        paymentStatus = `
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                Paid
                            </span>`
                    } else {
                        paymentStatus = `
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Unpaid
                            </span>`
                    }

                    if (item.patient.profile == null) {
                        const urlImage = '{{ Vite::asset('resources/img/icons8-male-user.png') }}'
                        image = `<img
                            class="h-10 mr-3"
                            src="${urlImage}"
                            alt="profile patient"
                            style="border-radius: 50%"
                        >`
                    }

                    btnActions = getAppointmentActions(item);

                    if (item.status === CONST_APPROVED) {
                        status = `
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Confirmed
                        </span>`
                    } else if (item.status === CONST_PENDING) {
                        status = `
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            Pending
                        </span>`
                    } else if (item.status === CONST_REJECTED) {
                        status = `
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Cancelled
                        </span>`
                    }

                    html += `
                            <div class="flex justify-between items-center my-4">
                                <div class="flex items-start">
                                    ${image}
                                    <div class="flex flex-col">
                                        <p>${item.patient.name}</p>
                                        <p class="text-xs mt-1">
                                            ${item.patient.gender}, ${formatDate(item.schedule.date)}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end space-y-2">
                                    ${btnActions}
                                    <div class="flex items-center space-x-2">
                                        ${status}
                                        ${paymentStatus}
                                    </div>
                                </div>
                            </div>
                            ${index < response.data.data.length - 1 ? '<hr class="my-2 border-gray-200">' : ''}`
                })
            }

            $('#content-body').html(html)
        }

        function changeStatus(id, status) {
            const route = '{{ route('patient.appointment.status') }}';
            changeStatusAppointment(id, status, route).then(() => {
                searchData()
            })
        }

        function openAppointmentDetailModal(appointmentId) {
            showLoading();
            const url = `/patient/api/appointments/${appointmentId}`;
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

        // Helper function to generate action buttons based on appointment status
        function getAppointmentActions(item) {
            if (!isMayorDate(item.schedule.date)) return '';

            let actions = `
                <div class="flex items-center space-x-2">
                    <button
                        onclick="openAppointmentDetailModal('${item.encrypted_id}')"
                        class="rounded text-white bg-blue-500 px-2 py-1 text-xs"
                    >
                        Detail
                    </button>
                    <button
                        onclick="changeStatus('${item.encrypted_id}', '${CONST_REJECTED}')"
                        class="rounded text-white bg-red-500 px-2 py-1 text-xs"
                    >
                        Cancel
                    </button>
            `;

            // Add Pay button for pending, unpaid appointments
            if (item.status !== CONST_REJECTED && !item.payment) {
                actions += `
                    <a
                        href="/patient/checkout/${item.encrypted_id}"
                        class="rounded text-white bg-green-500 px-2 py-1 text-xs"
                    >
                        Pay
                    </a>
                `;
            }

            actions += `</div>`;
            return actions;
        }
    </script>
@endpush
