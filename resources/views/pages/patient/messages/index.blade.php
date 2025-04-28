@extends('layouts.patient')

@section('content')
    <x-utils.loading-component/>

    <section class="flex h-full">
        <div class="w-2/5 border-r bg-white">
            <div class="px-6 py-4 border-b">
                <h1 class="text-xl font-bold text-gray-800">Messages</h1>

                <div class="relative mt-4">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input
                        class="bg-white border border-gray-300 rounded-lg pl-10 pr-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent transition"
                        type="search"
                        id="search_field"
                        placeholder="Search user"
                    >
                </div>
            </div>

            <div id="content-list-chat" class="space-y-0 overflow-y-auto divide-y divide-gray-100" style="height: calc(100vh - 280px)"></div>
        </div>

        <div
            id="start-chat-main"
            class="w-3/5 bg-white grid rounded-r-lg"
            style="height: calc(100vh - 136px); grid-template-rows: 1fr"
        >
            <div class="flex items-center justify-center h-full flex-col text-center px-6">
                <div class="w-20 h-20 bg-violet-100 rounded-full flex items-center justify-center mb-4">
                    <x-ri-chat-3-line class="w-10 h-10 text-violet-500"/>
                </div>
                <p class="text-gray-500 font-medium">Select a conversation to start messaging</p>
                <p class="text-gray-400 text-sm mt-2">Your messages are end-to-end encrypted</p>
            </div>
        </div>

        <div
            id="chat-main"
            class="w-3/5 bg-white grid hidden rounded-r-lg"
            style="height: calc(100vh - 136px); grid-template-rows: 70px 1fr 70px"
        >
            <div class="flex justify-between items-center bg-white p-4 px-6 border-b">
                <div class="flex items-center">
                    <x-utils.image-profile-component/>
                    <p id="name-user" class="font-medium ml-3"></p>
                </div>
                <x-ri-information-line
                    id="modal-open"
                    class="w-6 h-6 cursor-pointer text-gray-500 hover:text-gray-700 transition"
                />
            </div>

            <div id="grid-main" class="overflow-y-auto px-4 py-4 bg-gray-50"></div>

            <div class="flex items-center p-4 px-6 bg-white border-t">
                <div class="flex items-center justify-between p-2 w-full bg-gray-50 rounded-l-lg border border-gray-200">
                    <div class="flex items-center">
                        {{--<x-ri-clipboard-line class="w-6 h-6 cursor-pointer"/>
                        <x-lineawesome-microphone-solid class=" w-6 h-6 cursor-pointer"/>--}}
                    </div>
                    <textarea id="editor-text"></textarea>
                </div>
                <button
                    class="bg-violet-500 hover:bg-violet-600 p-3 rounded-lg ml-2 transition-colors"
                    onclick="sendMessage()"
                >
                    <x-lineawesome-telegram class="w-5 h-5 text-white"/>
                </button>
            </div>
        </div>
    </section>

    <x-modal.modal-component
        title="Report User"
        modalClass="modal"
    >
        <x-slot name="content">
            <form action="" method="post">
                <p class="mb-4 text-gray-600">Are you sure you want to report this user?</p>
                <div class="flex items-center space-x-4 mt-6">
                    <button
                        type="submit"
                        class="rounded-lg text-white bg-red-500 hover:bg-red-600 px-4 py-2 w-full font-medium transition-colors"
                    >
                        Yes, Report
                    </button>
                    <button
                        type="button"
                        class="rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 w-full font-medium modal-close transition-colors"
                    >
                        Cancel
                    </button>
                </div>
            </form>
        </x-slot>
    </x-modal.modal-component>
@endsection

