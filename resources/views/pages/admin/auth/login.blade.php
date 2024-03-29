@extends('layouts.auth')

@section('content')
    <section class="flex flex-col justify-center h-screen items-center">
        <div class="bg-white p-8 rounded max-w-xl">
            <img
                class="mx-auto mb-5"
                src="{{ Vite::asset('resources/img/home/logo.png') }}"
                alt="doctor management logo"
            >
            <h1 class="font-bold text-xl mb-4 text-center">Sign In</h1>

            <x-utils.message-component/>

            <form action="{{ route('admin.form.login') }}" method="post">
                @csrf

                <input type="hidden" name="recaptcha" id="recaptcha">

                <div class="space-y-6">
                    <div class="w-full">
                        <label for="email">Email</label>
                        <input
                            class="border w-full rounded"
                            type="email"
                            name="email"
                            id="email"
                            value="test@example.com"
                            placeholder="josh@gmail.com"
                        >
                    </div>
                    <div class="w-full">
                        <label for="password">Password</label>
                        <input
                            class="border w-full rounded"
                            type="password"
                            name="password"
                            id="password"
                            value="password"
                            placeholder="*********"
                        >
                    </div>
                </div>

                <div class="flex items-center space-x-20 mt-10">
                    <button
                        type="submit"
                        class="rounded text-white bg-blue-500 px-4 py-1 w-full"
                    >
                        Login
                    </button>
                </div>

                <div class="text-right text-blue-500 my-5">
                    <a href="{{ route('admin.forgot') }}">Forgot Password?</a>
                </div>
            </form>
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
