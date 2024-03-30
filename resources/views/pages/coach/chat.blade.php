@extends('pages.coach.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Chats'])
    <div class="container-fluid py-0">
        <div class="row mt-0">
            <div id="all-chat-container-id" class="card col-lg-4 mb-lg-0 mb-4 h75"
                style="background:#d3aaa1;z-index:100;border-radius:15px 0px 0 15px;padding:0px">
                <div id="all-chat-title" class="card-header bg-white "
                    style="border-radius:15px 0px 0 15px;font-weight:bolder">
                    All Chats
                </div>
                <div class="scrollable-container">
                    <input type="hidden" id="opened-chat">
                    <input type="hidden" id="opened-chat-name">
                    <div class="scrollable-content">

                        @foreach ($chats as $chat)
                            <div class="container my-1 p-2 chat" style="font-size:13px;cursor:pointer;position:relative"
                                data-id="{{ $chat->id }}" data-fullname="{{ $chat->full_name }}"
                                data-channel-name="{{ $chat->channel_name }}">
                                <div class="new-message-alert" id="ma-{{ $chat->id }}">

                                </div>
                                <div class="card py-2 px-3">
                                    <div class="row">
                                        <div class="col-sm" style="width:fit-content;max-width:fit-content">
                                            <img class="chat-icon" style="padding:0;margin:0"
                                                src="{{ $chat->avatar ?? asset('img/team-2.jpg') }}">
                                        </div>
                                        <div class="col-md">
                                            <div style="font-weight:bolder">{{ $chat->full_name }}</div>
                                            <div id="chat-message-{{ $chat->id }}" class="text-truncate"
                                                style="width:150px">

                                                {{ ($chat->my_message ? 'You : ' : '') . $chat->last_msg }}</div>
                                        </div>
                                    </div>
                                    <div class="col-sm" style="text-align:end;font-size:11px"
                                        id="chat-timestamp-{{ $chat->id }}"
                                        data-timestamp_int="{{ $chat->timestamp_int }}">{{ $chat->timestamp }}</div>

                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div id="chat-container-id" class="card col-lg-8 mb-lg-0 mb-4 p-0 h75"
                style="background:#ffc1b3;border-radius:0px 15px 15px 0px;">
                <div class="card-header bg-white " id="chat-right-title"
                    style="border-radius:0px 15px 15px 0px;font-weight:bolder;height:74px">
                    <div style="display:flex;align-items:center">
                        <i id="chat-back-arrow" class='bx bxs-chevron-left mr-3' style="font-size:32px"></i>
                        <div id="selected-chat-toolbar"></div>
                    </div>

                    <div id="user-typing-msg" style="color:#8c7bff;font-size:13px"></div>
                </div>
                <div id="chat-dialog" class="scrollable-container"></div>
                <div id="chat-message-container" class="messaging-input-container mt-1">
                    <input type="text" id="message-to-send" placeholder="Type a message..." class="messaging-input">
                    <button class="send-button" id="send-message">Send</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        // Flag to track typing status
        let isTyping = false;
        // Timer for tracking typing activity
        let typingTimer;

        // Duration of inactivity before considering typing stopped (in milliseconds)
        const typingInterval = 1000;

        // Function to be called when typing starts
        function startTyping() {
            isTyping = true;
            console.log('User started typing...');
            // Add your logic here
            let channelName = $("#opened-chat-name").val();
            const privateChannel = Echo.private(channelName);
            privateChannel.whisper('user-typing-status', {
                user_id: "{{ Auth::id() }}",
                chat: $("#opened-chat").val(),
                fullname: "{{ Auth::user()->fullname }}",
                status: "typing"
            });
        }

        // Function to be called when typing stops
        function stopTyping() {
            isTyping = false;
            console.log('User stopped typing...');
            // Add your logic here
            let channelName = $("#opened-chat-name").val();
            const privateChannel = Echo.private(channelName);
            privateChannel.whisper('user-typing-status', {
                user_id: "{{ Auth::id() }}",
                fullname: "{{ Auth::user()->fullname }}",
                chat: $("#opened-chat").val(),
                status: "stoped"
            });
        }

        $(document).ready(function() {
            console.log(window.isMobile())
            if (window.isMobile()) {
                $("#chat-container-id").css('display', 'none');
            } else {
                $("#chat-back-arrow").css('display', 'none')
            }
            let channels = {!! $channels !!}
            channels.forEach((item) => {
                for (const channel in item) {
                    const name = item[channel];
                    switch (channel) {
                        case 'private':
                            let privateChannel = window.Echo.private(name);
                            privateChannel.listen('.NewChat', (e) => {
                                let newChannelName = e.newChannelName;
                                setTimeout(() => {
                                    window.Echo.private(newChannelName)
                                        .listen('.NewChat', newChatListener);
                                }, 1000);
                            });
                            privateChannel.listen('.NewMessage', newMessageListener);
                            privateChannel.listenForWhisper('user-typing-status', userTypingListener);
                            break;
                    }
                }
            })
            $('#chat-back-arrow').on('click', function() {
                $("#all-chat-container-id").css('display', 'block');
                $("#chat-container-id").css('display', 'none');
            })
            // Event listener for the input event using jQuery
            $('#message-to-send').on('input', function() {
                if (!isTyping) {
                    startTyping();
                }

                // Reset the typing timeout
                clearTimeout(typingTimer);
                typingTimer = setTimeout(stopTyping, typingInterval);
            });
            $(".chat").on('click', function() {
                let channelId = $(this).attr('data-id');
                let channelName = $(this).attr('data-channel-name');
                if (channelId == $("#opened-chat").val()) return;
                if (window.isMobile()) {
                    $("#chat-container-id").css('display', 'flex');
                    $("#all-chat-container-id").css('display', 'none');

                }
                //$("#opened-chat").val(channelId);
                $("#opened-chat").val(channelId);
                $("#opened-chat-name").val(channelName);
                $("#chat-message-container").css('display', 'flex')
                // load all message from this channel.
                $("#selected-chat-toolbar").text($(this).attr('data-fullname'))
                loadChat();
                $('#message-to-send').focus();

            })
            $("#send-message").on('click', function() {
                let msg = $("#message-to-send").val();
                sendMessage(msg)
            });
            $('#message-to-send').keydown(function(event) {
                if (event.keyCode === 13) {
                    let msg = $("#message-to-send").val();
                    sendMessage(msg)
                }
            });
        });

        async function sendMessage(msg) {
            let chat = $("#opened-chat").val();
            //let channelName = $("#opened-chat-name").val();
            axios.post("{{ route('chat.message.new') }}", {
                channel_id: chat,
                message: msg
            })

        }

        function newChatListener(e) {}

        function userTypingListener(e) {
            if (e.chat == $("#opened-chat").val()) {
                if (e.status == 'typing')
                    $("#user-typing-msg").text(`${e.fullname    } is typing`)
                else if (e.status == 'stoped') {
                    console.log('stoped');
                    $("#user-typing-msg").text(``)
                }
            }
        }

        function newMessageListener(e) {
            let myId = {!! Auth::id() !!}
            let channel_id = e.channel_id;
            let items = $(".scrollable-content .chat").map(function() {
                return $(this).clone(true);
            }).toArray();
            items.sort(function(a, b) {
                let firstId = $(a).attr("data-id");
                let secondId = $(b).attr("data-id");

                let first_time = $(`#chat-timestamp-${firstId}`).attr('data-timestamp_int');
                let second_time = $(`#chat-timestamp-${secondId}`).attr('data-timestamp_int');
                first_time = parseInt(first_time);
                second_time = parseInt(second_time);
                return second_time - first_time;
            });
            $('.scrollable-content').empty();
            $(items).each(function() {
                $('.scrollable-content').append(this);
            });
            $(`#chat-message-${channel_id}`).text(
                (e.sender.id == myId ? 'You : ' : '') +
                e.message
            )
            $(`#chat-timestamp-${channel_id}`).text(e.created_at_diff)
            if (channel_id != $("#opened-chat").val()) {
                $(`#ma-${channel_id}`).css('display', 'block');
                try {
                    const audio = new Audio("{{ asset('assets/notify.wav') }}");
                    audio.play();
                } catch (e) {
                    console.log(e)
                }
            } else {
                let message = {
                    is_me: e.sender.id == myId,
                    message_id: e.message_id,
                    full_name: e.sender.fullname,
                    avatar: e.sender.avatar,
                    message: e.message,
                    created_at: e.created_at,
                    current_user_id: e.sender.id,
                };
                //console.log(message)
                let html = generateNewMessageTemplate(message)
                $("#chat-dialog").append(html);
                $('#chat-dialog').animate({
                    scrollTop: $('#chat-dialog')[0].scrollHeight
                }, 'slow');
                $("#message-to-send").val("");
            }
        }

        function loadChat() {
            let channelId = $("#opened-chat").val();

            $('#chat-dialog').animate({
                scrollTop: 0
            }, 'fast');
            $(`#ma-${channelId}`).css('display', 'none');
            axios({
                method: "POST",
                url: "{{ route('chat.load') }}",
                data: {
                    channel: channelId,
                }
            }).then((response) => {
                if (response.data.code == 200) {
                    let chat = response.data.chat;
                    $("#chat-dialog").empty();
                    let html = generateChatMessages(chat);
                    $("#chat-dialog").html(html);
                    $('#chat-dialog').animate({
                        scrollTop: $('#chat-dialog')[0].scrollHeight
                    }, 'slow');
                }
            }).catch((err) => console.log(err))
        }

        function generateChatMessages(chat) {
            let html = ``;
            chat.forEach((message) => {
                html += generateMessageTemplate(message);
            })
            return html;
        }

        function generateMessageTemplate(message) {
            let holder_img = "{{ asset('img/team-1.jpg') }}"
            let themeMode = localStorage.getItem('theme-mode');
            let newClass = '';
            if (themeMode === 'dark')
                newClass = 'dark-version';
            if (message.status == 'received') {
                // change this for this user to read.
                axios({
                    method: "POST",
                    url: "{{ route('chat.message.read') }}",
                    data: {
                        message_status_id: `${message.status_id}`
                    }
                }).then((response) => {
                    if (response.data.code == 200) {
                        $(`#check-${message.message_id}`).empty();
                        $(`#check-${message.message_id}`).html("<i class='bx bx-check-double'></i>");
                    }
                })
            }
            if (message.is_me) {
                return `
                        <div class="chat-container darker ${newClass} m-3">
                            <img src="${message.avatar ?? holder_img}" alt="Avatar" class="right">
                            <p>${message.full_name}</p>
                            <p>${message.message}</p>
                            <span class="time-right">${message.created_at}</span>
                            <span class="time-left" >
                            ${message.status == 'received' ? "<i class='bx bx-check'></i>" : "<i class='bx bx-check-double'></i>"}
                            </span>
                        </div>`;
            } else {
                return `
                    <div class="chat-container ${newClass} m-3">
                        <img src="${message.avatar ?? holder_img}" alt="Avatar">         
                        <p>${message.full_name}</p>
                        <p>${message.message}</p>
                        <span class="time-right">${message.created_at}</span>
                         <span class="time-left" id='check-${message.message_id}'>
                            ${message.status == 'received' ? "<i class='bx bx-check'></i>" : "<i class='bx bx-check-double'></i>"}
                            </span>
                    </div>
                    `;
            }

        }

        function generateNewMessageTemplate(message) {
            let holder_img = "{{ asset('img/team-1.jpg') }}"
            let themeMode = localStorage.getItem('theme-mode');
            let newClass = '';
            if (themeMode === 'dark')
                newClass = 'dark-version';
            // change this for this user to read.
            if (!message.is_me) {
                axios({
                    method: "POST",
                    url: "{{ route('chat.message.new.read') }}",
                    data: {
                        message_id: `${message.message_id}`,
                        user_id: `${message.current_user_id}`,
                    }
                }).then((response) => {
                    console.log("generateNewMessageTemplate");
                    console.log(response.data);
                    if (response.data.code == 200) {
                        setTimeout(() => {
                            //console.log($(`#check-${message.message_id}`).html())
                            $(`#check-${message.message_id}`).empty();
                            $(`#check-${message.message_id}`).html("<i class='bx bx-check-double'></i>");
                            console.log($(`#check-${message.message_id}`).html())
                            console.log($(`#check-${message.message_id}`))
                        }, 500);
                    }
                }).catch((err) => console.log(err))
            }

            if (message.is_me) {
                return `
                        <div class="chat-container darker ${newClass} m-3">
                            <img src="${message.avatar ?? holder_img}" alt="Avatar" class="right">
                            <p>${message.full_name}</p>
                            <p>${message.message}</p>
                            <span class="time-right">${message.created_at}</span>
                            <span class="time-left" >
                                <i class='bx bx-check-double'></i>
                            </span>
                        </div>`;
            } else {
                return `
                    <div class="chat-container ${newClass} m-3">
                        <img src="${message.avatar ?? holder_img}" alt="Avatar">         
                        <p>${message.full_name}</p>
                        <p>${message.message}</p>
                        <span class="time-right">${message.created_at}</span>
                        <span class="time-left" id='check-${message.message_id}'>
                            <i class='bx bx-check'></i>
                        </span>
                    </div>
                    `;
            }

        }
    </script>
@endpush
