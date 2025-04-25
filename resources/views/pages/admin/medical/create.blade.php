@extends('layouts.admin')

@section('content')
    <section class="max-w-3xl mx-auto mt-10 px-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Add Medical Specialty</h1>
            <a href="{{ route('admin.medical.index') }}" class="flex items-center text-gray-600 hover:text-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                        clip-rule="evenodd" />
                </svg>
                Back
            </a>
        </div>

        <x-utils.message-component />

        <div class="bg-white p-6 rounded-lg shadow-md">
            <form action="{{ route('admin.medical.store') }}" method="post">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="w-full md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            type="text" name="name" id="name" required>
                    </div>
                    <div class="w-full md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            name="description" id="description" rows="4"></textarea>
                    </div>
                    <div class="w-full">
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                        <input
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            type="text" name="price" id="price" required>
                    </div>
                    <div class="w-full">
                        <label for="currency" class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
                        <input
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 bg-gray-100"
                            type="tel" name="currency" id="currency" readonly value="USD">
                    </div>
                </div>

                <div class="mt-8">
                    <button type="submit"
                        class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md shadow-sm transition duration-150 ease-in-out">
                        Save Medical Specialty
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection
