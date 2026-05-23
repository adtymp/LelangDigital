@extends('layouts.body', ['title' => 'Dashboard'])

@section('content')

<div class="flex bg-white rounded-xl border border-gray-200 overflow-hidden h-[calc(100vh-6rem)]">

    {{-- ========================= --}}
    {{-- SIDEBAR USER (CHAT LIST) --}}
    {{-- ========================= --}}
    <div
        class="w-full lg:w-64 border-r border-gray-200 flex flex-col bg-white"
        :class="openChat ? 'hidden lg:flex' : 'flex'">
        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
            <p class="text-sm font-semibold text-gray-700">Daftar Chat</p>
        </div>

        <div class="overflow-y-auto flex-1">
            @foreach($users as $u)
            @php $unread = $unreadCounts[$u->id] ?? 0; @endphp

            <a href="{{ route('chat.index', ['user' => $u->id]) }}"
                @click="openChat = true"
                class="flex items-center justify-between px-4 py-3 border-b border-gray-200 hover:bg-gray-50 transition
                            {{ request('user') == $u->id ? 'bg-blue-50 border-l-4  border-blue-500' : '' }}">

                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-sm font-semibold">
                        {{ strtoupper(substr($u->name, 0, 1)) }}
                    </div>

                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $u->name }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ $u->email }}</p>
                    </div>
                </div>

                @if($unread > 0)
                <span class="bg-blue-500 text-white text-[10px] rounded-full w-5 h-5 flex items-center justify-center">
                    {{ $unread > 9 ? '9+' : $unread }}
                </span>
                @endif

            </a>
            @endforeach
        </div>
    </div>

    {{-- ========================= --}}
    {{-- AREA CHAT --}}
    {{-- ========================= --}}
    <div
        class="flex-1 flex flex-col bg-white"
        :class="!openChat ? 'hidden lg:flex' : 'flex'">

        @if($selectedUser)

        {{-- HEADER --}}
        <div class="px-4 py-3 border-b border-gray-200 flex items-center gap-3">

            {{-- BACK BUTTON MOBILE --}}
            <button @click="openChat = false" class="lg:hidden text-gray-500">
                ←
            </button>

            <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-sm font-semibold">
                {{ strtoupper(substr($selectedUser->name, 0, 1)) }}
            </div>

            <div>
                <p class="text-sm font-semibold text-gray-800">{{ $selectedUser->name }}</p>
                <p class="text-xs text-gray-400">{{ $selectedUser->email }}</p>
            </div>
        </div>

        {{-- CHAT BOX --}}
        <div id="chat-box" class="flex-1 overflow-y-auto p-3 space-y-3 bg-gray-50">

            @foreach($messages as $msg)

            @if($msg->pengirim_id == auth()->id())
            {{-- PESAN KELUAR --}}
            <div class="flex justify-end">
                <div class="px-4 py-2 rounded-2xl rounded-tr-sm bg-blue-500 text-white text-sm max-w-[75%] lg:max-w-md">

                    {{ $msg->teks }}

                    {{-- STATUS --}}
                    <div class="flex items-center gap-1 justify-end mt-1 text-[10px]">

                        <span>{{ $msg->created_at->format('H:i') }}</span>

                        @if($msg->status === 'sent')
                        ✔
                        @elseif($msg->status === 'delivered')
                        ✔✔
                        @elseif($msg->status === 'read')
                        <span class="text-blue-300">✔✔</span>
                        @endif

                    </div>

                </div>
            </div>

            @else
            {{-- PESAN MASUK --}}
            <div class="flex justify-start">
                <div class="px-4 py-2 rounded-2xl rounded-tl-sm bg-white border border-gray-200 text-sm max-w-[75%] lg:max-w-md">

                    {{ $msg->teks }}

                    <div class="text-[10px] text-gray-400 mt-1 text-right">
                        {{ $msg->created_at->format('H:i') }}
                    </div>

                </div>
            </div>
            @endif

            @endforeach
        </div>

        {{-- INPUT --}}
        <div class="px-3 py-2 border-t border-gray-200 flex gap-2 items-center">
            <input
                type="text"
                id="chat-input"
                placeholder="Ketik pesan..."
                class="flex-1 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-1 focus:ring-blue-300">

            <button id="send-btn"
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-xl text-sm flex items-center gap-1">
                Kirim
            </button>
        </div>

        @else
        {{-- EMPTY STATE --}}
        <div class="flex-1 flex items-center justify-center text-gray-400">
            Pilih user untuk mulai chat
        </div>
        @endif

    </div>
</div>
</div>
</div>
@if($selectedUser)
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const authId = {{auth()->id()}};
        const otherId = {{$selectedUser->id}};
        const csrfToken = '{{ csrf_token() }}';
        const sendUrl = '{{ route('chat.send') }}';
        const readUrl = '{{ route('chat.read',$selectedUser->id) }}';

        const chatBox = document.getElementById('chat-box');
        const input = document.getElementById('chat-input');
        const sendBtn = document.getElementById('send-btn');

        function scrollBottom() {
            chatBox.scrollTo({
                top: chatBox.scrollHeight,
                behavior: 'smooth'
            });
        }

        scrollBottom();

        function appendBubble(teks, waktu, isOwn) {

            const wrapper = document.createElement('div');
            wrapper.className = 'flex ' + (isOwn ? 'justify-end' : 'justify-start');

            const bubble = document.createElement('div');

            bubble.className = isOwn ?
                'px-4 py-2 rounded-2xl rounded-tr-sm bg-blue-500 text-white text-sm max-w-[75%]' :
                'px-4 py-2 rounded-2xl rounded-tl-sm bg-white border text-sm max-w-[75%]';

            bubble.innerHTML = `
                    ${teks}
                    <div class="text-[10px] mt-1 text-right">
                        ${waktu} ${isOwn ? '✔' : ''}
                    </div>
                `;

            wrapper.appendChild(bubble);
            chatBox.appendChild(wrapper);

            scrollBottom();
        }

        async function kirimPesan() {
            const teks = input.value.trim();
            if (!teks) return;

            input.value = '';

            appendBubble(teks, 'now', true);

            await fetch(sendUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    penerima_id: otherId,
                    teks
                })
            });
        }

        sendBtn.addEventListener('click', kirimPesan);

        input.addEventListener('keydown', e => {
            if (e.key === 'Enter') {
                e.preventDefault();
                kirimPesan();
            }
        });

        if (window.Echo) {

            const ids = [authId, otherId].sort((a, b) => a - b);

            window.Echo.private(`chat.${ids[0]}.${ids[1]}`).listen('.pesan.kirim', (e) => {

                    if (e.pengirim_id !== authId) {
                        window.dispatchEvent(new CustomEvent('notify', {
                            detail: {
                                title: e.nama_pengirim ?? 'Pesan Baru', message: e.teks
                            }
                        }));
                    }

                    if (e.pengirim_id !== authId) {

                        appendBubble(e.teks, e.created_at, false);

                        fetch(readUrl, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            }
                        });
                    }

                });
        }
    });
</script>
@endif
@endsection