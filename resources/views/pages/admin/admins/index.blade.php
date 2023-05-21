@extends('layouts.admin')

@section('content')
    <section>
        <div class="flex justify-between items-center">
            <h3 class="my-2 font-bold text-lg">Admins list</h3>
            <a
                href="{{ route('admins.create') }}"
                class="rounded text-white bg-blue-500 px-4 py-1 text-sm ml-2 flex items-center flex-row"
            >
                <span>Add</span>
            </a>
            <button
                onclick="sendPushNotification()"
                class="rounded text-white bg-blue-500 px-4 py-1 text-sm ml-2 flex items-center flex-row"
            >
                <span>Send push</span>
            </button>
        </div>

        <button id="modal-open" class="hidden"></button>

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
                <tr class="bg-zinc-100">
                    <th class="px-4 py-1 text-left">Name</th>
                    <th class="px-4 py-1">Email</th>
                    <th class="px-4 py-1">Role</th>
                    <th class="px-4 py-1">Actions</th>
                </tr>
                </thead>
                <tbody id="tbody"></tbody>
            </table>
        </div>
    </section>

@endsection

@push('scripts-bottom')
    <script>
        function sendPushNotification() {
            let url = '/admin/admins/send/push'
            let token = $('meta[name="csrf-token"]').attr('content')

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: token,
                    title: 'Test title',
                    body: 'Test body',
                    fcm_token: 'fcm_token'
                },
                success: function (response) {
                    console.log(response)
                }
            })
        }

        const selectShow = $('#select-show');
        let limit = selectShow.val();

        getData();

        // limit show with ajax
        selectShow.on('change', function () {
            limit = $(this).val()
            getData();
        })

        function getData() {
            let url = '/admin/admins/index/limit'
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
            let url = '/admin/admins/search'
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
                            <td class="px-4 py-2 text-center">${item.email}</td>
                            <td class="px-4 py-2 text-center">${item.email}</td>
                            <td class="px-4 py-2 text-center">
                                <a
                                    onclick="openModal(${item})"
                                    class="rounded text-green-900 bg-green-100 px-4 py-1 text-sm ml-2 cursor-pointer"
                                >
                                    Edit
                                </a>
                                <a
                                    href="admin/admins/delete-user/${item.id}"
                                    class="rounded text-red-900 bg-red-100 px-4 py-1 text-sm ml-2"
                                    data-confirm-delete="true"
                                >
                                    Delete
                                </a>
                            </td>
                        </tr>`
            })

            $('#tbody').html(html)
        }
    </script>
@endpush
