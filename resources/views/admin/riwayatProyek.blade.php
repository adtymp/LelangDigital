@extends('layouts.body', ['title' => 'Riwayat Proyek'])

@section('content')
<x-header
    :judul="'Riwayat Proyek'"
    :subjudul="'Data proyek yang telah selesai atau dibatalkan'" />

<div x-data="{
    modalPengambil : false,
    pengambilans : [],
    detail:{},
    namaSubProyek : '' }">

    <!-- search -->
    <x-search-filter
        :action="route('riwayat.proyek')"
        searchName="cari"
        searchPlaceholder="Cari proyek..."

        :filters="[
        [
            'name' => 'bulan',
            'placeholder' => 'Pilih Bulan',
            'options' => $bulanOptions
        ],
        [
            'name' => 'tahun',
            'placeholder' => 'Pilih Tahun',
            'options' => $tahunOptions
                ->mapWithKeys(fn($tahun) => [$tahun => $tahun])
                ->toArray()
        ]
    ]" />

    <div class="space-y-8">

        @forelse($groupedProyeks as $periode => $proyeks)

        {{-- HEADER BULAN --}}
        <div class="sticky top-0 z-10">

            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-linear-to-r from-brand-500 to-brand-700 text-white text-sm font-semibold">

                {{ strtoupper($periode) }}
                <span class="bg-white/20 px-2 py-1 rounded-lg text-xs">
                    {{ $proyeks->count() }} proyek
                </span>
            </div>
        </div>

        {{-- LOOP PROYEK DALAM BULAN --}}
        @foreach($proyeks as $proyek)

        <div
            x-data="{ openProyek : false }"
            class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">

            {{-- HEADER --}}
            <div
                @click="openProyek = !openProyek"
                class="p-6 cursor-pointer hover:bg-slate-50 transition">

                <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-5">

                    {{-- LEFT --}}
                    <div class="flex items-start gap-4">

                        <div class="w-10 h-10 rounded-2xl bg-slate-100 flex items-center justify-center">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-4 h-4 text-slate-500 transition duration-200"
                                :class="openProyek ? 'rotate-90' : ''"
                                viewBox="0 0 320 512"
                                fill="currentColor">

                                <path d="M96 96l128 160L96 416z" />

                            </svg>

                        </div>

                        <div>

                            <div class="flex flex-wrap items-center gap-3">

                                <h2 class="text-lg font-bold text-slate-800">
                                    {{ $proyek->nama_proyek }}
                                </h2>

                                <x-status :value="$proyek->status" />

                            </div>

                            <p class="mt-2 text-sm text-slate-500">

                                {{ $proyek->tanggal_mulai->format('d M Y') }}
                                —
                                {{ $proyek->tanggal_selesai->format('d M Y') }}

                            </p>

                        </div>

                    </div>

                    {{-- RIGHT --}}
                    <div class="grid grid-cols-2 gap-3">

                        <div class="bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 min-w-40">

                            <p class="text-xs font-medium text-slate-500">
                                Upah Dibayar
                            </p>

                            <p class="mt-1 text-lg font-bold text-emerald-600">

                                Rp {{ number_format($proyek->total_pendapatan,0,',','.') }}

                            </p>

                        </div>

                        <div class="bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 min-w-40">

                            <p class="text-xs font-medium text-slate-500">
                                Freelancer
                            </p>

                            <p class="mt-1 text-lg font-bold text-blue-600">

                                {{ $proyek->total_pengambil }}

                            </p>

                        </div>

                    </div>

                </div>

            </div>

            {{-- CONTENT --}}
            <div
                x-show="openProyek"
                x-transition
                x-cloak
                class="border-t border-slate-100 bg-slate-50 p-5">

                <div class="space-y-4">
                    @foreach($proyek->subproyeks as $sub)

                    @foreach($sub->subsubproyeks as $sss)

                    @php
                    $pengambilAktif = $sss->pengambilans
                    ->whereIn('status', ['diambil', 'menunggu', 'selesai']);
                    @endphp

                    <div class="bg-white rounded-2xl border border-slate-200 p-5 hover:border-brand-300 hover:shadow-md transition">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                            <div>
                                <p class="text-sm font-semibold text-slate-800">
                                    {{ $sub->nama_sub_proyek }}
                                </p>
                                <p class="text-xs font-semibold text-slate-400 mt-1">
                                    {{ $sss->nama_halaman }}
                                </p>
                            </div>

                            <div class="flex flex-wrap gap-2 mt-4">
                                <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs">
                                    {{ $sss->total_halaman }} halaman
                                </span>

                                <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs">
                                    {{ $pengambilAktif->count() }} pengambil
                                </span>

                                <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs">
                                    Rp {{ number_format($pengambilAktif->sum('total_harga'),0,',','.') }}
                                </span>
                            </div>

                            <div>
                                <x-secondary-button
                                    @click.stop="
                                    modalPengambil = true;
                                    detail ={
                                        proyek: '{{ $proyek->nama_proyek }}',
                                        subproyek: '{{ $sub->nama_sub_proyek }}',
                                        nama: '{{ $sss->nama_halaman }}',
                                        kualitas: '{{ $sss->kualitas }}',
                                        total_halaman: {{ $sss->total_halaman }},
                                        harga_perlembar: {{ $sss->harga_perlembar }},
                                        subtotal: {{ $sss->total_halaman * $sss->harga_perlembar }},
                                        pengambilans: {{ Js::from($sss->pengambilans) }}
                                    };">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2" height="20" width="20" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                                        <path fill="currentColor" d="M320 144C254.8 144 201.2 173.6 160.1 211.7C121.6 247.5 95 290 81.4 320C95 350 121.6 392.5 160.1 428.3C201.2 466.4 254.8 496 320 496C385.2 496 438.8 466.4 479.9 428.3C518.4 392.5 545 350 558.6 320C545 290 518.4 247.5 479.9 211.7C438.8 173.6 385.2 144 320 144zM127.4 176.6C174.5 132.8 239.2 96 320 96C400.8 96 465.5 132.8 512.6 176.6C559.4 220.1 590.7 272 605.6 307.7C608.9 315.6 608.9 324.4 605.6 332.3C590.7 368 559.4 420 512.6 463.4C465.5 507.1 400.8 544 320 544C239.2 544 174.5 507.2 127.4 463.4C80.6 419.9 49.3 368 34.4 332.3C31.1 324.4 31.1 315.6 34.4 307.7C49.3 272 80.6 220 127.4 176.6zM320 400C364.2 400 400 364.2 400 320C400 290.4 383.9 264.5 360 250.7C358.6 310.4 310.4 358.6 250.7 360C264.5 383.9 290.4 400 320 400zM240.4 311.6C242.9 311.9 245.4 312 248 312C283.3 312 312 283.3 312 248C312 245.4 311.8 242.9 311.6 240.4C274.2 244.3 244.4 274.1 240.5 311.5zM286 196.6C296.8 193.6 308.2 192.1 319.9 192.1C328.7 192.1 337.4 193 345.7 194.7C346 194.8 346.2 194.8 346.5 194.9C404.4 207.1 447.9 258.6 447.9 320.1C447.9 390.8 390.6 448.1 319.9 448.1C258.3 448.1 206.9 404.6 194.7 346.7C192.9 338.1 191.9 329.2 191.9 320.1C191.9 309.1 193.3 298.3 195.9 288.1C196.1 287.4 196.2 286.8 196.4 286.2C208.3 242.8 242.5 208.6 285.9 196.7z" />
                                    </svg>
                                    Lihat Pengambil
                                </x-secondary-button>
                                {{-- MODAL --}}
                                <x-modals.detail-riwayat-proyek></x-modals.detail-riwayat-proyek>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    @endforeach
                </div>

            </div>

        </div>

        @endforeach

        @empty

        <x-list-empty title="Tidak Ada Riwayat" subtitle="Semua proyek yang telah selesai akan muncul disini">
                <x-slot:icon>
                    <svg class="w-10 h-10 text-gray-400"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M9 8h6m2 12H7a2 2 0 01-2-2V6a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V18a2 2 0 01-2 2z" />
                    </svg>
                </x-slot:icon>
            </x-list-empty>

        @endforelse

    </div>

</div>
@endsection