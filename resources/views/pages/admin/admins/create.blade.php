@extends('layouts.admin')

@section('content')
    <x-utils.message-component/>

    <section class="mt-10">
        <form action="{{ route('admins.store') }}" method="post">
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
                        <label for="password_confirmation">Confirm Password</label>
                        <input
                            class="border w-full"
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
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
                <a
                    href="{{ url()->previous() }}"
                    class="rounded bg-white-500 px-4 py-1 w-full border modal-close text-center"
                >
                    Cancel
                </a>
            </div>
        </form>
    </section>
@endsection
