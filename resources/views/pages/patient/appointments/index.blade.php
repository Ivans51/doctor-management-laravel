@extends('layouts.patient')

@section('content')
    <x-utils.loading-component/>

    <section class="space-x-0 lg:space-x-4 flex lg:flex-row flex-col items-start">
        <div class="w-full">
            <h3 class="my-2 font-bold text-lg">Appointment Request</h3>
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
            let url = `/appointments/doctor?search=${search}&limit=${limit}&page=${page}`
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
                error: function (xhr) {
                    console.log(xhr)
                    hideLoading()
                }
            })
        }

        function setDataTable(response) {
            let html = ''

            setPagination(response)

            response.data.data.forEach(function (item) {
                let image
                let status

                if (item.patient.profile == null) {
                    const urlImage = '{{ Vite::asset('resources/img/icons8-male-user.png') }}'
                    image = `<img
                        class="h-10 mr-3"
                        src="${urlImage}"
                        alt="profile patient"
                        style="border-radius: 50%"
                    >`
                } else {
                    const urlImage = `{{ Vite::asset('storage/') }}/${item.patient.profile}`
                    image = `<img
                            class="h-10 mr-3"
                            src="${urlImage}"
                            alt="profile patient"
                            style="border-radius: 50%"
                        >`
                }

                if (item.status === CONST_APPROVED) {
                    status = `<span class="rounded text-blue-900 bg-blue-100 px-4 py-1 text-sm">
                        Confirmed
                    </span>`
                } else if (item.status === CONST_PENDING) {
                    status = `<span class="rounded text-yellow-900 bg-yellow-100 px-4 py-1 text-sm">
                        Pending
                    </span>`
                } else if (item.status === CONST_REJECTED) {
                    status = `<span class="rounded text-red-900 bg-red-100 px-4 py-1 text-sm">
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
                    ${status}
                </div>`
            })

            $('#content-body').html(html)
        }
    </script>
@endpush
