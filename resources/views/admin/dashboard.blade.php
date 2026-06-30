@extends('layouts.body', ['title' => 'Dashboard'])

@section('content')
<x-header
    :judul="'Dashboard Admin'"
    :subjudul="'Kelola proyek dan monitor aktivitas'" />

<!-- statcard -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <x-stat-card
        title="Total Proyek"
        :value="$totalProyek"
        color="yellow"
        brdr="yellow">
        <x-slot:icon>
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 640 640">
                <path fill="currentColor" d="M128 464L512 464C520.8 464 528 456.8 528 448L528 208C528 199.2 520.8 192 512 192L362.7 192C345.4 192 328.5 186.4 314.7 176L276.3 147.2C273.5 145.1 270.2 144 266.7 144L128 144C119.2 144 112 151.2 112 160L112 448C112 456.8 119.2 464 128 464zM512 512L128 512C92.7 512 64 483.3 64 448L64 160C64 124.7 92.7 96 128 96L266.7 96C280.5 96 294 100.5 305.1 108.8L343.5 137.6C349 141.8 355.8 144 362.7 144L512 144C547.3 144 576 172.7 576 208L576 448C576 483.3 547.3 512 512 512z" />
            </svg>
        </x-slot:icon>
    </x-stat-card>
    <x-stat-card
        title="Proyek Aktif"
        :value="$proyekAktif"
        color="blue"
        brdr="blue">
        <x-slot:icon>
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 640 640">
                <path fill="currentColor" d="M129.5 464L179.5 304L558.9 304L508.9 464L129.5 464zM320.2 512L509 512C530 512 548.6 498.4 554.8 478.3L604.8 318.3C614.5 287.4 591.4 256 559 256L179.6 256C158.6 256 140 269.6 133.8 289.7L112.2 358.4L112.2 160C112.2 151.2 119.4 144 128.2 144L266.9 144C270.4 144 273.7 145.1 276.5 147.2L314.9 176C328.7 186.4 345.6 192 362.9 192L480.2 192C489 192 496.2 199.2 496.2 208L544.2 208C544.2 172.7 515.5 144 480.2 144L362.9 144C356 144 349.2 141.8 343.7 137.6L305.3 108.8C294.2 100.5 280.8 96 266.9 96L128.2 96C92.9 96 64.2 124.7 64.2 160L64.2 448C64.2 483.3 92.9 512 128.2 512L320.2 512z" />
            </svg>
        </x-slot:icon>
    </x-stat-card>
    <x-stat-card
        title="Total Freelancer"
        :value="$freelancer"
        color="red"
        brdr="red">
        <x-slot:icon>
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                <path fill="currentColor" d="M320 80C377.4 80 424 126.6 424 184C424 241.4 377.4 288 320 288C262.6 288 216 241.4 216 184C216 126.6 262.6 80 320 80zM96 152C135.8 152 168 184.2 168 224C168 263.8 135.8 296 96 296C56.2 296 24 263.8 24 224C24 184.2 56.2 152 96 152zM0 480C0 409.3 57.3 352 128 352C140.8 352 153.2 353.9 164.9 357.4C132 394.2 112 442.8 112 496L112 512C112 523.4 114.4 534.2 118.7 544L32 544C14.3 544 0 529.7 0 512L0 480zM521.3 544C525.6 534.2 528 523.4 528 512L528 496C528 442.8 508 394.2 475.1 357.4C486.8 353.9 499.2 352 512 352C582.7 352 640 409.3 640 480L640 512C640 529.7 625.7 544 608 544L521.3 544zM472 224C472 184.2 504.2 152 544 152C583.8 152 616 184.2 616 224C616 263.8 583.8 296 544 296C504.2 296 472 263.8 472 224zM160 496C160 407.6 231.6 336 320 336C408.4 336 480 407.6 480 496L480 512C480 529.7 465.7 544 448 544L192 544C174.3 544 160 529.7 160 512L160 496z" />
            </svg>
        </x-slot:icon>
    </x-stat-card>
    <x-stat-card
        title="Upah Dibayar"
        :value="'Rp. ' . number_format($upah, 0, ',', '.')"
        color="green"
        brdr="green">
        <x-slot:icon>
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 640 640">
                <path fill="currentColor" d="M296 88C296 74.7 306.7 64 320 64C333.3 64 344 74.7 344 88L344 128L400 128C417.7 128 432 142.3 432 160C432 177.7 417.7 192 400 192L285.1 192C260.2 192 240 212.2 240 237.1C240 259.6 256.5 278.6 278.7 281.8L370.3 294.9C424.1 302.6 464 348.6 464 402.9C464 463.2 415.1 512 354.9 512L344 512L344 552C344 565.3 333.3 576 320 576C306.7 576 296 565.3 296 552L296 512L224 512C206.3 512 192 497.7 192 480C192 462.3 206.3 448 224 448L354.9 448C379.8 448 400 427.8 400 402.9C400 380.4 383.5 361.4 361.3 358.2L269.7 345.1C215.9 337.5 176 291.4 176 237.1C176 176.9 224.9 128 285.1 128L296 128L296 88z" />
            </svg>
        </x-slot:icon>
    </x-stat-card>
