@props(['menus' => [], 'badges' => []])

@php
$role = auth()->user()->getRoleNames()->first();
$menus = config('sidebar')[$role] ?? [];
@endphp

<div x-data="{ sidebarOpen: false }" class="flex">

    {{-- OVERLAY (Mobile) --}}
    <div
        x-show="sidebarOpen"
        @click="sidebarOpen = false"
        class="fixed inset-0 bg-black/50 z-30 lg:hidden"
        x-transition.opacity>
    </div>

    {{-- SIDEBAR --}}
    <aside
        class="fixed z-40 top-0 left-0 h-full w-64 bg-white border-r border-gray-200 shadow transform transition-all duration-300
        lg:translate-x-0"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

        {{-- HEADER --}}
        <div class="p-5 border-gray-200 border-b">
            <h1 class="text-brand-500 font-bold text-lg">Sistem Lelang</h1>
            <p class="text-gray-500 text-sm mt-1">{{ auth()->user()->name }}</p>
            <p class="text-xs text-gray-400 capitalize">
                {{ $role }}
                @role('freelancer')
                <span class="text-green-600">
                    | Level {{ auth()->user()->level->nama_level }}
                </span>
                @endrole
            </p>
        </div>

        {{-- MENU --}}
        <nav class="mt-4 space-y-1 px-3">

            @foreach ($menus as $menu)

            {{-- CEK ADA CHILD ATAU TIDAK --}}
            @if(isset($menu['children']))

            @php
            $isActive = collect($menu['children'])
            ->pluck('route')
            ->contains(fn($route) => request()->routeIs($route));
            @endphp

            <div
                x-data="{ open: {{ $isActive ? 'true' : 'false' }} }"
                class="space-y-1">

                {{-- PARENT MENU --}}
                <button
                    @click="open = !open"
                    class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-sm transition
            {{ $isActive ? 'bg-brand-500 text-white' : 'text-gray-600 hover:bg-brand-100 hover:text-brand-500' }}">

                    <div class="flex items-center gap-2">
                        @include('components.sidebar-icon', ['icon' => $menu['icon']])
                        <span>{{ $menu['label'] }}</span>
                    </div>

                    {{-- ICON ARROW --}}
                    <svg
                        class="w-4 h-4 transition-transform"
                        :class="open ? 'rotate-180' : ''"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 9l-7 7-7-7" />
                    </svg>

                </button>

                {{-- CHILD MENU --}}
                <div
                    x-show="open"
                    x-transition
                    class="ml-6 space-y-1">

                    @foreach ($menu['children'] as $child)
                    <a href="{{ route($child['route']) }}"
                        class="block px-3 py-2 rounded-md text-sm transition
                    {{ request()->routeIs($child['route']) 
                        ? 'bg-brand-500 text-white' 
                        : 'text-gray-500 hover:bg-brand-100 hover:text-brand-500' }}">
                        {{ $child['label'] }}
                    </a>
                    @endforeach

                </div>

            </div>

            @else

            {{-- MENU BIASA --}}
            @php
            $badge = $menu['badge'] ?? null
            ? ($badges[$menu['badge']] ?? 0)
            : 0;
            @endphp

            <a href="{{ route($menu['route']) }}"
                class="flex items-center justify-between px-3 py-2 rounded-lg transition text-sm
        {{ request()->routeIs($menu['route']) 
            ? 'bg-brand-500 text-white' 
            : 'text-gray-600 hover:bg-brand-100 hover:text-brand-500' }}">

                <div class="flex items-center gap-2">
                    @include('components.sidebar-icon', ['icon' => $menu['icon']])
                    <span>{{ $menu['label'] }}</span>
                </div>

                @if ($badge > 0)
                <span class="text-xs px-2 py-0.5 rounded-full bg-red-500 text-white">
                    {{ $badge }}
                </span>
                @endif
            </a>

            @endif

            @endforeach

            {{-- LOGOUT --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-gray-600 hover:bg-red-100 hover:text-red-600 transition">
                    @include('components.sidebar-icon',['icon'=>'logout'])
                    <span>Logout</span>
                </button>
            </form>

        </nav>
    </aside>

    {{-- TOGGLE BUTTON --}}
    <button
        @click="sidebarOpen = !sidebarOpen"
        class="fixed z-50 top-4 left-4 lg:hidden bg-white p-2 rounded-lg shadow transition-all duration-300"
        :class="sidebarOpen ? 'translate-x-64' : 'translate-x-0'">

        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 448 512">
            <path fill="currentColor"
                d="M0 96C0 78.3 14.3 64 32 64h384c17.7 0 32 14.3 
                32 32s-14.3 32-32 32H32C14.3 128 0 113.7 0 96zm0 
                160c0-17.7 14.3-32 32-32h384c17.7 0 32 14.3 
                32 32s-14.3 32-32 32H32c-17.7 0-32-14.3-32-32zm448 
                160c0 17.7-14.3 32-32 32H32c-17.7 
                0-32-14.3-32-32s14.3-32 32-32h384c17.7 
                0 32 14.3 32 32z" />
        </svg>
    </button>

</div>