@push('scripts-bottom')
    {{-- Modal --}}
    <script>
        configModal('modal', 'modal-open')
    </script>

    {{-- Open select --}}
    <script>
        let chatId = '';
        let userId2 = '';
        let search = '';
        let contentText = '';
        let countChat = 0;
        let chatData = [];
        showLoading()
        searchData()

        // search user with ajax when after 5 seconds
        let timeout = null;
        $('#search_field').on('keyup', function () {
            clearTimeout(timeout);
            timeout = setTimeout(function () {
                search = $('#search_field').val()
                searchData();
            }, 500);
        })

        // search data
        function searchData() {
            const baseUrl = '{{ route('patient.search.chat') }}';
            const url = `${baseUrl}?search=${search}`
            const token = $('meta[name="csrf-token"]').attr('content')

            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: token
                },
                success: function (response) {
                    let html = ''
                    $('#content-list-chat').empty()

                    chatData = response.data;

                    if (chatData.length > 0) {
                        chatData.forEach((item, idx) => {
                            html += `
                                <div
                                    class="hover:bg-gray-50 transition px-6 py-3 cursor-pointer"
                                    onclick="openChat(chatData[${idx}])"
                                >
                                    <div class="flex justify-between items-start">
                                        <div class="flex items-start">
                                            <x-utils.image-profile-component/>
                                            <div class="ml-3">
                                                <p class="font-medium text-gray-800">${item.name}</p>
                                                <p class="text-gray-500 text-sm mt-1 line-clamp-1">
                                                    ${item.lastMessage.message}
                                                </p>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="flex items-center text-xs text-gray-400 mt-1">
                                                <x-lineawesome-clock class="w-3 h-3"/>
                                                <span class="ml-1">${item.lastMessage.created_at_text}</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>`
                        })
                    } else {
                        html = `<div class="py-8 text-center">
                                    <p class="text-gray-500">No conversations found</p>
                                </div>`
                    }

                    $('#content-list-chat').html(html)
                    hideLoading()
                },
                error: function () {
                    hideLoading()
                }
            })
        }

        function openChat(user) {
            $('#name-user').html(user.name)
            chatId = user.lastMessage.id
            userId2 = user.id
            loadChat()
        }

        /* load chat */
        function loadChat() {
            const url = '{{ route('patient.chats.list') }}';

            $.ajax({
                url: `${url}?chat=${chatId}`,
                type: 'GET',
                success: function (response) {
                    const grid = $('#grid-main');
                    grid.empty()

                    const chatMain = $('#chat-main');
                    chatMain.removeClass('hidden')

                    const startChat = $('#start-chat-main');
                    startChat.addClass('hidden')

                    if (response && response.messages.length) {
                        let html = ''
                        countChat = response.messages.length - 1

                        response.messages.forEach((item, index) => {
                            if (item.right) {
                                html += rightMessage(item, index)
                            } else {
                                html += leftMessage(item, index)
                            }
                        })

                        grid.html(html)

                        response.messages.forEach((item, index) => {
                            openCloseHeaderBtn(`content-message-${index + 1}`, `btn-message-${index + 1}`)
                        })

                        grid.scrollTop(grid[0].scrollHeight);
                    } else {
                        grid.html(`
                            <div class="flex items-center justify-center h-full flex-col">
                                <div class="w-16 h-16 bg-violet-100 rounded-full flex items-center justify-center mb-3">
                                    <x-ri-chat-3-line class="w-8 h-8 text-violet-500"/>
                                </div>
                                <p class="text-gray-500">Start a new conversation</p>
                            </div>
                        `)
                    }
                },
                error: function (error) {
                    errorSwal(error, 'No se pudo cargar el chat')
                }
            })
        }

        function leftMessage(item, index) {
            return `
                <div class="w-3/4 mb-5">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mr-3">
                            <x-utils.image-profile-component/>
                        </div>
                        <div>
                            <div class="bg-white px-4 py-3 rounded-lg shadow-sm border border-gray-100">
                                ${item.message}
                            </div>
                            <p class="text-xs mt-1 text-gray-400 ml-1">
                                ${item.diffForHumans}
                            </p>
                        </div>
                    </div>
                </div>`
        }

        function rightMessage(item, index) {
            return `
                <div class="flex justify-end mb-5">
                    <div class="flex items-start justify-end text-right w-3/4">
                        <div>
                            <div class="bg-violet-500 text-white px-4 py-3 rounded-lg shadow-sm">
                                ${item.message}
                            </div>
                            <p class="text-xs mt-1 text-gray-400 text-right mr-1">
                                ${item.diffForHumans}
                            </p>
                        </div>
                    </div>
                </div>`
        }

        function sendMessage() {
            const url = '{{ route('patient.send.message') }}';

            $.ajax({
                url: `${url}`,
                type: 'POST',
                data: {
                    chat_id: chatId,
                    user_id2: userId2,
                    message: contentText,
                    '_token': '{{ csrf_token() }}'
                },
                success: function () {
                    $("#editor-text").emojioneArea().val('').trigger('change')
                    addMessage(contentText, true)
                },
                error: function (error) {
                    errorSwal(error, 'No se pudo enviar el mensaje')
                }
            })
        }

        function addMessage(message, right) {
            countChat++
            let html
            if (right) {
                html = rightMessage({
                    message: message,
                    diffForHumans: '1 second ago'
                }, countChat)
            } else {
                html = leftMessage({
                    message: message,
                    diffForHumans: '1 second ago'
                }, countChat)
            }
            const grid = $('#grid-main')
            grid.append(html)
            grid.scrollTop(grid[0].scrollHeight);
        }
    </script>

    <script type="module">
        const channelName = '{{ App\Utils\Constants::$CHAT_CHANNEL }}.{{ auth()->user()->id }}';
        window.Echo.private(channelName)
            .listen('ChatEvent', (e) => {
                addMessage(e.message.message, false);
            })
            .error(function (e) {
                console.log(e);
            });
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
            var emoji = $("#editor-text").emojioneArea({
                inline: true,
                placeholder: "Type a message...",
                buttonTitle: "Insert emoji",
            });

            emoji[0].emojioneArea.on("keydown", function (editor, event) {
                contentText = this.getText();
                if (event.which === 13) {
                    event.preventDefault();
                    sendMessage()
                }
            });
        });
    </script>

    <style>
        .emojionearea .emojionearea-editor {
            min-height: 40px;
            height: 100%;
            overflow-y: auto;
            padding: 8px 12px;
            font-size: 14px;
            border: none;
            background-color: transparent;
        }

        .emojionearea {
            border: none !important;
            box-shadow: none !important;
            background-color: transparent !important;
        }

        .emojionearea .emojionearea-button {
            right: 12px;
            top: 8px;
            opacity: 0.7;
        }

        .emojionearea .emojionearea-button:hover {
            opacity: 1;
        }

        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Ensure proper heights with the layout */
        @media (min-height: 768px) {
            #content-list-chat {
                height: calc(100vh - 280px);
            }

            #start-chat-main, #chat-main {
                height: calc(100vh - 136px);
            }
        }
    </style>
@endpush
