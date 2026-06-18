@props([
'action' => '',
'searchName' => 'search',
'searchPlaceholder' => 'Cari data...',
'filters' => [],
])

@php
// Menghitung jumlah filter aktif dari parameter request GET
$activeFiltersCount = 0;
foreach ($filters as $f) {
if (request($f['name'])) {
$activeFiltersCount++;
}
}
@endphp

<div x-data="{ showFilterModal: false }">
    <form
        method="GET"
        action="{{ $action }}"
        class="w-full mb-6">

        <!-- Main Search and Toggle Bar (Mobile: Stacked, Tablet/Desktop: Row) -->
        <div class="flex flex-col sm:flex-row gap-3">

            <!-- Input Pencarian -->
            <div class="relative flex-1">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                        <path fill="currentColor" d="M480 272C480 317.9 465.1 360.3 440 394.7L566.6 521.4C579.1 533.9 579.1 554.2 566.6 566.7C554.1 579.2 533.8 579.2 521.3 566.7L394.7 440C360.3 465.1 317.9 480 272 480C157.1 480 64 386.9 64 272C64 157.1 157.1 64 272 64C386.9 64 480 157.1 480 272zM272 416C351.5 416 416 351.5 416 272C416 192.5 351.5 128 272 128C192.5 128 128 192.5 128 272C128 351.5 192.5 416 272 416z" />
                    </svg>
                </span>
                <input
                    type="text"
                    name="{{ $searchName }}"
                    value="{{ request($searchName) }}"
                    placeholder="{{ $searchPlaceholder }}"
                    class="w-full pl-11 pr-4 py-3.5 bg-white border border-slate-200 rounded-2xl
                           focus:border-brand-500
                           focus:ring-2
                           focus:ring-brand-500/10
                           focus:outline-none
                           transition-all
                           text-sm
                           shadow-xs">
            </div>

            <!-- Tombol Aksi di Kanan (Mobile: Lebar penuh, Tablet/Desktop: Auto) -->
            <div class="flex items-center gap-2 shrink-0">
                <!-- Tombol Buka Modal Filter -->
                <button
                    type="button"
                    @click="showFilterModal = true"
                    class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-5 py-3.5 rounded-2xl border text-sm font-semibold transition-all shadow-xs cursor-pointer focus:outline-none"
                    :class="{'border-brand-300 text-brand-700 bg-brand-50 hover:bg-brand-100/70': {{ $activeFiltersCount }} > 0, 
                             'border-slate-200 text-slate-700 bg-white hover:bg-slate-50': {{ $activeFiltersCount }} == 0}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                        <path fill="currentColor" d="M96 128C83.1 128 71.4 135.8 66.4 147.8C61.4 159.8 64.2 173.5 73.4 182.6L256 365.3L256 480C256 488.5 259.4 496.6 265.4 502.6L329.4 566.6C338.6 575.8 352.3 578.5 364.3 573.5C376.3 568.5 384 556.9 384 544L384 365.3L566.6 182.7C575.8 173.5 578.5 159.8 573.5 147.8C568.5 135.8 556.9 128 544 128L96 128z" />
                    </svg>
                    <span>Filter</span>
                    @if($activeFiltersCount > 0)
                    <span class="flex items-center justify-center min-w-5 h-5 px-1.5 text-[10px] font-bold text-white bg-brand-600 rounded-full">
                        {{ $activeFiltersCount }}
                    </span>
                    @endif
                </button>

                <!-- Tombol Reset Seluruh Parameter Filter -->
                @if(request()->hasAny([$searchName, ...collect($filters)->pluck('name')->toArray()]))
                @if(request($searchName) || $activeFiltersCount > 0)
                <a
                    href="{{ url()->current() }}"
                    class="inline-flex items-center justify-center px-4 py-3.5 rounded-2xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-500 hover:text-slate-700 transition text-sm font-semibold shadow-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                        <path fill="currentColor" d="M129.9 292.5C143.2 199.5 223.3 128 320 128C373 128 421 149.5 455.8 184.2C456 184.4 456.2 184.6 456.4 184.8L464 192L416.1 192C398.4 192 384.1 206.3 384.1 224C384.1 241.7 398.4 256 416.1 256L544.1 256C561.8 256 576.1 241.7 576.1 224L576.1 96C576.1 78.3 561.8 64 544.1 64C526.4 64 512.1 78.3 512.1 96L512.1 149.4L500.8 138.7C454.5 92.6 390.5 64 320 64C191 64 84.3 159.4 66.6 283.5C64.1 301 76.2 317.2 93.7 319.7C111.2 322.2 127.4 310 129.9 292.6zM573.4 356.5C575.9 339 563.7 322.8 546.3 320.3C528.9 317.8 512.6 330 510.1 347.4C496.8 440.4 416.7 511.9 320 511.9C267 511.9 219 490.4 184.2 455.7C184 455.5 183.8 455.3 183.6 455.1L176 447.9L223.9 447.9C241.6 447.9 255.9 433.6 255.9 415.9C255.9 398.2 241.6 383.9 223.9 383.9L96 384C87.5 384 79.3 387.4 73.3 393.5C67.3 399.6 63.9 407.7 64 416.3L65 543.3C65.1 561 79.6 575.2 97.3 575C115 574.8 129.2 560.4 129 542.7L128.6 491.2L139.3 501.3C185.6 547.4 249.5 576 320 576C449 576 555.7 480.6 573.4 356.5z" />
                    </svg>
                </a>
                @endif
                @endif
            </div>

        </div>

        <!-- Pil Filter Aktif (Filter Pills) -->
        @if($activeFiltersCount > 0)
        <div class="flex flex-wrap items-center gap-2 mt-3 pl-1">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Filter Aktif:</span>
            @foreach($filters as $filter)
            @if(request($filter['name']))
            @php
            $selectedLabel = $filter['options'][request($filter['name'])] ?? request($filter['name']);
            @endphp
            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold bg-brand-50 text-brand-700 rounded-full border border-brand-100 shadow-xs">
                {{ $filter['placeholder'] }}: {{ $selectedLabel }}
                <a href="{{ request()->fullUrlWithQuery([$filter['name'] => null]) }}" class="hover:text-brand-900 ml-0.5 text-[10px] focus:outline-none">✕</a>
            </span>
            @endif
            @endforeach
        </div>
        @endif

        <!-- FILTER MODAL (Overlay) -->
        <div
            x-show="showFilterModal"
            x-transition.opacity
            x-cloak
            class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
            @click.self="showFilterModal = false"
            @keydown.escape.window="showFilterModal = false">

            <div
                x-show="showFilterModal"
                x-transition
                class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden transform transition-all flex flex-col max-h-[85vh]">

                <!-- Modal Header -->
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between shrink-0">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Filter Pencarian</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Pilih kriteria penyaringan data di bawah ini.</p>
                    </div>
                    <button type="button" @click="showFilterModal = false" class="text-slate-400 hover:text-slate-600 transition-colors text-lg focus:outline-none">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Modal Body (Pilihan Dropdown) -->
                <div class="p-6 overflow-y-auto space-y-4 flex-1">
                    @foreach($filters as $filter)
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                            {{ $filter['placeholder'] }}
                        </label>
                        <div class="relative">
                            <select
                                name="{{ $filter['name'] }}"
                                class="w-full appearance-none pl-4 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10 focus:outline-none transition text-sm text-slate-700">
                                <option value="">Semua {{ $filter['placeholder'] }}</option>
                                @foreach($filter['options'] as $value => $label)
                                <option
                                    value="{{ $value }}"
                                    @selected(request($filter['name'])==$value)>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs">
                                <i class="fas fa-chevron-down"></i>
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex gap-3 shrink-0">
                    <a
                        href="{{ url()->current() }}"
                        class="flex-1 inline-flex justify-center items-center px-4 py-2.5 bg-white border border-slate-200 text-sm font-semibold text-slate-700 rounded-xl hover:bg-slate-50 transition shadow-xs">
                        Reset Filter
                    </a>
                    <button
                        type="submit"
                        class="flex-1 inline-flex justify-center items-center px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-xl transition shadow-xs focus:outline-none">
                        Terapkan Filter
                    </button>
                </div>

            </div>
        </div>

    </form>
</div>