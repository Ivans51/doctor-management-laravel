@extends('layouts.admin')

@section('content')
    <x-utils.loading-component/>

    <section class="max-w-7xl mx-auto px-4 mt-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="font-bold text-2xl text-gray-800">Doctors List</h1>
            <a
                href="{{ route('admin.doctors.create') }}"
                class="flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md shadow-sm transition duration-150 ease-in-out"
            >
                <span>Add</span>
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <div class="flex items-center">
                    <label class="flex items-center">
                        <span class="text-sm font-medium text-gray-700 mr-2">Show</span>
                        <select class="rounded-md border-gray-300 py-2 pl-3 pr-8 text-sm focus:border-blue-500 focus:ring-blue-500" name="show" id="select-show">
                            <option value="10">10</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <span class="text-sm font-medium text-gray-700 ml-2">entries</span>
                    </label>
                </div>
                <div class="flex flex-1 md:flex-row flex-col gap-3">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input class="pl-10 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" type="search" placeholder="Name, email" id="search_field" name="search_field">
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Speciality</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tbody" class="bg-white divide-y divide-gray-200"></tbody>
                </table>
            </div>

            <div class="mt-4">
                <x-utils.pagination-component/>
            </div>
        </div>
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
            let url = `/admin/doctors/search?search=${search}&limit=${limit}&page=${page}`
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
                error: function () {
                    hideLoading()
                }
            })
        }

        function setDataTable(response) {
            let html = ''

            setPagination(response)

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
                                    href="/admin/patients?doctorId=${item.id}"
                                    class="rounded text-blue-900 bg-blue-100 px-4 py-1 text-sm ml-2"
                                >
                                    Patients
                                </a>
                                <a
                                    href="/admin/payments?doctorId=${item.id}"
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
