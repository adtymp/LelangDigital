@props(['menus' => [], 'badges' => [], 'badgeUrl' => ''])

@php
$role = auth()->user()->getRoleNames()->first();
$menus = config('sidebar')[$role] ?? [];
@endphp

<div x-data="sidebarComponent({{ json_encode($badges) }}, '{{ route('api.badges') }}')"
    x-init="startBadgePolling()"
    class="flex">

    {{-- OVERLAY (Mobile & Tablet) --}}
    <div
        x-show="sidebarOpen"
        @click="sidebarOpen = false"
        class="fixed inset-0 bg-black/50 z-30 lg:hidden"
        x-transition.opacity>
    </div>

    {{-- SIDEBAR --}}
    <aside
        class="fixed z-40 top-0 left-0 h-full w-60 sm:w-64 bg-white border-r border-gray-200 shadow transform transition-all duration-300
        lg:translate-x-0 flex flex-col"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

        {{-- HEADER --}}
        <div class="p-5 border-b-2 border-gray-200 bg-white relative">

            <!-- Brand -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-brand-50 border border-brand-100 flex items-center justify-center shadow-sm">
                    <img src="{{ asset('image/logo-pranala.png') }}" alt="Logo" class="w-6 h-6 object-contain">
                </div>

                <div>
                    <h1 class="text-base font-bold text-brand-600 leading-tight">
                        Pranala GigPortal
                    </h1>
                    <p class="text-xs text-gray-500">
                        PT. Pranala Digital Transmaritim
                    </p>
                </div>
            </div>

            <!-- User Info -->
            <div class="mt-5 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-brand-100 text-brand-600 flex items-center justify-center font-semibold text-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

                <div class="min-w-0">
                    <p class="font-semibold text-gray-800 truncate">
                        {{ auth()->user()->name }}
                    </p>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-600 capitalize">
                            {{ $role }}
                        </span>
                        @role('freelancer')
                        <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-700 font-medium">
                            Level {{ auth()->user()->level?->nama_level }}
                        </span>
                        @endrole
                    </div>
                </div>
            </div>
        </div>

        {{-- MENU --}}
        <nav class="flex-1 overflow-y-auto mt-4 space-y-1 px-3">
            @foreach ($menus as $menu)
            @if(isset($menu['children']))
            @php
            $isActive = collect($menu['children'])->pluck('route')->contains(fn($route) => request()->routeIs($route));
            @endphp

            <div x-data="{ open: {{ $isActive ? 'true' : 'false' }} }" class="space-y-1">
                {{-- PARENT MENU --}}
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-sm transition
                            {{ $isActive ? 'bg-brand-500 text-white' : 'text-gray-600 hover:bg-brand-100 hover:text-brand-500' }}">
                    <div class="flex items-center gap-2">
                        @include('components.sidebar-icon', ['icon' => $menu['icon']])
                        <span>{{ $menu['label'] }}</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                {{-- CHILD MENU --}}
                <div x-show="open" x-transition class="ml-6 space-y-1">
                    @foreach ($menu['children'] as $child)
                    <a href="{{ route($child['route']) }}"
                        class="block px-3 py-2 rounded-md text-sm transition
                                    {{ request()->routeIs($child['route']) ? 'bg-brand-500 text-white' : 'text-gray-500 hover:bg-brand-100 hover:text-brand-500' }}">
                        {{ $child['label'] }}
                    </a>
                    @endforeach
                </div>
            </div>
            @else
            {{-- MENU UTAMA --}}
            <a href="{{ route($menu['route']) }}"
                class="flex items-center justify-between px-3 py-2 rounded-lg transition text-sm
                        {{ request()->routeIs($menu['route']) ? 'bg-brand-500 text-white' : 'text-gray-600 hover:bg-brand-100 hover:text-brand-500' }}">
                <div class="flex items-center gap-2">
                    @include('components.sidebar-icon', ['icon' => $menu['icon']])
                    <span>{{ $menu['label'] }}</span>
                </div>

                {{-- Real-Time Badge --}}
                <template x-if="getBadge('{{ $menu['badge'] ?? '' }}') > 0">
                    <span class="text-xs px-2 py-0.5 rounded-full bg-red-500 text-white font-bold animate-pulse"
                        x-text="getBadge('{{ $menu['badge'] ?? '' }}')">
                    </span>
                </template>
            </a>
            @endif
            @endforeach
        </nav>

        {{-- LOGOUT --}}
        <div class="p-3 border-t-2 border-gray-200 bg-white">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 px-3 py-3 rounded-xl text-gray-600 hover:bg-red-50 hover:text-red-600 transition">
                    @include('components.sidebar-icon', ['icon' => 'logout'])
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- TOGGLE BUTTON & FLOATING BADGE (Mobile & Tablet) --}}
    <button
        @click="sidebarOpen = !sidebarOpen"
        class="fixed z-50 top-4 left-4 lg:hidden bg-white p-2 rounded-lg shadow transition-all duration-300 flex items-center justify-center"
        :class="sidebarOpen ? 'translate-x-60 sm:translate-x-64' : 'translate-x-0'">

        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" viewBox="0 0 448 512">
            <path fill="currentColor" d="M0 96C0 78.3 14.3 64 32 64h384c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 128 0 113.7 0 96zm0 160c0-17.7 14.3-32 32-32h384c17.7 0 32 14.3 32 32s-14.3 32-32 32H32c-17.7 0-32-14.3-32-32zm448 160c0 17.7-14.3 32-32 32H32c-17.7 0-32-14.3-32-32s14.3-32 32-32h384c17.7 0 32 14.3 32 32z" />
        </svg>

        {{-- Floating Badge: Muncul hanya ketika sidebar tertutup dan ada badge aktif > 0 --}}
        <span
            x-show="!sidebarOpen && Object.values(badges).reduce((sum, val) => sum + (parseInt(val) || 0), 0) > 0"
            x-transition
            class="absolute -top-1.5 -right-1.5 min-w-5 h-5 bg-red-500 text-white rounded-full text-[10px] font-bold flex items-center justify-center border-2 border-white px-1 shadow-sm animate-bounce"
            x-text="Object.values(badges).reduce((sum, val) => sum + (parseInt(val) || 0), 0)">
        </span>
    </button>
