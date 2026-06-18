@extends('layouts.body', ['title' => 'Chat'])

@section('content')
<div x-data="{ openChat: {{ request('user') ? 'true' : 'false' }} }"
    class="flex bg-white rounded-2xl border border-gray-200 overflow-hidden h-[calc(100vh-7rem)] lg:h-[calc(100vh-5rem)] shadow-sm">

    {{-- ================================= --}}
    {{-- LIST USER (DAFTAR CHAT)          --}}
    {{-- ================================= --}}
    <div
        class="w-full md:w-80 border-r border-gray-200 flex flex-col bg-white transition-all duration-300"
        :class="openChat ? 'hidden md:flex' : 'flex'">

        <div class="px-4 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
            <h2 class="text-base font-bold text-gray-800">Daftar Obrolan</h2>
            <span class="text-xs px-2.5 py-1 rounded-full bg-blue-50 text-blue-600 font-semibold">
                {{ count($users) }} Kontak
            </span>
        </div>

        <div class="overflow-y-auto flex-1 divide-y divide-gray-100">
            @forelse($users as $u)
            @php $unread = $unreadCounts[$u->id] ?? 0; @endphp

            <a href="{{ route('chat.index', ['user' => $u->id]) }}"
                @click="openChat = true"
                class="flex items-center justify-between px-4 py-3.5 hover:bg-gray-50/80 transition-all duration-200
                        {{ request('user') == $u->id ? 'bg-blue-50/50 border-l-4 border-blue-500' : '' }}">

                <div class="flex items-center gap-3 min-w-0">
                    {{-- Avatar dengan inisial --}}
                    <div class="w-10 h-10 rounded-full bg-brand-50 border border-brand-100 text-brand-600 flex items-center justify-center font-bold relative shrink-0">
                        {{ strtoupper(substr($u->name, 0, 1)) }}

                        {{-- Status Online Indicator (Dekoratif) --}}
                    </div>

                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $u->name }}</p>
                        <p class="text-xs text-gray-500 truncate mt-0.5">{{ $u->email }}</p>
                    </div>
                </div>

                @if($unread > 0)
                <span class="bg-red-500 text-white text-[10px] font-bold rounded-full w-5 h-5 flex items-center justify-center shrink-0 shadow-sm animate-pulse">
                    {{ $unread > 9 ? '9+' : $unread }}
                </span>
                @endif
            </a>
            @empty
            <div class="flex-1 flex flex-col items-center justify-center p-6 text-center text-gray-400">
                <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <p class="text-sm">Tidak ada kontak tersedia</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- ================================= --}}
    {{-- AREA CHAT BOX                    --}}
    {{-- ================================= --}}
    <div
        class="flex-1 flex flex-col bg-gray-50 transition-all duration-300"
        :class="!openChat ? 'hidden md:flex' : 'flex'">

        @if($selectedUser)
        {{-- CHAT HEADER --}}
        <div class="px-4 py-3 bg-white border-b border-gray-200 flex items-center justify-between shrink-0 shadow-sm">
            <div class="flex items-center gap-3 min-w-0">
                {{-- Tombol kembali untuk Mobile --}}
                <button @click="openChat = false"
                    class="md:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </button>

                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm shrink-0">
                    {{ strtoupper(substr($selectedUser->name, 0, 1)) }}
                </div>

                <div class="min-w-0">
                    <h3 class="text-sm font-bold text-gray-800 truncate leading-tight">{{ $selectedUser->name }}</h3>
                </div>
            </div>
        </div>

        {{-- CHAT BUBBLES LIST --}}
        <div id="chat-box" class="flex-1 overflow-y-auto p-4 space-y-4">
            @foreach($messages as $msg)
            @if($msg->pengirim_id == auth()->id())
            {{-- PESAN KELUAR (OWN) --}}
            <div class="flex justify-end">
                <div class="max-w-[85%] sm:max-w-[70%] md:max-w-md bg-blue-600 text-white px-4 py-2.5 rounded-2xl rounded-tr-none shadow-sm text-sm">
                    <p class="leading-relaxed break-words whitespace-pre-line">{{ $msg->teks }}</p>

                    <div class="flex items-center gap-1 justify-end mt-1 text-[10px] text-blue-100 font-medium select-none">
                        <span>{{ $msg->created_at->format('H:i') }}</span>
                        @if($msg->status === 'sent')
                        <span>✔</span>
                        @elseif($msg->status === 'delivered')
                        <span>✔✔</span>
                        @elseif($msg->status === 'read')
                        <span class="text-sky-200">✔✔</span>
                        @endif
                    </div>
                </div>
            </div>
            @else
            {{-- PESAN MASUK (SENDER) --}}
            <div class="flex justify-start">
                <div class="max-w-[85%] sm:max-w-[70%] md:max-w-md bg-white border border-gray-200 text-gray-800 px-4 py-2.5 rounded-2xl rounded-tl-none shadow-sm text-sm">
                    <p class="leading-relaxed break-words whitespace-pre-line">{{ $msg->teks }}</p>
                    <div class="text-[10px] text-gray-400 mt-1 text-right font-medium select-none">
                        {{ $msg->created_at->format('H:i') }}
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        </div>

        {{-- INPUT CHAT BAR --}}
        <div class="px-4 py-3 bg-white border-t border-gray-200 flex gap-2 items-center shrink-0">
            <input
                type="text"
                id="chat-input"
                placeholder="Ketik pesan..."
                class="flex-1 border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 bg-gray-50 focus:bg-white placeholder:text-gray-400">

            <button id="send-btn"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-1.5 transition duration-150 shadow-sm shrink-0">
                <span>Kirim</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
            </button>
        </div>
        @else
        {{-- EMPTY STATE --}}
        <div class="flex-1 flex flex-col items-center justify-center p-8 text-center text-gray-400 bg-white">
            <div class="w-20 h-20 rounded-full bg-blue-50 flex items-center justify-center mb-4 shadow-inner">
                <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
            </div>
            <h4 class="text-base font-bold text-gray-800 mb-1">Mulai Diskusi</h4>
            <p class="text-sm text-gray-500 max-w-xs">Pilih salah satu kontak dari daftar obrolan untuk mengirimkan pesan.</p>
        </div>
        @endif
    </div>
</div>

@if($selectedUser)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const authId = {{ auth()->id() }};
        const otherId = {{ $selectedUser->id }};
        const csrfToken = '{{ csrf_token() }}';
        const sendUrl = '{{ route('chat.send') }}';
        const readUrl = '{{ route('chat.read', $selectedUser->id) }}';

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
                'max-w-[85%] sm:max-w-[70%] md:max-w-md bg-blue-600 text-white px-4 py-2.5 rounded-2xl rounded-tr-none shadow-sm text-sm' :
                'max-w-[85%] sm:max-w-[70%] md:max-w-md bg-white border border-gray-200 text-gray-800 px-4 py-2.5 rounded-2xl rounded-tl-none shadow-sm text-sm';

            bubble.innerHTML = `
                <p class="leading-relaxed break-words whitespace-pre-line">${teks}</p>
                <div class="text-[10px] mt-1 text-right font-medium select-none ${isOwn ? 'text-blue-100' : 'text-gray-400'}">
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
            window.Echo.private(`chat.${ids[0]}.${ids[1]}`)
                .listen('.pesan.kirim', (e) => {
                    if (e.pengirim_id !== authId) {
                        window.dispatchEvent(new CustomEvent('notify', {
                            detail: {
                                title: e.nama_pengirim ?? 'Pesan Baru',
                                message: e.teks
                            }
                        }));
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