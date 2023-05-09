@extends('layouts/auth')

@section('content')
    <section class="flex flex-col justify-center h-screen items-center">
        <div class="bg-white p-8 rounded">
            <img
                class="mx-auto mb-5"
                src="{{ Vite::asset('resources/img/home/logo.png') }}"
                alt="doctor management logo"
            >
            <h1 class="font-bold text-xl mb-4 mb-0">Forgot Password</h1>
            <p class="text-zinc-300 mb-8">Enter your email, and we will send you a reset link</p>

            <form action="" method="post">
                <div class="space-y-6">
                    <div class="w-full">
                        <label for="password">Email</label>
                        <input
                            class="border w-full rounded"
                            type="password"
                            name="password"
                            id="password"
                            placeholder="josh@gmail.com"
                        >
                    </div>
                </div>

                <div class="flex items-center space-x-20 mt-10">
                    <button
                        type="submit"
                        class="rounded text-white bg-blue-500 px-4 py-1 w-full"
                    >
                        Send me link
                    </button>
                </div>
            </form>

            <hr class="mt-10 mb-5">

            <p class="text-center">
                Already have an account? <a class="text-blue-500" href="{{ route('login') }}">Sign in</a>
            </p>
        </div>
    </section>
@endsection
