@extends('home')

@section('content')
    <section class="flex items-start">
        <div class="w-2/5">
            <div class="px-4 py-2">
                <h1 class="text-lg font-bold">Message</h1>

                <label>
                    <input
                        class="bg-transparent border w-full my-6 outline-0"
                        type="search"
                        placeholder="Search for message"
                    >
                </label>
            </div>

            <div class="space-y-2 overflow-y-auto" style="height: calc(100vh - 262px)">
                @foreach($images as $image)
                    <div class="flex justify-between items-start bg-white px-2 py-2 cursor-pointer">
                        <div class="flex items-center">
                            <img class="h-10 mr-3" src="{{$image}}" alt="image animal" style="border-radius: 50%">
                            <div>
                                <p>Jenny Wilson</p>
                                <p class="text-xs mt-1">Message</p>
                            </div>
                        </div>
                        <div>
                            <span class="text-xs">04/04/23</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div
            class="w-3/5 bg-white border grid"
            style="height: calc(100vh - 136px); grid-template-rows: 70px 1fr 70px"
        >
            <div class="flex justify-between items-center bg-white p-4">
                <div class="flex items-center">
                    <img class="h-10 mr-3" src="{{$image}}" alt="image animal" style="border-radius: 50%">
                    <p>Jenny Wilson</p>
                </div>
                <x-ri-information-line
                    id="modal-open"
                    class="w-6 h-6 cursor-pointer"
                />
            </div>

            <div id="grid-main" class="overflow-y-auto">
                @foreach($images as $index => $image)
                    @if($index % 2 == 0)
                        <div class="flex items-start bg-white px-2 py-2">
                            <img class="h-10 mr-3" src="{{$image}}" alt="image animal" style="border-radius: 50%">
                            <div>
                                <p class="border py-2 px-4 rounded">Jenny Wilson</p>
                                <p class="text-xs mt-1 text-gray-400">Time</p>
                            </div>
                            <div class="relative">
                                <x-lineawesome-comment-dots
                                    id="btn-message-{{ $index + 1 }}"
                                    class="w-6 h-6 mt-2 ml-2 cursor-pointer"
                                />
                                <div
                                    id="content-message-{{ $index + 1 }}"
                                    class="bg-white z-10 absolute border rounded-lg hidden py-2 px-4 right-0"
                                >
                                    <ul class="space-y-2 btn-link">
                                        <li><a href="/#" class="whitespace-nowrap">Reply</a></li>
                                        <li><a href="/#" class="whitespace-nowrap">Delete</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($index % 2 != 0)
                        <div class="flex items-start bg-white px-2 py-2 justify-end text-right">
                            <div class="relative">
                                <x-lineawesome-comment-dots
                                    id="btn-message-{{ $index + 1 }}"
                                    class="w-6 h-6 mt-2 mr-2 cursor-pointer"
                                />
                                <div
                                    id="content-message-{{ $index + 1 }}"
                                    class="bg-white z-10 absolute border rounded-lg hidden py-2 px-4 right-0"
                                >
                                    <ul class="space-y-2 btn-link">
                                        <li><a href="/#" class="whitespace-nowrap">Reply</a></li>
                                        <li><a href="/#" class="whitespace-nowrap">Delete</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div>
                                <p class="bg-blue-500 text-white py-2 px-4 rounded">Jenny Wilson</p>
                                <p class="text-xs mt-1 text-gray-400">Time</p>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="flex items-center p-4 space-x-2">
                <div class="flex items-center justify-between p-2 w-full">
                    <div class="flex items-center">
                        <x-ri-clipboard-line class="w-6 h-6 cursor-pointer"/>
                        <x-lineawesome-microphone-solid class=" w-6 h-6 cursor-pointer"/>
                    </div>
                    <textarea id="example1"></textarea>
                </div>
                <div class="bg-violet-500 p-1" style="border-radius: 50%">
                    <x-lineawesome-telegram class="w-6 h-6 cursor-pointer text-white"/>
                </div>
            </div>
        </div>
    </section>

    <x-modal.create-patient-component
        title="¿Desea reportar al usuario?"
        modalClass="modal"
    >
        <x-slot name="content">
            <form action="" method="post">
                <div class="flex items-center space-x-8 my-6">
                    <button
                        type="submit"
                        class="rounded text-white bg-blue-500 px-4 py-1 w-full"
                    >
                        Yes
                    </button>
                    <button
                        type="button"
                        class="rounded bg-white-500 px-4 py-1 w-full border modal-close"
                    >
                        No
                    </button>
                </div>
            </form>
        </x-slot>
    </x-modal.create-patient-component>
@endsection

@push('scripts-bottom')
    {{-- Modal --}}
    <script>
        configModal('modal', 'modal-open')
    </script>

    {{-- Open select --}}
    <script>
        const data = @json($images);
        data.forEach((item, index) => {
            openCloseHeaderBtn(`content-message-${index + 1}`, `btn-message-${index + 1}`)
        })
    </script>

    {{-- Scroll --}}
    <script>
        const grid = $('#grid-main');
        grid.scrollTop(grid[0].scrollHeight);
    </script>

    {{-- Emoji --}}
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.2/emojionearea.min.css"
        integrity="sha512-vEia6TQGr3FqC6h55/NdU3QSM5XR6HSl5fW71QTKrgeER98LIMGwymBVM867C1XHIkYD9nMTfWK2A0xcodKHNA=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"
    />
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.2/emojionearea.min.js"
        integrity="sha512-hkvXFLlESjeYENO4CNi69z3A1puvONQV5Uh+G4TUDayZxSLyic5Kba9hhuiNLbHqdnKNMk2PxXKm0v7KDnWkYA=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"
    >
    </script>

    <script type="text/javascript">
        $(document).ready(function () {
            $("#example1").emojioneArea({
                inline: true
            });
        });
    </script>

    <style>
        .emojionearea .emojionearea-editor {
            min-height: 32px;
            height: 100%;
            overflow-y: auto;
        }
    </style>
@endpush
