@extends('layouts/auth')

@section('content')
    <section class="flex flex-col justify-center h-screen items-center">
        <div class="bg-white p-8 rounded max-w-xl">
            <img class="mx-auto mb-5" src="{{ Vite::asset('resources/img/home/logo.png') }}" alt="doctor management logo">
            <h1 class="font-bold text-xl mb-4 text-center">Sign Up</h1>

            <x-utils.message-component />

            <form action="{{ route('admin.form.register') }}" method="post">
                @csrf

                <div class="space-y-6">
                    <div class="w-full">
                        <label for="name">Your name</label>
                        <input class="border w-full rounded" type="text" name="name" id="name" value="Josh"
                            placeholder="Josh">
                    </div>

                    <div class="w-full">
                        <label for="email">Email</label>
                        <input class="border w-full rounded" type="email" name="email" id="email"
                            value="josh@gmail.com" placeholder="josh@gmail.com">
                    </div>

                    <div class="grid grid-cols-2 gap-x-4">
                        <div class="w-full">
                            <label for="password">Password</label>
                            <input class="border w-full rounded" type="password" name="password" id="password"
                                value="12345678" placeholder="*********">
                        </div>

                        <div class="w-full">
                            <label for="password_confirmation">Confirm Password</label>
                            <input class="border w-full rounded" type="password" name="password_confirmation"
                                id="password_confirmation" value="12345678" placeholder="*********">
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
                Already have an account? <a class="text-blue-500" href="{{ route('admin.login') }}">Sign in</a>
            </p>
        </div>
    </section>
@endsection

@push('scripts-bottom')
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
@endpush
