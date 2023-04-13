@extends('home')

@section('content')
    <h1 class="font-bold text-2xl mb-1">Welcome, Dr. Stephen</h1>
    <p class="text-sm">Have a nice day at great work</p>

    <section id="head-info" class="text-white my-6 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

        <div class="box-context" style="background: #8364fe">
            <div class="box-left">
                <x-ri-calendar-line/>
            </div>
            <div class="ml-4">
                <h3>24.4 K</h3>
                <p>Appointments</p>
            </div>
        </div>

        <div class="box-context" style="background: #f75166">
            <div class="box-left">
                <x-ri-user-3-line/>
            </div>
            <div class="ml-4">
                <h3>24.4 K</h3>
                <p>Total Patient</p>
            </div>
        </div>

        <div class="box-context" style="background: #f8ab16">
            <div class="box-left">
                <x-ri-video-line/>
            </div>
            <div class="ml-4">
                <h3>24.4 K</h3>
                <p>Clinic Consulting</p>
            </div>
        </div>

        <div class="box-context" style="background: #4ca4f9">
            <div class="box-left">
                <x-ri-video-chat-line/>
            </div>
            <div class="ml-4">
                <h3>24.4 K</h3>
                <p>Video Consulting</p>
            </div>
        </div>

    </section>
@endsection
