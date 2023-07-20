@extends('layouts.admin')

@section('content')
    <x-utils.loading-component/>

    <section class="mt-10">
        <div class="flex justify-between items-center">
            <h3 class="my-2 font-bold text-lg">Medical Specialty list</h3>
            <a
                href="{{ route('admin.medical.create') }}"
                class="rounded text-white bg-blue-500 px-4 py-1 text-sm ml-2 flex items-center flex-row"
            >
                <span>Add</span>
            </a>
        </div>

        <div class="my-4 flex flex-col lg:flex-row space-x-0 lg:space-x-4 space-y-2 lg:space-y-0 w-full">
            <label>Show
                <select class="bg-white p-1 border ml-2" name="show" id="select-show">
                    <option value="10">10</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </label>
        </div>

        <div class="my-4 flex flex-col lg:flex-row space-x-0 lg:space-x-4 space-y-2 lg:space-y-0 w-full">
            <label class="w-full lg:w-2/3">
                Search
                <input class="w-full" type="search" placeholder="Name, description">
            </label>
        </div>

        <div class="overflow-x-auto">
            <table class="table-auto border-separate w-full border-spacing-x-0 border-spacing-y-2">
                <tr class="bg-secondary">
                    <th class="px-4 py-1 text-left">Name</th>
                    <th class="px-4 py-1">Description</th>
                    <th class="px-4 py-1">Price</th>
                    <th class="px-4 py-1">Currency</th>
                    <th class="px-4 py-1">Date</th>
                    <th class="px-4 py-1">Actions</th>
                </tr>
                <tbody id="tbody">
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
            let url = `/admin/medical/search?search=${search}&limit=${limit}&page=${page}`
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
                // limit text to 50 characters in description and add three dots
                html += `<tr class="bg-white rounded">
                            <td class="px-4 py-2 text-center">${item.name}</td>
                            <td class="px-4 py-2 text-center">${limitText(item.description)}</td>
                            <td class="px-4 py-2 text-center">${formatNumber(item.price)}</td>
                            <td class="px-4 py-2 text-center">${formatDate(item.created_at)}</td>
                            <td class="px-4 py-2 text-center">
                                ${item.status === 'active' ? 'Activo' : 'Inactivo'}
                            </td>
                            <td class="px-4 py-2 text-center">
                                <a
                                    href="/admin/medical/${item.id}/edit"
                                    class="rounded text-green-900 bg-green-100 px-4 py-1 text-sm ml-2 cursor-pointer"
                                >
                                    Edit
                                </a>
                            </td>
                        </tr>`
            })

            $('#tbody').html(html)
        }
    </script>
@endpush
