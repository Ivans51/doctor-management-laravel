@extends('layouts.home')

@section('content')
    <section class="space-x-0 lg:space-x-4 flex lg:flex-row flex-col items-start">
        <div class="w-full">
            <h3 class="my-2 font-bold text-lg">Appointment Request</h3>
            <div class="px-4 py-2 bg-white rounded">
                @foreach($images as $image)
                    <div class="flex justify-between items-center my-4">
                        <div class="flex items-start">
                            <img class="h-10 mr-3" src="{{$image}}" alt="image animal" style="border-radius: 50%">
                            <div>
                                <p>Jenny Wilson</p>
                                <p class="text-xs mt-1">Female, 25 April 10:30 PM</p>
                            </div>
                        </div>
                        <button class="rounded text-red-900 bg-red-100 px-4 py-1 text-sm ml-2">
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
