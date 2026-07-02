<nav class="fixed top-0 left-0 w-full h-20 bg-white border-b border-gray-200 z-50 flex items-center justify-between px-4 sm:px-6 shadow-sm">
    {{-- Sisi Kiri: Hamburger + Brand --}}
    <div class="flex items-center gap-3">
        <button
            x-data="{ totalBadges: 0 }"
            @badges-updated.window="totalBadges = Object.values($event.detail).reduce((sum, val) => sum + (parseInt(val) || 0), 0)"
            @click="window.dispatchEvent(new CustomEvent('toggle-sidebar'))"
            class="lg:hidden p-2.5 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors relative overflow-visible">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            {{-- Badge: pakai overflow-visible agar tidak terpotong --}}
            <span
                x-show="totalBadges > 0"
                x-transition
                class="absolute -top-1.5 -right-1.5 min-w-4.5 h-4.5 bg-red-500 text-white rounded-full text-[9px] font-extrabold flex items-center justify-center px-1 border-2 border-white shadow-sm animate-pulse z-10"
                x-text="totalBadges > 99 ? '99+' : totalBadges">
            </span>
        </button>

        <a href="/" class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-xl bg-brand-50 border border-brand-100 flex items-center justify-center shadow-xs shrink-0">
                <img src="{{ asset('image/logo-pranala.png') }}"
                    alt="Logo Pranala"
                    width="40"
                    height="40"
                    fetchpriority="high"
                    class="w-6 h-6 object-contain">
            </div>
            <span class="text-sm sm:text-base font-bold text-brand-600 truncate max-w-32 sm:max-w-none">Pranala GigPortal</span>
        </a>
    </div>

    {{-- Sisi Kanan: Notifikasi + User Dropdown --}}
    <div class="flex items-center gap-2 sm:gap-4">

        {{-- DROPDOWN NOTIFIKASI (Role Freelancer Saja) --}}
        @role('freelancer')
        <div x-data="notificationComponent()" x-init="init()" class="relative">
            <button @click="toggleDropdown()" class="p-2.5 text-gray-500 hover:bg-gray-100 rounded-xl transition relative overflow-visible">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <template x-if="unreadCount > 0">
                    <span class="absolute -top-1.5 -right-1.5 min-w-4.5 h-4.5 bg-red-500 text-white rounded-full text-[9px] font-extrabold flex items-center justify-center px-1 border-2 border-white animate-pulse z-10"
                        x-text="unreadCount > 99 ? '99+' : unreadCount"></span>
                </template>
            </button>

            {{-- PANEL DROPDOWN NOTIF --}}
            <div x-show="open"
                @click.outside="open = false"
                x-transition
                class="fixed sm:absolute top-24 sm:top-auto left-4 right-4 sm:left-auto
                sm:right-0 mt-0 sm:mt-2 w-auto sm:w-80 bg-white border border-gray-200 rounded-2xl shadow-xl z-[9999] overflow-hidden flex flex-col max-h-[70vh] sm:max-h-96"
                style="display:none;">
                <div class="px-4 py-3 bg-linear-to-r from-brand-500 to-brand-700 border-b border-gray-100 flex items-center justify-between shrink-0">
                    <span class="text-xs font-bold text-white">Notifikasi</span>
                    <button @click="markAsRead()" x-show="unreadCount > 0" class="text-[10px] text-blue-600 hover:underline font-semibold">Tandai semua dibaca</button>
                </div>
                <div class="overflow-y-auto flex-1 divide-y divide-gray-100">
                    <template x-for="item in list" :key="item.id">
                        <div class="p-3.5 hover:bg-gray-50/50 transition duration-150 flex flex-col gap-1" :class="item.read_at ? 'opacity-70' : 'bg-blue-50/10'">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-xs font-bold text-gray-800 truncate" x-text="item.title"></span>
                                <span class="text-[10px] text-gray-400 font-medium shrink-0" x-text="item.time_ago"></span>
                            </div>
                            <p class="text-xs text-gray-600 leading-normal" x-text="item.message"></p>
                        </div>
                    </template>
                    <template x-if="list.length === 0">
                        <div class="p-6 text-center text-gray-400 text-xs">Tidak ada notifikasi</div>
                    </template>
                </div>
            </div>
        </div>
        @endrole

        {{-- DROPDOWN USER --}}
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex items-center gap-2 py-1 px-1 sm:px-2 rounded-xl hover:bg-gray-100 transition">
                <div class="w-8 h-8 rounded-full bg-brand-100 text-brand-600 flex items-center justify-center font-bold text-xs shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <span class="hidden sm:block text-xs font-medium text-gray-700 max-w-20 truncate">{{ auth()->user()->name }}</span>
                <svg class="w-4 h-4 text-gray-500 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            {{-- MENU USER DROPDOWN --}}
            <div x-show="open" @click.outside="open = false" x-transition
                class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-xl shadow-xl z-50"
                style="display: none;">
                @role('freelancer')
                <a href="{{ route('profil.freelance') }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100 hover:rounded-t-xl font-medium">Profil Saya</a>
                @endrole
                <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-100 mt-1">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-xs text-red-600 hover:bg-red-100 hover:rounded-b-xl font-medium">Logout</button>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
    function notificationComponent() {
        return {
            open: false,
            list: [],
            unreadCount: 0,
            url: '{{ route("notifications.index") }}',
            readUrl: '{{ route("notifications.read") }}',

            async init() {
                await this.fetchNotifications();
                setInterval(() => this.fetchNotifications(), 30000);

                const authId = document.querySelector('meta[name="auth-id"]')?.getAttribute('content');
                if (authId && window.Echo) {
                    const channel = window.Echo.private(`user.${authId}`);
                    channel.listen('.proyek.aktif', () => this.fetchNotifications());
                    channel.listen('.nilai.tugas', () => this.fetchNotifications());
                    channel.listen('.pembayaran.diunggah', () => this.fetchNotifications());
                }
            },

            async fetchNotifications() {
                try {
                    const res = await fetch(this.url);
                    if (res.ok) {
                        const data = await res.json();
                        this.list = data.notifications;
                        this.unreadCount = data.unread_count;
                    }
                } catch (e) {
                    console.warn(e);
                }
            },

            toggleDropdown() {
                this.open = !this.open;
                if (this.open && this.unreadCount > 0) {
                    this.markAsRead();
                }
            },

            async markAsRead() {
                this.unreadCount = 0;
                try {
                    await fetch(this.readUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                } catch (e) {
                    console.warn(e);
                }
            }
        }
    }
</script>