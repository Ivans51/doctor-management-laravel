@extends('layouts.admin')

@section('content')
    <section class="mt-10">
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
    </section>
@endsection
