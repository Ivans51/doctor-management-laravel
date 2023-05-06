@extends('home')

@section('content')
    <h3 class="font-bold text-lg mb-10">Monitoring Plan</h3>

    <div class="flex items-start space-x-0 md:space-x-4">
        <section class="w-3/5">
            <div class="space-y-4 mb-6">
                <p id="date-monitor">11/07/2021 @ 11:11</p>
                <p id="doctor-monitor">Dr. Sphen Conley</p>
                <p id="message-monitor">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium alias
                    doloribus eius facilis id illum impedit iure laborum officiis optio, quas quasi quia ullam.
                    Cupiditate doloremque id qui quia sed!
                </p>
                <p id="amount-monitor" class="text-sky-400 font-bold">
                    Amount: $300
                </p>
            </div>

            <table class="w-full table-auto text-left" style="border-collapse: separate; border-spacing: 0 8px;">
                <tr class="bg-zinc-100">
                    <th class="px-4 py-1">Monitored trackers</th>
                    <th class="px-4 py-1">Reporting Time & Reminder</th>
                </tr>
                @foreach($images as $image)
                    <tr class="bg-white rounded">
                        <td class="px-4 py-2">
                            1
                        </td>
                        <td class="px-4 py-2">
                            1
                        </td>
                    </tr>
                @endforeach
            </table>
        </section>

        <section class="w-2/5 bg-white rounded p-6">
            <div class="flex flex-col items-center">
                <img
                    class="mb-2 w-24 h-24"
                    src="{{ Vite::asset('resources/img/home/logo.png') }}"
                    alt="patient profile image"
                    style="border-radius: 50%"
                >
                <p class="font-bold">Mr. Jone Martin</p>
                <p class="text-zinc-400">22 Years, Male</p>
            </div>

            <hr class="my-4">

            <div class="space-y-4">
                <div>
                    <p class="text-zinc-400">Email</p>
                    <p>test@gmail.com</p>
                </div>
                <div>
                    <p class="text-zinc-400">Phone</p>
                    <p>(707) 555-0710</p>
                </div>
                <div>
                    <p class="text-zinc-400">Date of Birth</p>
                    <p>14 February 2021</p>
                </div>
                <div>
                    <p class="text-zinc-400">Diseases</p>
                    <p>Cardiology</p>
                </div>
            </div>

            <hr class="my-4">
        </section>
    </div>

@endsection
