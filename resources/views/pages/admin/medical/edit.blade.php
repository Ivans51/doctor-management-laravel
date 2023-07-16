@php use App\Utils\Constants; @endphp
@extends('layouts.admin')

@section('content')
    <x-utils.message-component/>

    <section class="mt-10">
        <form action="{{ route('medical.update', $medical->id) }}" method="post">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div class="grid grid-cols-1 gap-x-4">
                    <div>
                        <label for="name">Name</label>
                        <input
                            class="border w-full"
                            type="text"
                            name="name"
                            id="name"
                            value="{{ $medical->name }}"
                        >
                    </div>
                </div>

                <div class="w-full">
                    <label for="description">Description</label>
                    <textarea
                        class="border w-full p-2"
                        name="description"
                        id="description"
                        cols="30"
                        rows="5"
                    >{{ $medical->description }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-x-4">
                    <div>
                        <label for="price">Price</label>
                        <input
                            class="border w-full"
                            type="text"
                            name="price"
                            id="price"
                            value="{{ $medical->price }}"
                        >
                    </div>

                    <div>
                        <label for="currency">Currency</label>
                        <input
                            class="border w-full"
                            type="tel"
                            name="currency"
                            id="currency"
                            readonly
                            value="{{ $medical->currency }}"
                        >
                    </div>
                </div>

                <div class="w-full">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="border w-full">
                        <option value="">Selection</option>
                        <option
                            value="{{ Constants::$ACTIVE }}"
                            {{ $medical->status == Constants::$ACTIVE ? 'selected' : '' }}
                        >
                            Active
                        </option>
                        <option
                            value="{{ Constants::$INACTIVE }}"
                            {{ $medical->status == Constants::$INACTIVE ? 'selected' : '' }}
                        >
                            Inactive
                        </option>
                    </select>
                </div>
            </div>

            <div class="flex items-center space-x-2 mt-10">
                <button
                    type="submit"
                    class="rounded text-white bg-blue-500 px-4 py-1 w-full"
                >
                    Save
                </button>
                <a
                    href="{{ url()->previous() }}"
                    class="rounded bg-white-500 px-4 py-1 w-full border modal-close text-center"
                >
                    Cancel
                </a>
            </div>
        </form>
    </section>
@endsection
