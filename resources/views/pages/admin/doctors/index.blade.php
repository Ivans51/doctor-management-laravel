@extends('layouts.admin')

@section('content')
    <x-utils.loading-component/>

    <section class="mt-10">
        <div class="flex justify-between items-center">
            <h3 class="my-2 font-bold text-lg">Doctors list</h3>
            <a
                href="{{ route('doctors.create') }}"
                class="rounded text-white bg-blue-500 px-4 py-1 text-sm ml-2 flex items-center flex-row"
            >
                <span>Add</span>
            </a>
        </div>

        <button id="modal-open" class="hidden"></button>

        <div class="my-4 flex flex-col lg:flex-row space-x-0 lg:space-x-4 space-y-2 lg:space-y-0 w-full">
            <label>Show
                <select class="bg-white p-1 border ml-2" name="show" id="show">
                    <option value="10">10</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </label>
        </div>

        <div class="my-4 flex flex-col lg:flex-row space-x-0 lg:space-x-4 space-y-2 lg:space-y-0 w-full">
            <label class="w-full lg:w-2/3">
                Search
                <input class="w-full" type="search" placeholder="Name, email">
            </label>
        </div>

        <div class="overflow-x-auto">
            <table class="table-auto border-separate w-full border-spacing-x-0 border-spacing-y-2">
                <tr class="bg-zinc-100">
                    <th class="px-4 py-1 text-left">Name</th>
                    <th class="px-4 py-1">Email</th>
                    <th class="px-4 py-1">Speciality</th>
                    <th class="px-4 py-1">Status</th>
                    <th class="px-4 py-1">Data</th>
                    <th class="px-4 py-1">Actions</th>
                </tr>
                <tbody id="tbody">
            </table>
        </div>
    </section>

@endsection

@push('scripts-bottom')
    <script>
        const selectShow = $('#select-show');
        let limit = selectShow.val();

        getData();

        // limit show with ajax
        selectShow.on('change', function () {
            limit = $(this).val()
            getData();
        })

        function getData() {
            let url = '/admin/doctors/index/limit'
            let token = $('meta[name="csrf-token"]').attr('content')

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    limit: limit,
                    _token: token
                },
                success: function (response) {
                    setDataTable(response);
                }
            })
        }

        // search user with ajax
        $('#search_field').on('keyup', function () {
            let search = $(this).val()
            let url = '/admin/doctors/search'
            let token = $('meta[name="csrf-token"]').attr('content')

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    search: search,
                    _token: token
                },
                success: function (response) {
                    setDataTable(response);
                }
            })
        })

        function setDataTable(response) {
            let html = ''

            response.data.data.forEach(function (item) {
                html += `<tr class="bg-white rounded">
                            <td class="px-4 py-2 text-center">${item.name}</td>
                            <td class="px-4 py-2 text-center">${item.user.email}</td>
                            <td class="px-4 py-2 text-center">${item.speciality}</td>
                            <td class="px-4 py-2 text-center">
                                ${item.status === 'active' ? 'Activo' : 'Inactivo'}
                            </td>
                            <td class="px-4 py-2 text-center">
                                <a
                                    href="/admin/patients/${item.id}"
                                    class="rounded text-blue-900 bg-blue-100 px-4 py-1 text-sm ml-2"
                                >
                                    Patients
                                </a>
                                <a
                                    href="/admin/payments/${item.id}"
                                    class="rounded text-blue-900 bg-blue-100 px-4 py-1 text-sm ml-2"
                                >
                                    Payments
                                </a>
                            </td>
                            <td class="px-4 py-2 text-center">
                                <a
                                    href="/admin/doctors/${item.id}/edit"
                                    class="rounded text-green-900 bg-green-100 px-4 py-1 text-sm ml-2 cursor-pointer"
                                >
                                    Edit
                                </a>
                                <button
                                    onclick="deleteDoctor('${item.id}')"
                                    class="rounded text-red-900 bg-red-100 px-4 py-1 text-sm ml-2"
                                    data-confirm-delete="true"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>`
            })

            $('#tbody').html(html)
        }

        // delete with ajax
        function deleteDoctor(id) {
            deleteSwal().then(() => {
                showLoading()
                let url = `/admin/doctors/${id}`
                let token = $('meta[name="csrf-token"]').attr('content')

                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: token
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            hideLoading()
                            successSwal()
                            getData();
                        }
                    },
                    error: function (response) {
                        hideLoading()
                        errorSwal(response)
                    }
                })
            })
        }
    </script>
@endpush
