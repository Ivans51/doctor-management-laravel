@extends('layouts.patient')

@section('content')
    <x-utils.loading-component/>

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

                    if (isMayorDate(item.schedule.date)) {
                        const cancelImg = '{{ Vite::asset('resources/img/utils/icons8-reject-96.png') }}'

                        if (item.status === CONST_APPROVED || item.status === CONST_PENDING) {
                            btnActions = `
                                <div class="flex items-center">
                                    <img
                                        class="h-6 cursor-pointer"
                                        alt="btn cancel"
                                        title="Cancel"
                                        src="${cancelImg}"
                                        onclick="changeStatus('${item.id}', '${CONST_REJECTED}')"
                                    >
                                </div>`
                        }
                    }

                    if (item.status === CONST_APPROVED) {
                        status = `
                        <span class="text-xs text-zinc-500">
                            Confirmed
                        </span>`
                    } else if (item.status === CONST_PENDING) {
                        status = `
                        <span class="text-xs text-zinc-500">
                            Pending
                        </span>`
                    } else if (item.status === CONST_REJECTED) {
                        status = `
                        <span class="text-xs text-zinc-500">
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
                                    <div>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col items-center">
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
    </script>
@endpush
