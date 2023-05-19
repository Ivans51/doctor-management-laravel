@extends('layouts.admin')

@section('content')
    <section class="mt-10">
        <div class="flex justify-between items-center">
            <h3 class="my-2 font-bold text-lg">Admins list</h3>
            <button
                onclick="openModal()"
                class="rounded text-white bg-blue-500 px-4 py-1 text-sm ml-2 flex items-center flex-row"
            >
                <span>Add</span>
            </button>
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
                            onclick="openModal({{ $admin }})"
                            class="rounded text-green-900 bg-green-100 px-4 py-1 text-sm ml-2 cursor-pointer"
                        >
                            Edit
                        </a>
                        <a
                            href="{{ route('delete-user', 1) }}"
                            class="rounded text-red-900 bg-red-100 px-4 py-1 text-sm ml-2"
                            data-confirm-delete="true"
                        >
                            Delete
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>
    </section>

    <x-modal.modal-component
        title="Form"
        modalClass="modal"
    >
        <x-slot name="content">
            <form action="" method="post">
                @csrf

                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-x-4">
                        <div>
                            <label for="name">Name</label>
                            <input class="border w-full" type="text" name="name" id="name">
                        </div>
                        <div>
                            <label for="email">Email</label>
                            <input class="border w-full" type="email" name="email" id="email">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-x-4">
                        <div>
                            <label for="password">Password</label>
                            <input
                                class="border w-full"
                                type="password"
                                name="password"
                                id="password"
                                placeholder="*********"
                            >
                        </div>
                        <div>
                            <label for="confirm_password">Confirm Password</label>
                            <input
                                class="border w-full"
                                type="password"
                                name="confirm_password"
                                id="confirm_password"
                                placeholder="*********"
                            >
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-2 mt-10">
                    <button
                        type="submit"
                        class="rounded text-white bg-blue-500 px-4 py-1 w-full"
                    >
                        Save
                    </button>
                    <button
                        type="button"
                        class="rounded bg-white-500 px-4 py-1 w-full border modal-close"
                    >
                        Cancel
                    </button>
                </div>
            </form>
        </x-slot>
    </x-modal.modal-component>

@endsection

@push('scripts-bottom')
    <script>
        configModal('modal', 'modal-open')

        function openModal(data) {
            if (data) {
                $('#name').val(data.name)
                $('#email').val(data.email)
                $('#password').val(data.password)
                $('#confirm_password').val(data.password)
                $('form').attr('action', '/admin/admins/' + data.id)
            } else {
                $('#name').val('')
                $('#email').val('')
                $('#password').val('')
                $('#confirm_password').val('')
                $('form').attr('action', '/admin/admins')
            }
            document.getElementById('modal-open').click();
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
                success: function (data) {
                    $('tbody').html(data)
                }
            })
        })
    </script>
@endpush
