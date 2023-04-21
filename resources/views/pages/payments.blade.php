@extends('home')

@section('content')
    <h1 class="font-bold text-2xl mb-1">Welcome, Dr. Stephen</h1>
    <p class="text-sm">Have a nice day at great work</p>

    <section class="mt-10">
        <h3 class="my-2 font-bold text-lg">Recent Patients</h3>
        <table class="w-full table-auto" style="border-collapse: separate; border-spacing: 0 8px;">
            <tr class="bg-zinc-100">
                <th class="px-4 py-1 text-left">Patient Name</th>
                <th class="px-4 py-1">Visit Id</th>
                <th class="px-4 py-1">Date</th>
                <th class="px-4 py-1">Gender</th>
                <th class="px-4 py-1">Diseases</th>
                <th class="px-4 py-1">Status</th>
            </tr>
            @foreach($images as $image)
                <tr class="bg-white rounded">
                    <td class="px-4 py-2 flex items-center">
                        <img class="h-10 mr-3" src="{{$image}}" alt="image animal" style="border-radius: 50%">
                        Jenny Wilson
                    </td>
                    <td class="px-4 py-2 text-center">
                        1
                    </td>
                    <td class="px-4 py-2 text-center">
                        1
                    </td>
                    <td class="px-4 py-2 text-center">
                        1
                    </td>
                    <td class="px-4 py-2 text-center">
                        1
                    </td>
                    <td class="px-4 py-2 text-center">
                        <button class="rounded text-red-900 bg-red-100 px-4 py-1 text-sm ml-2">
                            Options
                        </button>
                    </td>
                </tr>
            @endforeach
        </table>
    </section>
@endsection
