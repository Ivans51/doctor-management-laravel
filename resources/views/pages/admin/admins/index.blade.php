@extends('layouts.admin')

@section('content')
    <section class="mt-10">
        <div class="flex justify-between items-center">
            <h3 class="my-2 font-bold text-lg">Admins list</h3>
            <a
                href="{{ route('admins.create') }}"
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
                <input class="w-full" type="search" id="search_field" placeholder="Name, email">
            </label>
            <label class="w-full lg:w-1/3">
                Date
                <input class="w-full" type="date">
            </label>
        </div>

        <table class="w-full table-auto" style="border-collapse: separate; border-spacing: 0 8px;">
            <tr class="bg-zinc-100">
                <th class="px-4 py-1 text-left">Name</th>
                <th class="px-4 py-1">Email</th>
                <th class="px-4 py-1">Role</th>
                <th class="px-4 py-1">Actions</th>
            </tr>
            <tbody id="tbody">
            @foreach($admins as $admin)
                <tr class="bg-white rounded">
                    <td class="px-4 py-2 flex items-center">
                        <a href="{{ route('my-patients-detail') }}">
                            {{ $admin->name }}
                        </a>
                    </td>
                    <td class="px-4 py-2 text-center">
                        {{ $admin->email }}
                    </td>
                    <td class="px-4 py-2 text-center">
                        {{ $admin->roles->first()->name }}
                    </td>
                    <td class="px-4 py-2 text-center">
                        <a
                            href="{{ route('admins.edit', $admin->id) }}"
                            class="rounded text-green-900 bg-green-100 px-4 py-1 text-sm ml-2 cursor-pointer"
                        >
                            Edit
                        </a>
                        <a
                            href="{{ route('admins.destroy', $admin->id) }}"
                            class="rounded text-red-900 bg-red-100 px-4 py-1 text-sm ml-2"
                            data-confirm-delete="true"
                        >
                            Delete
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </section>

@endsection

@push('scripts-bottom')
    <script>
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
                    let html = ''

                    response.data.forEach(function (item) {
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
            })
        })
    </script>
@endpush
