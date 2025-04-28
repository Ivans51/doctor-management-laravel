@extends('layouts/auth')

@section('content')
    <section class="flex flex-col justify-center h-screen items-center">
        <div class="bg-white p-8 rounded max-w-xl">
            <img class="mx-auto mb-5" src="{{ Vite::asset('resources/img/home/logo.png') }}" alt="doctor management logo">
            <h1 class="font-bold text-xl mb-4 text-center">Sign Up</h1>

            <x-utils.message-component />

            <form action="{{ route('doctor.form.register') }}" method="post">
                @csrf

                <div class="space-y-6">
                    <div class="w-full">
                        <label for="name">Your name</label>
                        <input class="border w-full rounded" type="text" name="name" id="name"
                            placeholder="Josh">
                    </div>

                    <div class="w-full">
                        <label for="email">Email</label>
                        <input class="border w-full rounded" type="email" name="email" id="email"
                            placeholder="josh@gmail.com">
                    </div>

                    <div class="grid grid-cols-2 gap-x-4">
                        <div class="w-full">
                            <label for="password">Password</label>
                            <div class="relative">
                                <input
                                    class="border w-full rounded"
                                    type="password"
                                    name="password"
                                    id="password"
                                    placeholder="*********"
                                >
                                <button
                                    type="button"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none"
                                    onclick="togglePasswordVisibility('password')"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="w-full">
                            <label for="password_confirmation">Confirm Password</label>
                            <div class="relative">
                                <input
                                    class="border w-full rounded"
                                    type="password"
                                    name="password_confirmation"
                                    id="password_confirmation"
                                    placeholder="*********"
                                >
                                <button
                                    type="button"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none"
                                    onclick="togglePasswordVisibility('password_confirmation')"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="cf-turnstile mt-5" data-sitekey="{{ config('services.turnstile.sitekey') }}"></div>

                <div class="flex items-center space-x-20 mt-5">
                    <button type="submit" class="rounded text-white bg-blue-500 px-4 py-1 w-full">
                        Sing up
                    </button>
                </div>
            </form>

            <hr class="mt-10 mb-5">

            <p class="text-center">
                Already have an account? <a class="text-blue-500" href="{{ route('doctor.login') }}">Sign in</a>
            </p>
        </div>
    </section>
@endsection

@push('scripts-bottom')
    <script>
        function togglePasswordVisibility(fieldId) {
            const field = document.getElementById(fieldId);
            const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
            field.setAttribute('type', type);
        }
    </script>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
@endpush
