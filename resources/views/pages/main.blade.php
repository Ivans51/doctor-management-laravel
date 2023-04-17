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

    <section class="space-x-0 lg:space-x-4 flex lg:flex-row flex-col items-start">
        <div class="lg:w-2/5 w-full">
            <h3 class="my-2 font-bold text-lg">Appointment Request</h3>
            <div class="px-4 py-2 bg-white rounded">
                @foreach($images as $image)
                    <div class="flex justify-between items-center my-2">
                        <div class="flex items-center">
                            <img class="h-10 mr-3" src="{{$image}}" alt="image animal" style="border-radius: 50%">
                            <div>
                                <p>Jenny Wilson</p>
                                <p>Female, 25 April 10:30 PM</p>
                            </div>
                        </div>
                        <button class="rounded text-red-900 bg-red-100 px-4 py-1 text-sm">
                            Declined
                        </button>
                        {{--<button class="rounded text-blue-900 bg-blue-100 px-4 py-1 text-sm">
                            Confirmed
                        </button>--}}
                    </div>
                @endforeach
            </div>
        </div>

        <div class="lg:w-1/5 w-full">
            <h3 class="my-2 font-bold text-lg">Patients</h3>
            <div class="px-4 py-2 bg-white rounded">
                @foreach($images as $image)
                    <div class="flex justify-between items-center my-2">
                        <div class="flex items-center">
                            <img class="h-10 mr-3" src="{{$image}}" alt="image animal" style="border-radius: 50%">
                            <div>
                                <p>Jenny Wilson</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="lg:w-2/5 w-full">
            <h3 class="my-2 font-bold text-lg">Today Appointments</h3>
            <div class="px-4 py-2 bg-white rounded">
                @foreach($images as $image)
                    <div class="flex justify-between items-center my-2">
                        <div class="flex items-center">
                            <img class="h-10 mr-3" src="{{$image}}" alt="image animal" style="border-radius: 50%">
                            <div>
                                <p>Jenny Wilson</p>
                                <p>Female, 25 April 10:30 PM</p>
                            </div>
                        </div>
                        <button class="rounded text-red-900 bg-red-100 px-4 py-1 text-sm">
                            Declined
                        </button>
                        {{--<button class="rounded text-blue-900 bg-blue-100 px-4 py-1 text-sm">
                            Confirmed
                        </button>--}}
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
