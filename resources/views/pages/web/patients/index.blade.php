@extends('layouts.home')

@section('content')
    <x-utils.loading-component/>

    <section class="max-w-7xl mx-auto px-4 mt-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="font-bold text-2xl text-gray-800">Patient List</h1>
            <a
                href="{{ route('doctor.my-patients-doctor.create') }}"
                class="flex items-center px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white font-medium rounded-md shadow-sm transition duration-150 ease-in-out"
            >
                <x-ri-heart-add-fill class="w-5 h-5 mr-2"/>
                <span>Add Patient</span>
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <div class="flex items-center">
                    <label class="flex items-center">
                        <span class="text-sm font-medium text-gray-700 mr-2">Show</span>
                        <select class="rounded-md border-gray-300 py-2 pl-3 pr-8 text-sm focus:border-blue-500 focus:ring-blue-500" name="select-show" id="select-show">
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
                            <input
                                type="search"
                                name="search"
                                id="search_field"
                                placeholder="Patient name, status"
                                class="pl-10 w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            >
                        </div>
                    </div>

                    <div class="md:w-1/3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input
                                type="date"
                                class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            >
                        </div>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diseases</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
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
                error: function () {
                    hideLoading()
                }
            })
        }

        function setDataTable(response) {
            let html = ''

            setPagination(response)

            response.data.data.forEach(function (item) {
                let image = ''
                let status

                if (item.profile == null) {
                    const urlImage = '{{ Vite::asset('resources/img/icons8-male-user.png') }}'
                    image = `<img
                        class="h-10 w-10 rounded-full mr-3"
                        src="${urlImage}"
                        alt="profile patient"
                    >`
                }

                if (item.status === CONST_ACTIVE) {
                    status = `<span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                        Active
                    </span>`
                } else if (item.status === CONST_INACTIVE) {
                    status = `<span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                        Inactive
                    </span>`
                }

                const iconMessage = `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z" clip-rule="evenodd" /></svg>`;
                const iconEdit = `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg>`;

                html += `<tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    ${image}
                                    <span class="font-medium text-gray-900">${item.name}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDate(item.created_at)}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">${item.gender}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Not found</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">${status}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-3">
                                    <a
                                        href="/messages?patient_id=${item.id}"
                                        class="text-blue-600 hover:text-blue-900 transition-colors duration-200"
                                        title="Message"
                                    >
                                        ${iconMessage}
                                    </a>
                                    <a
                                        href="/my-patients-doctor/${item.id}/edit?doctor_id=${item.doctorPatient}"
                                        class="text-blue-600 hover:text-blue-900 transition-colors duration-200"
                                        title="Edit"
                                    >
                                        ${iconEdit}
                                    </a>
                                </div>
                            </td>
                        </tr>`
            })

            $('#tbody').html(html)
        }
    </script>
@endpush
