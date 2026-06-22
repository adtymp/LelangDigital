@props(['menus' => [], 'badges' => [], 'badgeUrl' => ''])

@php
$role = auth()->user()->getRoleNames()->first();
$menus = config('sidebar')[$role] ?? [];
@endphp

<div x-data="sidebarComponent({{ json_encode($badges) }}, '{{ route('api.badges') }}')"
    x-init="startBadgePolling()"
    @toggle-sidebar.window="sidebarOpen = !sidebarOpen">

    {{-- OVERLAY (Mobile & Tablet) --}}
    <div
        x-show="sidebarOpen"
        @click="sidebarOpen = false"
        class="fixed inset-0 bg-black/50 z-40 lg:hidden"
        x-transition:enter="transition-opacity ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        style="display: none;">
    </div>

    {{-- SIDEBAR --}}
    {{--
        KUNCI LAYOUT:
        - Mobile/tablet: fixed, z-50, top-16 (tepat di bawah navbar), h-[calc(100vh-4rem)]
        - Desktop (lg): fixed, z-30, top-16, selalu terlihat (translate-x-0)
        - Lebar: w-64 di semua breakpoint untuk konsistensi
    --}}
    <aside
        class="fixed z-40 bottom-0 top-0 left-0 h-screen pt-20 w-64 min-w-[256px] bg-white border-r border-gray-200 shadow-lg lg:shadow-none transform transition-transform duration-300 ease-in-out lg:translate-x-0 flex flex-col"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

        {{-- HEADER USER --}}
        <div class="p-4 border-b border-gray-200 bg-white shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-brand-100 text-brand-600 flex items-center justify-center font-semibold text-sm shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="font-semibold text-gray-800 text-sm truncate">
                        {{ auth()->user()->name }}
                    </p>
                    <div class="flex items-center gap-1.5 flex-wrap mt-0.5">
                        <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 capitalize">
                            {{ $role }}
                        </span>
                        @role('freelancer')
                        <span class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-700 font-medium">
                            Level {{ auth()->user()->level?->nama_level }}
                        </span>
                        @endrole
                    </div>
                </div>
            </div>
        </div>

        {{-- MENU --}}
        <nav class="flex-1 overflow-y-auto py-3 space-y-0.5 px-3">
            @foreach ($menus as $menu)
            @if(isset($menu['children']))
            @php
            $isActive = collect($menu['children'])->pluck('route')->contains(fn($route) => request()->routeIs($route));
            @endphp

            <div x-data="{ open: {{ $isActive ? 'true' : 'false' }} }" class="space-y-0.5">
                {{-- PARENT MENU --}}
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm transition
                                {{ $isActive ? 'bg-brand-500 text-white' : 'text-gray-600 hover:bg-brand-50 hover:text-brand-600' }}">
                    <div class="flex items-center gap-2.5 min-w-0">
                        @include('components.sidebar-icon', ['icon' => $menu['icon']])
                        <span class="truncate">{{ $menu['label'] }}</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform shrink-0 ml-1" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                {{-- CHILD MENU --}}
                <div x-show="open" x-transition class="ml-5 space-y-0.5 pl-3 border-l-2 border-gray-100">
                    @foreach ($menu['children'] as $child)
                    <a href="{{ route($child['route']) }}"
                        @click="$dispatch('close-sidebar-mobile')"
                        class="block px-3 py-2 rounded-md text-sm transition
                                        {{ request()->routeIs($child['route']) ? 'bg-brand-500 text-white' : 'text-gray-500 hover:bg-brand-50 hover:text-brand-600' }}">
                        {{ $child['label'] }}
                    </a>
                    @endforeach
                </div>
            </div>
            @else
            {{-- MENU UTAMA --}}
            <a href="{{ route($menu['route']) }}"
                @click="sidebarOpen = false"
                class="flex items-center justify-between px-3 py-2.5 rounded-lg transition text-sm
                            {{ request()->routeIs($menu['route']) ? 'bg-brand-500 text-white' : 'text-gray-600 hover:bg-brand-50 hover:text-brand-600' }}">
                <div class="flex items-center gap-2.5 min-w-0">
                    @include('components.sidebar-icon', ['icon' => $menu['icon']])
                    <span class="truncate">{{ $menu['label'] }}</span>
                </div>

                {{-- Real-Time Badge — pakai shrink-0 + padding cukup agar tidak terpotong --}}
                <template x-if="getBadge('{{ $menu['badge'] ?? '' }}') > 0">
                    <span class="shrink-0 ml-2 min-w-5 h-5 px-1.5 rounded-full bg-red-500 text-white text-[10px] font-bold flex items-center justify-center animate-pulse"
                        x-text="getBadge('{{ $menu['badge'] ?? '' }}') > 99 ? '99+' : getBadge('{{ $menu['badge'] ?? '' }}')">
                    </span>
                </template>
            </a>
            @endif
            @endforeach
        </nav>

        {{-- LOGOUT --}}
        <div class="p-3 border-t border-gray-200 bg-white shrink-0">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-gray-600 hover:bg-red-50 hover:text-red-600 transition text-sm">
                    @include('components.sidebar-icon', ['icon' => 'logout'])
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>
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
                        window.dispatchEvent(new CustomEvent('badges-updated', {
                            detail: this.badges
                        }));
                    }
                } catch (e) {
                    console.warn('Badge fetch failed:', e);
                }
            },

            startBadgePolling() {
                setTimeout(() => this.fetchBadges(), 5000);
                setInterval(() => this.fetchBadges(), 30000);
                this.listenBadgeEvents();
            },

            listenBadgeEvents() {
                const authId = document
                    .querySelector('meta[name="auth-id"]')
                    ?.getAttribute('content');

                if (!authId || !window.Echo) return;

                const userChannel = window.Echo.private(`user.${authId}`);

                userChannel.listen('.badge.updated', (e) => {
                    if (e.badges) {
                        this.badges = e.badges;
                        window.dispatchEvent(new CustomEvent('badges-updated', {
                            detail: e.badges
                        }));
                    }
                });

                userChannel.listen('.pesan.kirim', () => this.fetchBadges());
                userChannel.listen('.proyek.aktif', () => this.fetchBadges());
            }
        }
    }
</script>