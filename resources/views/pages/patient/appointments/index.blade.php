@extends('layouts.patient')

@section('content')
    <x-utils.loading-component/>
    <x-utils.message-component/>

    <section class="space-x-0 lg:space-x-4 flex lg:flex-row flex-col items-start">
        <div class="w-full">
            <div class="flex justify-between items-center mb-5">
                <h3 class="my-2 font-bold text-lg">Appointment Request</h3>
                <a
                    href="{{ route('patient.monitoring') }}"
                    class="rounded text-white bg-blue-500 px-4 py-1 text-sm ml-2 flex items-center flex-row"
                >
                    <x-ri-heart-add-fill/>
                    <span>Add Appointment</span>
                </a>
            </div>

            <div class="px-4 py-2 bg-white rounded">
                @if(sizeof($appointments) == 0)
                    <x-utils.not-data
                        title="No Appointments"
                        description="There are no appointments"
                    />
                @else
                    <div id="content-body"></div>
                @endif
            </div>

            <x-utils.pagination-component/>
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
                success: function (response) {
                    setDataTable(response)
                    hideLoading()
                },
                error: function () {
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
                response.data.data.forEach(function (item) {
                    let image = ''
                    let status
                    let btnActions = ''

                    if (item.patient.profile == null) {
                        const urlImage = '{{ Vite::asset('resources/img/icons8-male-user.png') }}'
                        image = `<img
                            class="h-10 mr-3"
                            src="${urlImage}"
                            alt="profile patient"
                            style="border-radius: 50%"
                        >`
                    }

                    if (isMinorDate(item.schedule.date)) {
                        if (item.status === CONST_PENDING) {
                            btnActions = `
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
                                    <a
                                        href="/patient/checkout/${item.encrypted_id}"
                                        class="rounded text-white bg-green-500 px-2 py-1 text-xs"
                                    >
                                        Pay
                                    </a>
                                </div>`
                        } else if (item.status === CONST_APPROVED) {
                            btnActions = `
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
                                </div>`
                        }
                    }

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
                                    ${status}
                                </div>
                            </div>`
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
                data: { _token: token },
                success: function (response) {
                    const appointment = response.data;
                    const modalContent = `
                        <div class="space-y-4">
                            <div>
                                <p class="font-medium">Patient:</p>
                                <p>${appointment.patient.name}</p>
                            </div>
                            <div>
                                <p class="font-medium">Date:</p>
                                <p>${formatDate(appointment.schedule.date)}</p>
                            </div>
                            <div>
                                <p class="font-medium">Status:</p>
                                <p>${appointment.status}</p>
                            </div>
                        </div>
                    `;
                    $('#modalContent').html(modalContent);
                    $('#appointmentDetailModal').removeClass('hidden');
                    hideLoading();
                },
                error: function () {
                    hideLoading();
                    alert('Failed to load appointment details.');
                }
            });
        }

        function closeModal() {
            $('#appointmentDetailModal').addClass('hidden');
        }
    </script>
@endpush
