@extends('layouts.admin')

@section('content')
    <x-utils.loading-component/>

    <section>
        <div class="flex justify-between items-center">
            <h3 class="my-2 font-bold text-lg">Admins list</h3>
            <a
                href="{{ route('admin.admins.create') }}"
                class="rounded text-white bg-blue-500 px-4 py-1 text-sm ml-2 flex items-center flex-row"
            >
                <span>Add</span>
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
            <label class="w-full">
                Search
                <input class="w-full" type="search" id="search_field" placeholder="Name, email">
            </label>
        </div>

        <div class="overflow-x-auto">
            <table class="table-auto border-separate w-full border-spacing-x-0 border-spacing-y-2">
                <thead>
                <tr class="bg-secondary">
                    <th class="px-4 py-1 text-left">Name</th>
                    <th class="px-4 py-1">Email</th>
                    <th class="px-4 py-1">Role</th>
                    <th class="px-4 py-1">Actions</th>
                </tr>
                </thead>
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
            let url = `/admin/admins/search?search=${search}&limit=${limit}&page=${page}`
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
                let btnDelete

                if (item.id !== {{ auth()->user()->id }}) {
                    btnDelete = `<button
                                    onclick="deleteUser('${item.id}')"
                                    class="rounded text-red-900 bg-red-100 px-4 py-1 text-sm ml-2 cursor-pointer"
                                >
                                    Delete
                                </button>`
                } else {
                    btnDelete = ''
                }

                html += `<tr class="bg-white rounded">
                            <td class="px-4 py-2 text-center">${item.name}</td>
                            <td class="px-4 py-2 text-center">${item.email}</td>
                            <td class="px-4 py-2 text-center">${item.email}</td>
                            <td class="px-4 py-2 text-center">
                                <a
                                    href="/admin/admins/${item.id}/edit"
                                    class="rounded text-green-900 bg-green-100 px-4 py-1 text-sm ml-2 cursor-pointer"
                                >
                                    Edit
                                </a>
                                ${btnDelete}
                            </td>
                        </tr>`
            })

            $('#tbody').html(html)
        }

        // delete with ajax
        function deleteUser(id) {
            deleteSwal().then(() => {
                showLoading()
                let url = `/admin/admins/${id}`
                let token = $('meta[name="csrf-token"]').attr('content')

                $.ajax({
                    url: url,
                    type: 'DELETE',
                    dataType: 'json',
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
