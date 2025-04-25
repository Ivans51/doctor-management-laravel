@php use App\Utils\Constants; @endphp
@extends('layouts.home')

@section('content')
    <h1 class="font-bold text-2xl mb-1">Welcome, Dr. {{ auth()->user()->doctor->name }}</h1>
    <p class="text-sm">Have a nice day at great work</p>

    <section id="head-info" class="text-white my-6 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        <div class="box-context" style="background: #8364fe">
            <div class="box-left">
                <x-ri-calendar-line/>
            </div>
            <div class="ml-4">
                <h3 class="font-bold text-xl">{{ number_format($countAppointments) }}</h3>
                <p>Appointments</p>
            </div>
        </div>

        <div class="box-context" style="background: #f75166">
            <div class="box-left">
                <x-ri-user-3-line/>
            </div>
            <div class="ml-4">
                <h3 class="font-bold text-xl">{{ number_format($countPatients) }}</h3>
                <p>Total Patient</p>
            </div>
        </div>
    </section>

    <section class="space-x-0 lg:space-x-4 flex lg:flex-row flex-col items-start">
        <div class="w-full">
            <div class="flex items-center justify-between">
                <h3 class="my-2 font-bold text-lg">Appointment Request</h3>
                <a class="text-blue-400" href="{{ route('doctor.appointments') }}">View All ></a>
            </div>
            <div class="px-4 py-2 bg-white rounded">
                @if(sizeof($appointments) == 0)
                    <x-utils.not-data
                        title="No Appointments"
                        description="There are no appointments"
                    />
                @else
                    @foreach($appointments as $item)
                        @if($item->patient)
                            <div class="flex justify-between items-center my-4">
                                <div class="flex items-start">
                                    @if($item->patient->profile == null)
                                        <img
                                            class="h-10 mr-3"
                                            src="{{ Vite::asset('resources/img/icons8-male-user.png') }}"
                                            alt="profile patient"
                                            style="border-radius: 50%"
                                        >
                                    @else
                                        <img
                                            class="h-10 mr-3"
                                            src="{{asset('storage/'.$item->patient->profile)}}"
                                            alt="profile patient"
                                            style="border-radius: 50%"
                                        >
                                    @endif
                                    <div>
                                        <p>{{ $item->patient->name }}</p>
                                        <p class="text-xs mt-1">
                                            {{ $item->patient->gender }}
                                        </p>
                                    </div>
                                </div>
                                @if($item->status == Constants::$APPROVED)
                                    <span class="rounded text-blue-900 bg-blue-100 px-4 py-1 text-sm">
                                    Confirmed
                                </span>
                                @elseif($item->status == Constants::$PENDING)
                                    <span class="rounded text-yellow-900 bg-yellow-100 px-4 py-1 text-sm">
                                    Pending
                                </span>
                                @else
                                    <span class="rounded text-red-900 bg-red-100 px-4 py-1 text-sm">
                                    Cancelled
                                </span>
                                @endif
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
    </section>

    <section class="space-x-0 lg:space-x-4 flex lg:flex-row flex-col items-stretch mb-3">
        <div class="lg:w-3/6 w-full flex flex-col">
            <h3 class="my-2 font-bold text-lg">Today Appointments</h3>
            <div class="px-4 py-2 bg-white rounded flex flex-col justify-center items-center min-h-[250px]">
                @if(sizeof($appointmentsToday) == 0)
                    <x-utils.not-data
                        title="No Appointments"
                        description="There are no appointments today"
                    />
                @else
                    @foreach($appointmentsToday as $item)
                        <div class="flex justify-between items-center my-4">
                            <div class="flex items-start">
                                <x-utils.image-profile-component :item="$item->patient"/>
                                <div>
                                    <p>{{ $item->patient->name }}</p>
                                    <p class="text-xs mt-1">
                                        {{ $item->patient->gender }}
                                    </p>
                                </div>
                            </div>
                            <p class="text-sm mt-1">
                                {{ $item->schedule->start_time }}
                            </p>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="lg:w-3/6 w-full flex flex-col">
            <h3 class="my-2 font-bold text-lg">Gender</h3>
            <div class="px-4 py-2 bg-white rounded min-h-[250px] flex justify-center items-center">
                <div class="h-44">
                    {!! $chart->container() !!}
                </div>
            </div>
        </div>
    </section>

    <section class="mt-10">
        <h3 class="my-2 font-bold text-lg">Recent Patients</h3>

        @if(sizeof($patients) == 0)
            <x-utils.not-data
                title="No Patients"
                description="There are no patients today"
            />
        @else
            <div class="overflow-x-auto">
                <table class="table-auto border-separate w-full border-spacing-x-0 border-spacing-y-2">
                    <tr class="bg-secondary">
                        <th class="px-4 py-3 text-left">Patient Name</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Gender</th>
                        <th class="px-4 py-3">Diseases</th>
                        <th class="px-4 py-3">Status</th>
                    </tr>
                    @foreach($patients as $item)
                        <tr class="bg-white rounded">
                            <td class="px-4 py-2 flex items-center">
                                @if($item->profile == null)
                                    <img
                                        class="h-10 mr-3"
                                        src="{{ Vite::asset('resources/img/icons8-male-user.png') }}"
                                        alt="profile patient"
                                        style="border-radius: 50%"
                                    >
                                @else
                                    <img
                                        class="h-10 mr-3"
                                        src="{{asset('storage/'.$item->profile)}}"
                                        alt="profile patient"
                                        style="border-radius: 50%"
                                    >
                                @endif
                                {{ $item->name }}
                            </td>
                            <td class="px-4 py-2 text-center">
                                {{ $item->created_at->format('d M Y') }}
                            </td>
                            <td class="px-4 py-2 text-center">
                                {{ $item->gender }}
                            </td>
                            <td class="px-4 py-2 text-center">
                                Not found
                            </td>
                            <td class="px-4 py-2 text-center">

                                @if($item->status == Constants::$ACTIVE)
                                    <div class="flex items-center">
                                        <span class="bg-green-500 rounded-full block w-2 h-2 mr-2"></span>
                                        Active
                                    </div>
                                @else
                                    <div class="flex items-center">
                                        <span class="bg-red-500 rounded-full block w-2 h-2 mr-2"></span>
                                        Inactive
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        @endif
    </section>

    {!! $chart->script() !!}
@endsection
