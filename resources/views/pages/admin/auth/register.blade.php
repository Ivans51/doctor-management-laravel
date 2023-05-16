@extends('layouts/auth')

@section('content')
    <section class="flex flex-col justify-center h-screen items-center">
        <div class="bg-white p-8 rounded max-w-xl">
            <img
                class="mx-auto mb-5"
                src="{{ Vite::asset('resources/img/home/logo.png') }}"
                alt="doctor management logo"
            >
            <h1 class="font-bold text-xl mb-4 text-center">Sign Up</h1>

            @error('captcha')
            <span class="text-red-500 text-xs italic">{{ $message }}</span>
            @enderror

            @if(session('auth_message'))
                <div class="text-green-500">
                    {{ session('auth_message') }}
                </div>
            @endif

            <form action="{{ route('admin-register') }}" method="post">
                @csrf

                <input type="hidden" name="recaptcha" id="recaptcha">

                <div class="space-y-6">
                    <div class="w-full">
                        <label for="name">Your name</label>
                        <input
                            class="border w-full rounded"
                            type="text"
                            name="name"
                            id="name"
                            placeholder="Josh"
                        >
                    </div>

                    <div class="w-full">
                        <label for="email">Email</label>
                        <input
                            class="border w-full rounded"
                            type="email"
                            name="email"
                            id="email"
                            placeholder="josh@gmail.com"
                        >
                    </div>

                    <div class="grid grid-cols-2 gap-x-4">
                        <div class="w-full">
                            <label for="password">Password</label>
                            <input
                                class="border w-full rounded"
                                type="password"
                                name="password"
                                id="password"
                                placeholder="*********"
                            >
                        </div>

                        <div class="w-full">
                            <label for="confirm_password">Confirm Password</label>
                            <input
                                class="border w-full rounded"
                                type="password"
                                name="confirm_password"
                                id="confirm_password"
                                placeholder="*********"
                            >
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-20 mt-10">
                    <button
                        type="submit"
                        class="rounded text-white bg-blue-500 px-4 py-1 w-full"
                    >
                        Sing up
                    </button>
                </div>
            </form>

            <hr class="mt-10 mb-5">

            <p class="text-center">
                Already have an account? <a class="text-blue-500" href="{{ route('admin-login') }}">Sign in</a>
            </p>
        </div>
    </section>
@endsection

@push('scripts-bottom')
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.sitekey') }}"></script>
    <script>
        grecaptcha.ready(function () {
            grecaptcha.execute('{{ config('services.recaptcha.sitekey') }}', {action: 'contact'}).then(function (token) {
                if (token) {
                    document.getElementById('recaptcha').value = token;
                }
            });
        });
    </script>
@endpush
