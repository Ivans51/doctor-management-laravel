@extends('layouts.home')

@section('content')
    <x-utils.loading-component/>

    <section class="mt-10">
        <div class="flex justify-between items-center">
            <h3 class="my-2 font-bold text-lg">Patient list</h3>
            <a
                href="{{ route('my-patients-doctor.create') }}"
                class="rounded text-white bg-violet-500 px-4 py-1 text-sm ml-2 flex items-center flex-row"
            >
                <x-ri-heart-add-fill/>
                <span>Add Patient</span>
            </a>
        </div>

        <div class="my-4 flex flex-col lg:flex-row space-x-0 lg:space-x-4 space-y-2 lg:space-y-0 w-full">
            <label>Show
                <select class="bg-white p-1 border ml-2" name="select-show" id="select-show">
                    <option value="10">10</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </label>
        </div>

        <div class="my-4 flex flex-col lg:flex-row space-x-0 lg:space-x-4 space-y-2 lg:space-y-0 w-full">
            <label class="w-full lg:w-2/3">
                Search
                <input class="w-full" type="search" name="search" id="search_field" placeholder="Patient name, status">
            </label>
            <label class="w-full lg:w-1/3">
                Date
                <input class="w-full" type="date">
            </label>
        </div>

        <div class="overflow-x-auto">
            <table class="table-auto border-separate w-full border-spacing-x-0 border-spacing-y-2">
                <tr class="bg-secondary">
                    <th class="px-4 py-1 text-left">Patient Name</th>
                    <th class="px-4 py-1">Date</th>
                    <th class="px-4 py-1">Gender</th>
                    <th class="px-4 py-1">Diseases</th>
                    <th class="px-4 py-1">Status</th>
                    <th class="px-4 py-1">Action</th>
                </tr>
                <tbody id="tbody"></tbody>
            </table>
        </div>

        <x-utils.pagination-component/>
    </section>

@endsection

@push('scripts-bottom')
    <script>

        const selectShow = $('#select-show');
        let limit = selectShow.val();
        let search = '';

        searchData();

        // limit show with ajax
        selectShow.on('change', function () {
            limit = $(this).val()
            searchData();
        })

        // search user with ajax when after 5 seconds
        let timeout = null;
        $('#search_field').on('keyup', function () {
            clearTimeout(timeout);
            timeout = setTimeout(function () {
                search = $('#search_field').val()
                searchData();
            }, 500);
        })

        // search data
        function searchData(page = 1) {
            showLoading()
            let url = `/patients/doctor/search?search=${search}&limit=${limit}&page=${page}`
            let token = $('meta[name="csrf-token"]').attr('content')

            $.ajax({
                url: url,
                type: 'POST',
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

                if (item.profile == null) {
                    const urlImage = '{{ Vite::asset('resources/img/icons8-male-user.png') }}'
                    image = `<img
                        class="h-10 mr-3"
                        src="${urlImage}"
                        alt="profile patient"
                        style="border-radius: 50%"
                    >`
                } else {
                    const urlImage = `{{ Vite::asset('storage/') }}/${item.profile}`
                    image = `<img
                            class="h-10 mr-3"
                            src="${urlImage}"
                            alt="profile patient"
                            style="border-radius: 50%"
                        >`
                }

                if (item.status === CONST_ACTIVE) {
                    status = `<span class="rounded text-blue-900 bg-blue-100 px-4 py-1 text-sm">
                        Active
                    </span>`
                } else if (item.status === CONST_INACTIVE) {
                    status = `<span class="rounded text-yellow-900 bg-yellow-100 px-4 py-1 text-sm">
                        Inactive
                    </span>`
                }

                const iconMonitoring = `<x-ri-heart-add-fill width='30'/>`
                const iconMessage = `<x-ri-message-2-fill width='30'/>`
                const iconEdit = `<x-ri-edit-2-fill width='30'/>`

                html += `<tr class="bg-white rounded">
                            <td class="px-4 py-2 flex items-center">
                                ${image}
                                ${item.name}
                            </td>
                            <td class="px-4 py-2 text-center capitalize">${formatDate(item.created_at)}</td>
                            <td class="px-4 py-2 text-center capitalize">${item.gender}</td>
                            <td class="px-4 py-2 text-center">Not found</td>
                            <td class="px-4 py-2 text-center">${status}</td>
                            <td class="px-4 py-2 flex items-center">
                                <a
                                    title="Add Monitoring"
                                    href="/my-patients/monitoring?patient_id=${item.id}"
                                    class="rounded text-blue-900 ml-2"
                                >
                                    ${iconMonitoring}
                                </a>
                                <a
                                    title="Message"
                                    href="/messages?patient_id=${item.id}"
                                    class="rounded text-blue-900 ml-2"
                                >
                                    ${iconMessage}
                                </a>
                                <a
                                    title="Edit"
                                    href="/my-patients-doctor/${item.id}/edit?doctor_id=${item.doctorPatient}"
                                    class="rounded text-blue-900 ml-2"
                                >
                                    ${iconEdit}
                                </but>
                            </td>
                        </tr>`
            })

            $('#tbody').html(html)
        }
    </script>
@endpush