</div>

<script>
    function sidebarComponent(initialBadges, badgeUrl) {
        return {
            sidebarOpen: false,
            badges: initialBadges || {},
            badgeUrl: badgeUrl,

            getBadge(key) {
                if (!key) return 0;
                return this.badges[key] || 0;
            },

            async fetchBadges() {
                try {
                    const res = await fetch(this.badgeUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });
                    if (res.ok) {
                        this.badges = await res.json();
                    }
                } catch (e) {
                    console.warn('Badge fetch failed:', e);
                }
            },

            startBadgePolling() {
                // Fetch awal setelah 5 detik
                setTimeout(() => this.fetchBadges(), 5000);

                // Polling setiap 30 detik
                setInterval(() => this.fetchBadges(), 30000);

                // Jalankan WebSocket listener untuk respon instan
                this.listenBadgeEvents();
            },

            listenBadgeEvents() {
                const authId = document
                    .querySelector('meta[name="auth-id"]')
                    ?.getAttribute('content');

                if (!authId || !window.Echo) return;

                const userChannel = window.Echo.private(`user.${authId}`);

                // 1. Listen UpdateBadge event (Reverb)
                userChannel.listen('.badge.updated', (e) => {
                    if (e.badges) {
                        this.badges = e.badges;
                    }
                });

                // 2. Listen Pesan Baru (Chat) -> Fetch ulang badge agar update seketika
                userChannel.listen('.pesan.kirim', () => {
                    this.fetchBadges();
                });

                // 3. Listen Proyek Baru Aktif -> Fetch ulang badge agar update seketika
                userChannel.listen('.proyek.aktif', () => {
                    this.fetchBadges();
                });
            }
        }
    }
</script>