</div>

<x-anchor link="{{ route('proyek.halaman') }}">
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
    </svg>
    Tambah Proyek Baru</x-anchor>

<div class="bg-white rounded-2xl border border-gray-200 mt-8 shadow-sm">

    <!-- HEADER -->
    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-200 bg-linear-to-r from-brand-500 to-brand-800 rounded-t-2xl">
        <div>
            <h2 class="text-lg font-semibold text-white">
                Daftar Proyek
            </h2>
            <p class="text-sm text-gray-200">
                Kelola semua proyek dalam satu tempat
            </p>
        </div>

        <span class="bg-white px-3 py-1 font-semibold rounded-full text-brand-500">
            {{ $totalProyek }} Proyek Sedang Berjalan
        </span>
    </div>

    <!-- TABLE -->
    <div x-data="{
        openProyek: null,
        openSub: null,
        showModal: false,
        detail:{}
        }" class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-brand-500 text-xs uppercase">
                <tr class="border-b border-gray-200">
                    <th class="px-6 py-3 text-left">Proyek</th>
                    <th class="px-6 py-3 text-center">Mulai</th>
                    <th class="px-6 py-3 text-center">Selesai</th>
                    <th class="px-6 py-3 text-center">Status</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($proyeks as $proyek)
                <!-- ROW -->
                <tr class="hover:bg-gray-50 transition border border-gray-200">

                    <!-- NAMA + EXPAND -->
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3 cursor-pointer"
                            @click="openProyek === @js($proyek->id) ? openProyek=null : openProyek=@js($proyek->id)">

                            <div>
                                <p class="font-semibold text-brand-500">
                                    {{ $proyek->nama_proyek }}
                                </p>
                                <p class="text-xs text-gray-400">
                                    Klik untuk melihat detail
                                </p>
                            </div>

                            <!-- CHEVRON -->
                            <svg class="ml-auto w-4 h-4 transition-transform" :class="openProyek === @js($proyek->id) ? 'rotate-180' : ''"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                                <path d="M297.4 438.6C309.9 451.1 330.2 451.1 342.7 438.6L502.7 278.6C515.2 266.1 515.2 245.8 502.7 233.3C490.2 220.8 469.9 220.8 457.4 233.3L320 370.7L182.6 233.4C170.1 220.9 149.8 220.9 137.3 233.4C124.8 245.9 124.8 266.2 137.3 278.7L297.3 438.7z" />
                            </svg>
                        </div>
                    </td>

                    <td class="px-6 py-4 text-center font-semibold text-blue-600">
                        {{ $proyek->tanggal_mulai->format('d M Y H:i') }}
                    </td>

                    <td class="px-6 py-4 text-center font-semibold text-red-600">
                        {{ $proyek->tanggal_selesai->format('d M Y H:i') }}
                    </td>

                    <td class="px-6 py-4 text-center">
                        <x-status :value="$proyek->status"></x-status>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex justify-center gap-2">

                            <button
                                @click.stop="window.location.href='{{ route('proyek.edit', $proyek->id) }}'"
                                class="px-3 py-1.5 text-xs rounded-lg bg-blue-50 text-blue-500 hover:bg-blue-100 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" height="18" width="18" viewBox="0 0 640 640">
                                    <path fill="currentColor" d="M505 122.9L517.1 135C526.5 144.4 526.5 159.6 517.1 168.9L488 198.1L441.9 152L471 122.9C480.4 113.5 495.6 113.5 504.9 122.9zM273.8 320.2L408 185.9L454.1 232L319.8 366.2C316.9 369.1 313.3 371.2 309.4 372.3L250.9 389L267.6 330.5C268.7 326.6 270.8 323 273.7 320.1zM437.1 89L239.8 286.2C231.1 294.9 224.8 305.6 221.5 317.3L192.9 417.3C190.5 425.7 192.8 434.7 199 440.9C205.2 447.1 214.2 449.4 222.6 447L322.6 418.4C334.4 415 345.1 408.7 353.7 400.1L551 202.9C579.1 174.8 579.1 129.2 551 101.1L538.9 89C510.8 60.9 465.2 60.9 437.1 89zM152 128C103.4 128 64 167.4 64 216L64 488C64 536.6 103.4 576 152 576L424 576C472.6 576 512 536.6 512 488L512 376C512 362.7 501.3 352 488 352C474.7 352 464 362.7 464 376L464 488C464 510.1 446.1 528 424 528L152 528C129.9 528 112 510.1 112 488L112 216C112 193.9 129.9 176 152 176L264 176C277.3 176 288 165.3 288 152C288 138.7 277.3 128 264 128L152 128z" />
                                </svg>
                            </button>

                            <div x-data="{ open:false }">
                                <button @click.stop="open=true"
                                    class="px-3 py-1.5 text-xs rounded-lg bg-red-50 text-red-600 hover:bg-red-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="18" width="18" viewBox="0 0 640 640">
                                        <path fill="currentColor" d="M262.2 48C248.9 48 236.9 56.3 232.2 68.8L216 112L120 112C106.7 112 96 122.7 96 136C96 149.3 106.7 160 120 160L520 160C533.3 160 544 149.3 544 136C544 122.7 533.3 112 520 112L424 112L407.8 68.8C403.1 56.3 391.2 48 377.8 48L262.2 48zM128 208L128 512C128 547.3 156.7 576 192 576L448 576C483.3 576 512 547.3 512 512L512 208L464 208L464 512C464 520.8 456.8 528 448 528L192 528C183.2 528 176 520.8 176 512L176 208L128 208zM288 280C288 266.7 277.3 256 264 256C250.7 256 240 266.7 240 280L240 456C240 469.3 250.7 480 264 480C277.3 480 288 469.3 288 456L288 280zM400 280C400 266.7 389.3 256 376 256C362.7 256 352 266.7 352 280L352 456C352 469.3 362.7 480 376 480C389.3 480 400 469.3 400 456L400 280z" />
                                    </svg>
                                </button>

                                <div x-show="open"
                                    x-transition
                                    class="fixed inset-0 flex items-center justify-center bg-black/40 backdrop-blur-sm z-50">

                                    <div class="bg-white rounded-xl p-6 w-full max-w-md shadow-lg">
                                        <h2 class="text-lg font-semibold mb-2">
                                            Hapus Proyek
                                        </h2>

                                        <p class="text-sm text-gray-500 mb-6">
                                            Data yang dihapus tidak bisa dikembalikan.
                                        </p>

                                        <div class="flex justify-end gap-3">
                                            <button @click="open=false"
                                                class="px-4 py-2 text-sm border border-gray-200 rounded-lg">
                                                Batal
                                            </button>

                                            <form method="POST" action="{{ route('proyek.hapus', $proyek->id) }}">
                                                @csrf
                                                <button class="px-4 py-2 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </td>
                </tr>

                <!-- EXPAND -->
                <tr x-show="openProyek === @js($proyek->id)" x-transition>
                    <td colspan="5" class="bg-gray-50 px-6 py-5 border-b border-gray-200">
                        <div class="space-y-3">
                            @foreach($proyek->subproyeks as $sub)

                            <div
                                class="bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow-md transition overflow-hidden"
                                x-data="{ open: false }">

                                <!-- HEADER -->
                                <button
                                    @click="open = !open"
                                    class="w-full flex items-center justify-between p-4 text-left hover:bg-slate-50 transition">

                                    <div class="space-y-1">
                                        <p class="font-semibold text-slate-800">
                                            {{ $sub->nama_sub_proyek }}
                                        </p>
                                        <p class="text-xs text-slate-500">
                                            {{ $sub->total_halaman }} halaman
                                        </p>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <span class="text-[11px] px-2 py-1 rounded-full bg-emerald-50 text-emerald-600">
                                            File
                                        </span>

                                        <svg :class="open ? 'rotate-180' : ''"
                                            class="w-4 h-4 text-slate-400 transition-transform"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </button>

                                <!-- CONTENT -->
                                <div x-show="open" class="px-4 pb-4">

                                    <div class="grid gap-3">

                                        @foreach($sub->subsubproyeks as $sss)

                                        <div class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white hover:border-indigo-200 hover:shadow-lg transition-all duration-300">

                                            <!-- Accent -->
                                            <div class="absolute left-0 top-0 h-full w-1 bg-linear-to-r from-brand-500 to-brand-700"></div>

                                            <div class="p-5">

                                                <!-- Header -->
                                                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">

                                                    <div class="flex items-start gap-3">

                                                        <div class="w-11 h-11 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                                                                <path fill="currentColor" d="M129.5 464L179.5 304L558.9 304L508.9 464L129.5 464zM320.2 512L509 512C530 512 548.6 498.4 554.8 478.3L604.8 318.3C614.5 287.4 591.4 256 559 256L179.6 256C158.6 256 140 269.6 133.8 289.7L112.2 358.4L112.2 160C112.2 151.2 119.4 144 128.2 144L266.9 144C270.4 144 273.7 145.1 276.5 147.2L314.9 176C328.7 186.4 345.6 192 362.9 192L480.2 192C489 192 496.2 199.2 496.2 208L544.2 208C544.2 172.7 515.5 144 480.2 144L362.9 144C356 144 349.2 141.8 343.7 137.6L305.3 108.8C294.2 100.5 280.8 96 266.9 96L128.2 96C92.9 96 64.2 124.7 64.2 160L64.2 448C64.2 483.3 92.9 512 128.2 512L320.2 512z" />
                                                            </svg>

                                                        </div>

                                                        <div>
                                                            <h4 class="font-semibold text-slate-800">
                                                                {{ $sss->nama_halaman }}
                                                            </h4>

                                                            <p class="text-sm text-slate-500 mt-1">
                                                                Detail halaman proyek
                                                            </p>
                                                        </div>

                                                    </div>

                                                    <!-- Kualitas -->
                                                    <div x-data="{ kualitas: '{{ $sss->kualitas }}' }">
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border"
                                                            :class="{
                                                            'bg-yellow-100 text-yellow-800 border-yellow-200' : kualitas === '2',
                                                            'bg-orange-100 text-orange-800 border-orange-200' : kualitas === '4',
                                                            'bg-green-100 text-green-800 border-green-200' : kualitas === '6',
                                                            'bg-cyan-100 text-cyan-800 border-cyan-200' : kualitas === '8',
                                                            'bg-blue-100 text-blue-800 border-blue-200' : kualitas === '10',
                                                        }">

                                                            @if ($sss->kualitas == 2)
                                                            Sangat Kurang
                                                            @elseif ($sss->kualitas == 4)
                                                            Kurang
                                                            @elseif ($sss->kualitas == 6)
                                                            Sedang
                                                            @elseif ($sss->kualitas == 8)
                                                            Baik
                                                            @elseif ($sss->kualitas == 10)
                                                            Sangat Baik
                                                            @endif
                                                        </span>
                                                    </div>

                                                </div>

                                                <!-- Stats -->
                                                <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mt-5">

                                                    <div class="rounded-xl bg-slate-50 p-3">
                                                        <p class="text-xs text-slate-500">
                                                            Harga/Lembar
                                                        </p>

                                                        <p class="font-bold text-slate-800 mt-1">
                                                            Rp {{ number_format($sss->harga_perlembar,0,',','.') }}
                                                        </p>
                                                    </div>

                                                    <div class="rounded-xl bg-slate-50 p-3">
                                                        <p class="text-xs text-slate-500">
                                                            Total Halaman
                                                        </p>

                                                        <p class="font-bold text-slate-800 mt-1">
                                                            {{ $sss->total_halaman }}
                                                        </p>
                                                    </div>

                                                    <div class="rounded-xl bg-emerald-50 p-3 col-span-2 md:col-span-1">
                                                        <p class="text-xs text-emerald-600">
                                                            Subtotal
                                                        </p>

                                                        <p class="font-bold text-emerald-700 mt-1">
                                                            Rp {{ number_format($sss->total_halaman * $sss->harga_perlembar,0,',','.') }}
                                                        </p>
                                                    </div>

                                                </div>

                                                <!-- Action -->
                                                <div class="mt-5 pt-4 border-t border-slate-100 flex flex-wrap gap-2 justify-between">

                                                    <a href="{{ asset('storage/'.$sss->file_pdf) }}"
                                                        target="_blank"
                                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white border border-red-200 transition font-medium text-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                                                            <path fill="currentColor" d="M240 112L128 112C119.2 112 112 119.2 112 128L112 512C112 520.8 119.2 528 128 528L208 528L208 576L128 576C92.7 576 64 547.3 64 512L64 128C64 92.7 92.7 64 128 64L261.5 64C278.5 64 294.8 70.7 306.8 82.7L429.3 205.3C441.3 217.3 448 233.6 448 250.6L448 400.1L400 400.1L400 272.1L312 272.1C272.2 272.1 240 239.9 240 200.1L240 112.1zM380.1 224L288 131.9L288 200C288 213.3 298.7 224 312 224L380.1 224zM272 444L304 444C337.1 444 364 470.9 364 504C364 537.1 337.1 564 304 564L292 564L292 592C292 603 283 612 272 612C261 612 252 603 252 592L252 464C252 453 261 444 272 444zM304 524C315 524 324 515 324 504C324 493 315 484 304 484L292 484L292 524L304 524zM400 444L432 444C460.7 444 484 467.3 484 496L484 560C484 588.7 460.7 612 432 612L400 612C389 612 380 603 380 592L380 464C380 453 389 444 400 444zM432 572C438.6 572 444 566.6 444 560L444 496C444 489.4 438.6 484 432 484L420 484L420 572L432 572zM508 464C508 453 517 444 528 444L576 444C587 444 596 453 596 464C596 475 587 484 576 484L548 484L548 508L576 508C587 508 596 517 596 528C596 539 587 548 576 548L548 548L548 592C548 603 539 612 528 612C517 612 508 603 508 592L508 464z" />
                                                        </svg>
                                                        PDF
                                                    </a>

                                                    <a href="{{ asset('storage/'.$sss->file_xls) }}"
                                                        target="_blank"
                                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-50 text-emerald-600 hover:bg-green-600 hover:text-white border border-green-200 transition font-medium text-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                                                            <path fill="currentColor" d="M192 112L304 112L304 200C304 239.8 336.2 272 376 272L464 272L464 512C464 520.8 456.8 528 448 528L192 528C183.2 528 176 520.8 176 512L176 128C176 119.2 183.2 112 192 112zM352 131.9L444.1 224L376 224C362.7 224 352 213.3 352 200L352 131.9zM192 64C156.7 64 128 92.7 128 128L128 512C128 547.3 156.7 576 192 576L448 576C483.3 576 512 547.3 512 512L512 250.5C512 233.5 505.3 217.2 493.3 205.2L370.7 82.7C358.7 70.7 342.5 64 325.5 64L192 64zM291.2 329.6C283.2 319 268.2 316.8 257.6 324.8C247 332.8 244.8 347.8 252.8 358.4L290 408L252.8 457.6C244.8 468.2 247 483.2 257.6 491.2C268.2 499.2 283.2 497 291.2 486.4L320 448L348.8 486.4C356.8 497 371.8 499.2 382.4 491.2C393 483.2 395.2 468.2 387.2 457.6L350 408L387.2 358.4C395.2 347.8 393 332.8 382.4 324.8C371.8 316.8 356.8 319 348.8 329.6L320 368L291.2 329.6z" />
                                                        </svg>
                                                        XLS
                                                    </a>

                                                    <x-secondary-button @click.stop="
                                                    detail = {
                                                        proyek: '{{ $proyek->nama_proyek }}',
                                                        subproyek: '{{ $sub->nama_sub_proyek }}',
                                                        nama: '{{ $sss->nama_halaman }}',
                                                        kualitas: '{{ $sss->kualitas }}',
                                                        total_halaman: {{ $sss->total_halaman }},
                                                        harga_perlembar: {{ $sss->harga_perlembar }},
                                                        subtotal: {{ $sss->total_halaman * $sss->harga_perlembar }},
                                                        pengambilans: {{ Js::from($sss->pengambilans) }}
                                                    };
                                                    showModal = true">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2" height="20" width="20" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                                                            <path fill="currentColor" d="M320 144C254.8 144 201.2 173.6 160.1 211.7C121.6 247.5 95 290 81.4 320C95 350 121.6 392.5 160.1 428.3C201.2 466.4 254.8 496 320 496C385.2 496 438.8 466.4 479.9 428.3C518.4 392.5 545 350 558.6 320C545 290 518.4 247.5 479.9 211.7C438.8 173.6 385.2 144 320 144zM127.4 176.6C174.5 132.8 239.2 96 320 96C400.8 96 465.5 132.8 512.6 176.6C559.4 220.1 590.7 272 605.6 307.7C608.9 315.6 608.9 324.4 605.6 332.3C590.7 368 559.4 420 512.6 463.4C465.5 507.1 400.8 544 320 544C239.2 544 174.5 507.2 127.4 463.4C80.6 419.9 49.3 368 34.4 332.3C31.1 324.4 31.1 315.6 34.4 307.7C49.3 272 80.6 220 127.4 176.6zM320 400C364.2 400 400 364.2 400 320C400 290.4 383.9 264.5 360 250.7C358.6 310.4 310.4 358.6 250.7 360C264.5 383.9 290.4 400 320 400zM240.4 311.6C242.9 311.9 245.4 312 248 312C283.3 312 312 283.3 312 248C312 245.4 311.8 242.9 311.6 240.4C274.2 244.3 244.4 274.1 240.5 311.5zM286 196.6C296.8 193.6 308.2 192.1 319.9 192.1C328.7 192.1 337.4 193 345.7 194.7C346 194.8 346.2 194.8 346.5 194.9C404.4 207.1 447.9 258.6 447.9 320.1C447.9 390.8 390.6 448.1 319.9 448.1C258.3 448.1 206.9 404.6 194.7 346.7C192.9 338.1 191.9 329.2 191.9 320.1C191.9 309.1 193.3 298.3 195.9 288.1C196.1 287.4 196.2 286.8 196.4 286.2C208.3 242.8 242.5 208.6 285.9 196.7z" />
                                                        </svg>
                                                        Lihat Detail
                                                    </x-secondary-button>

                                                    <x-modals.detail-dashboard-pengambil></x-modals.detail-dashboard-pengambil>


                                                </div>

                                            </div>

                                        </div>

                                        @endforeach

                                    </div>

                                </div>

                            </div>

                            @endforeach
                        </div>
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="5">
                        <x-list-empty title="Tidak ada proyek aktif" subtitle="Tambahkan proyek baru untuk ditampilkan">
                            <x-slot:icon>
                                <svg class="w-10 h-10 text-gray-400"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M9 8h6m2 12H7a2 2 0 01-2-2V6a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V18a2 2 0 01-2 2z" />
                                </svg>
                            </x-slot:icon>
                        </x-list-empty>
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>
    </div>
</div>
<script>
    function proyekTable() {
        return {

            openProyek: null,

            async editProyek(id) {

                let response = await fetch(`/proyek/${id}`)
                let data = await response.json()

                // misalnya buka modal edit
                this.editData = data
                this.showEditModal = true
            },

            async hapusProyek(id) {

                if (!confirm('Yakin ingin menghapus proyek ini?')) return

                try {

                    let response = await fetch(`/admin-dashboard/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            'Accept': 'application/json'
                        }
                    })

                    console.log(response)

                    let result = await response.json()

                    console.log(result)

                } catch (error) {
                    console.error(error)
                }

            }
        }
    }
</script>
@endsection