@extends('layouts.home')

@section('content')
    <x-utils.loading-component/>

    <section class="flex items-start">
        <div class="w-2/5">
            <div class="px-4 py-2">
                <h1 class="text-lg font-bold">Message</h1>

                <label>
                    <input
                        class="bg-transparent border w-full my-6 outline-0"
                        type="search"
                        id="search_field"
                        placeholder="Search user"
                    >
                </label>
            </div>

            <div id="content-list-chat" class="space-y-2 overflow-y-auto" style="height: calc(100vh - 262px)"></div>
        </div>

        <div
            id="start-chat-main"
            class="w-3/5 bg-white border grid"
            style="height: calc(100vh - 136px); grid-template-rows: 1fr"
        >
            <div class="flex items-center justify-center h-full flex-col">
                <x-ri-chat-3-line class="w-12 h-12"/>
                <p class="text-gray-400">Select chat to start messaging</p>
            </div>
        </div>

        <div
            id="chat-main"
            class="w-3/5 bg-white border grid hidden"
            style="height: calc(100vh - 136px); grid-template-rows: 70px 1fr 70px"
        >
            <div class="flex justify-between items-center bg-white p-4">
                <div class="flex items-center">
                    <x-utils.image-profile-component/>
                    <p id="name-user"></p>
                </div>
                <x-ri-information-line
                    id="modal-open"
                    class="w-6 h-6 cursor-pointer"
                />
            </div>

            <div id="grid-main" class="overflow-y-auto"></div>

            <div class="flex items-center p-4 space-x-2">
                <div class="flex items-center justify-between p-2 w-full">
                    <div class="flex items-center">
                        <x-ri-clipboard-line class="w-6 h-6 cursor-pointer"/>
                        <x-lineawesome-microphone-solid class=" w-6 h-6 cursor-pointer"/>
                    </div>
                    <textarea id="editor-text"></textarea>
                </div>
                <div
                    id="btn-send-message"
                    class="bg-violet-500 p-1" style="border-radius: 50%"
                    onclick="sendMessage()"
                >
                    <x-lineawesome-telegram class="w-6 h-6 cursor-pointer text-white"/>
                </div>
            </div>
        </div>
    </section>

    <x-modal.modal-component
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
            const baseUrl = '{{ route('doctor.search.chat') }}';
            const url = `${baseUrl}?search=${search}`
            let token = $('meta[name="csrf-token"]').attr('content')

            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: token
                },
                success: function (response) {
                    let html = ''

                    const data = response.data;
                    if (data.length > 0) {
                        data.forEach(item => {
                            html += setContentUser(item)
                        })
                    } else {
                        html = `<p class="text-center">No se encontraron resultados</p>`
                    }

                    $('#content-list-chat').html(html)
                    hideLoading()
                },
                error: function () {
                    hideLoading()
                }
            })
        }

        function setContentUser(item) {
            return `
                    <div
                        class="flex justify-between items-start bg-white px-2 py-2 cursor-pointer"
                        onclick='openChat(${JSON.stringify(item)})'
                    >
                        <div class="flex items-center">
                            <x-utils.image-profile-component/>
                            <div>
                                <p>${item.name}</p>
                                <p class="text-xs mt-1">
                                    ${item.lastMessage.message}
                                </p>
                            </div>
                        </div>
                        <div>
                            <span class="flex items-center text-xs">
                                <x-lineawesome-clock class="w-4 h-4"/>
                                <span class="ml-1">${item.lastMessage.created_at_text}</span>
                                </span>
                            </div>
                        </div>`
        }

        /* request with ajax chat according user id*/
        function openChat(user) {
            $('#name-user').html(user.name)
            userId2 = user.id
            chatId = user.lastMessage.id
            loadChat()
        }

        /* load chat */
        function loadChat() {
            const url = '{{ route('doctor.chats.list') }}';

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
                        countChat = response.messages.length - 1
                        let html = ''

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
                                <x-ri-chat-3-line class="w-12 h-12"/>
                                <p class="text-gray-400">Start chat</p>
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
                <div class="w-3/4">
                    <div class="flex items-start bg-white px-2 py-2">
                        <div>
                            <x-utils.image-profile-component/>
                            <p class="border py-2 px-4 rounded">
                                ${item.message}
                            </p>
                            <p class="text-xs mt-1 text-gray-400">
                                ${item.diffForHumans}
                            </p>
                        </div>
                        <div class="relative">
                            <x-lineawesome-comment-dots
                                id="btn-message-${index + 1}"
                                class="w-6 h-6 mt-2 ml-2 cursor-pointer"
                            />
                            <div
                                id="content-message-${index + 1}"
                                class="bg-white z-10 absolute border rounded-lg hidden py-2 px-4 right-0"
                            >
                                <ul class="space-y-2 btn-link">
                                    <li><a href="/#" class="whitespace-nowrap">Reply</a></li>
                                    <li><a href="/#" class="whitespace-nowrap">Delete</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>`
        }

        function rightMessage(item, index) {
            return `
                <div class="flex justify-end">
                    <div class="flex items-start justify-end bg-white px-2 py-2 text-right w-3/4">
                        <div class="relative">
                            <x-lineawesome-comment-dots
                                id="btn-message-${index + 1}"
                                class="w-6 h-6 mt-2 mr-2 cursor-pointer"
                            />
                            <div
                                id="content-message-${index + 1}"
                                class="bg-white z-10 absolute border rounded-lg hidden py-2 px-4 right-0"
                            >
                                <ul class="space-y-2 btn-link">
                                    <li><a href="/#" class="whitespace-nowrap">Reply</a></li>
                                    <li><a href="/#" class="whitespace-nowrap">Delete</a></li>
                                </ul>
                            </div>
                        </div>
                        <div>
                            <p class="bg-blue-500 text-white py-2 px-4 rounded">
                                ${item.message}
                            </p>
                            <p class="text-xs mt-1 text-gray-400">
                                ${item.diffForHumans}
                            </p>
                        </div>
                    </div>
                </div>`
        }

        function sendMessage() {
            const url = '{{ route('doctor.send.message') }}';

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
                    countChat++
                    rightMessage({
                        message: contentText,
                        diffForHumans: '1 second ago'
                    }, countChat)
                },
                error: function (error) {
                    errorSwal(error, 'No se pudo enviar el mensaje')
                }
            })
        }
    </script>

    <script type="module">
        const userId = '{{ auth()->user()->id }}';
        const channel = `ChatChannel.${userId}`;
        window.Echo.private(channel)
            .listen('ChatEvent', (e) => {
                countChat++
                leftMessage(e.message, countChat)
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
            min-height: 32px;
            height: 100%;
            overflow-y: auto;
        }
    </style>
@endpush
