@extends('layouts.admin')

@section('content')
    <section class="mt-10">
        <h3 class="my-2 font-bold text-lg">Payment List</h3>

        <div class="my-4 flex flex-col lg:flex-row space-x-0 lg:space-x-4 space-y-2 lg:space-y-0 w-full">
            <label>Show
                <select class="bg-white p-1 border ml-2" name="show" id="show">
                    <option value="10">10</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </label>
        </div>

        <div class="my-4 flex flex-col lg:flex-row space-x-0 lg:space-x-4 space-y-2 lg:space-y-0 w-full">
            <label class="w-full lg:w-2/3">
                Search
                <input class="w-full" type="search" placeholder="Patient name, status, type">
            </label>
            <label class="w-full lg:w-1/3">
                Date
                <input class="w-full" type="date">
            </label>
        </div>

        <div class="overflow-x-auto">
            <table class="table-auto border-separate w-full border-spacing-x-0 border-spacing-y-2">
                <tr class="bg-zinc-100">
                    <th class="px-4 py-1 text-left">Patient Name</th>
                    <th class="px-4 py-1">Amount</th>
                    <th class="px-4 py-1">Date</th>
                    <th class="px-4 py-1">Type</th>
                    <th class="px-4 py-1">ID</th>
                    <th class="px-4 py-1">Status</th>
                    <th class="px-4 py-1">Action</th>
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
                            1
                        </td>
                        <td class="px-4 py-2 text-center">
                            <button class="rounded text-red-900 bg-red-100 px-4 py-1 text-sm ml-2">
                                Details
                            </button>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </section>
@endsection
