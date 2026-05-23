@extends('layouts.body', ['title' => 'Pembayaran'])

@section('content')
<x-header
    :judul="'Pembayaran'"
    :subjudul="'Kelola pembayaran upah freelancer'" />

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <x-stat-card
        title="Belum Dibayar"
        :value="$belumDibayar"
        color="orange" />
    <x-stat-card
        title="Sudah Dibayar"
        :value="$sudahDibayar"
        color="green" />
    <x-stat-card
        title="Total Pembayaran"
        :value="'Rp. ' . number_format($pengupahan, 0, ',', '.')"
        color="blue">
    </x-stat-card>
</div>
<div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        {{-- Search --}}
        <div class="relative">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"
                viewBox="0 0 640 640" fill="currentColor">
                <path d="M480 272C480 317.9 465.1 360.3 440 394.7L566.6 521.4C579.1 533.9 579.1 554.2 566.6 566.7C554.1 579.2 533.8 579.2 521.3 566.7L394.7 440C360.3 465.1 317.9 480 272 480C157.1 480 64 386.9 64 272C64 157.1 157.1 64 272 64C386.9 64 480 157.1 480 272zM272 416C351.5 416 416 351.5 416 272C416 192.5 351.5 128 272 128C192.5 128 128 192.5 128 272C128 351.5 192.5 416 272 416z" />
            </svg>

            <input
                type="text"
                placeholder="Cari proyek..."
                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
        </div>

        {{-- Filter Harga --}}
        <div class="relative">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"
                viewBox="0 0 640 640" fill="currentColor">
                <path d="M96 128C83.1 128 71.4 135.8 66.4 147.8C61.4 159.8 64.2 173.5 73.4 182.6L256 365.3L256 480C256 488.5 259.4 496.6 265.4 502.6L329.4 566.6C338.6 575.8 352.3 578.5 364.3 573.5C376.3 568.5 384 556.9 384 544L384 365.3L566.6 182.7C575.8 173.5 578.5 159.8 573.5 147.8C568.5 135.8 556.9 128 544 128L96 128z" />
            </svg>

            <select
                class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent appearance-none bg-white">
                <option value="all">Semua Harga</option>
                <option value="low">&lt; Rp 1.000</option>
                <option value="medium">Rp 1.000 - Rp 3.000</option>
                <option value="high">&gt; Rp 3.000</option>
            </select>

            {{-- Icon panah dropdown --}}
            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 9l-7 7-7-7" />
            </svg>
        </div>

    </div>
</div>
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-gray-900 font-semibold">Daftar Pembayaran</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-gray-500">Nama Freelancer</th>
                    <th class="px-6 py-3 text-left text-gray-500">Nama Bank</th>
                    <th class="px-6 py-3 text-left text-gray-500">No Rekening</th>
                    <th class="px-6 py-3 text-left text-gray-500">Hasil Tugas</th>
                    <th class="px-6 py-3 text-left text-gray-500">Pembayaran</th>
                    <th class="px-6 py-3 text-left text-gray-500">Status</th>
                    <th class="px-6 py-3 text-left text-gray-500">Aksi</th>
                </tr>
            </thead>
            <tbody
                x-data="{
                            semuaPembayaran : @js($pembayarans),
                            modalUser : false,
                            modalDetail : false,
                            pilihUser : null,
                            pilihDetail : null,
                            paymentProof : null, 
                            riwayatPembayaran : []}" class="divide-y divide-gray-200">
                @forelse ($pembayarans as $pembayaran)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <button @click="
                                    modalUser = true;
                                    pilihUser = {{ json_encode($pembayaran) }};
                                    riwayatPembayaran = semuaPembayaran.filter(p => p.user.id === pilihUser.user.id);"
                            class="text-brand-500 hover:underline cursor-pointer">
                            {{ $pembayaran['user']['name'] }}
                        </button>
                        <div x-show="modalUser" x-transition
                            class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 p-4">

                            <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl max-h-[90vh] flex flex-col overflow-hidden">

                                <!-- HEADER -->
                                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-brand-500 text-white rounded-full flex items-center justify-center text-lg font-semibold">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="30" width="30" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                                                <path fill="currentColor" d="M240 192C240 147.8 275.8 112 320 112C364.2 112 400 147.8 400 192C400 236.2 364.2 272 320 272C275.8 272 240 236.2 240 192zM448 192C448 121.3 390.7 64 320 64C249.3 64 192 121.3 192 192C192 262.7 249.3 320 320 320C390.7 320 448 262.7 448 192zM144 544C144 473.3 201.3 416 272 416L368 416C438.7 416 496 473.3 496 544L496 552C496 565.3 506.7 576 520 576C533.3 576 544 565.3 544 552L544 544C544 446.8 465.2 368 368 368L272 368C174.8 368 96 446.8 96 544L96 552C96 565.3 106.7 576 120 576C133.3 576 144 565.3 144 552L144 544z" />
                                            </svg>

                                        </div>
                                        <div>
                                            <h2 class="text-gray-900 font-semibold text-base" x-text="pilihUser?.user?.name ?? '-'"></h2>
                                            <p class="text-gray-500 text-xs" x-text="pilihUser?.user?.email ?? '-'"></p>
                                        </div>
                                    </div>

                                    <button @click="modalUser = false"
                                        class="text-gray-400 hover:text-gray-700 text-lg">
                                        ✕
                                    </button>
                                </div>

                                <!-- CONTENT -->
                                <div class="p-6 space-y-6 overflow-y-auto text-sm">

                                    <!-- INFO GRID -->
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">

                                        <div>
                                            <p class="text-gray-400">Level</p>
                                            <p class="font-medium text-gray-800"
                                                x-text="pilihUser?.level?.nama_level ? 'Level ' + pilihUser.level.nama_level : '-'"></p>
                                        </div>

                                        <div>
                                            <p class="text-gray-400">Total Poin</p>
                                            <p class="font-medium text-gray-800"
                                                x-text="pilihUser?.user?.poin_level ?? '-'"></p>
                                        </div>

                                        <div>
                                            <p class="text-gray-400">Status</p>
                                            <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold"
                                                :class="{
                                                                'bg-green-100 text-green-700': pilihUser?.user?.status === 'aktif',
                                                                'bg-red-100 text-red-700': pilihUser?.user?.status === 'nonaktif'
                                                            }"
                                                x-text="pilihUser?.user?.status ?? '-'">
                                            </span>
                                        </div>

                                    </div>

                                    <!-- BANK -->
                                    <div>
                                        <h3 class="text-gray-900 mb-4 flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                                                <path d="M512 176C520.8 176 528 183.2 528 192L528 224L112 224L112 192C112 183.2 119.2 176 128 176L512 176zM528 288L528 448C528 456.8 520.8 464 512 464L128 464C119.2 464 112 456.8 112 448L112 288L528 288zM128 128C92.7 128 64 156.7 64 192L64 448C64 483.3 92.7 512 128 512L512 512C547.3 512 576 483.3 576 448L576 192C576 156.7 547.3 128 512 128L128 128zM144 408C144 421.3 154.7 432 168 432L216 432C229.3 432 240 421.3 240 408C240 394.7 229.3 384 216 384L168 384C154.7 384 144 394.7 144 408zM288 408C288 421.3 298.7 432 312 432L376 432C389.3 432 400 421.3 400 408C400 394.7 389.3 384 376 384L312 384C298.7 384 288 394.7 288 408z" />
                                            </svg>
                                            Informasi Bank
                                        </h3>
                                        <div class="border border-gray-200 rounded-xl p-4 shadow">



                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <p class="text-gray-400">Bank</p>
                                                    <p class="font-medium text-gray-800" x-text="pilihUser?.rekening?.nama_bank ?? '-'"></p>
                                                </div>
                                                <div>
                                                    <p class="text-gray-400">No Rekening</p>
                                                    <p class="font-medium text-gray-800" x-text="pilihUser?.rekening?.no_akun ?? '-'"></p>
                                                </div>
                                                <div class="col-span-2">
                                                    <p class="text-gray-400">Nama</p>
                                                    <p class="font-medium text-gray-800" x-text="pilihUser?.rekening?.nama_akun ?? '-'"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- PORTOFOLIO -->
                                    <div>
                                        <h3 class="text-gray-900 mb-4 flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                                                <path d="M128 128C128 92.7 156.7 64 192 64L448 64C483.3 64 512 92.7 512 128L512 512C512 547.3 483.3 576 448 576L192 576C156.7 576 128 547.3 128 512L128 128zM208 432C208 440.8 215.2 448 224 448L416 448C424.8 448 432 440.8 432 432C432 387.8 396.2 352 352 352L288 352C243.8 352 208 387.8 208 432zM320 312C350.9 312 376 286.9 376 256C376 225.1 350.9 200 320 200C289.1 200 264 225.1 264 256C264 286.9 289.1 312 320 312z" />
                                            </svg>
                                            Portfolio
                                        </h3>
                                        <div class="border border-gray-200 rounded-xl p-4 shadow">
                                            <template x-if="!pilihUser?.portofolio">
                                                <p class="text-gray-400 italic">Tidak ada</p>
                                            </template>

                                            <template x-if="pilihUser?.portofolio">
                                                <div class="flex items-center justify-between border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition">

                                                    <div class="truncate text-gray-700">
                                                        <span x-text="pilihUser.portofolio.type === 'file' 
                                                                ? '📄 ' + pilihUser.portofolio.file_path 
                                                                : '🔗 ' + pilihUser.portofolio.link_url">
                                                        </span>
                                                    </div>

                                                    <a :href="pilihUser.portofolio.type === 'file' 
                                                                ? '/storage/' + pilihUser.portofolio.file_path 
                                                                : pilihUser.portofolio.link_url"
                                                        target="_blank"
                                                        class="text-xs px-3 py-1 border border-gray-200 rounded-lg hover:bg-gray-100">
                                                        Buka
                                                    </a>
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    <!-- RIWAYAT -->
                                    <div>
                                        <h3 class="text-gray-900 mb-4 flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                                                <path d="M320 128C426 128 512 214 512 320C512 426 426 512 320 512C254.8 512 197.1 479.5 162.4 429.7C152.3 415.2 132.3 411.7 117.8 421.8C103.3 431.9 99.8 451.9 109.9 466.4C156.1 532.6 233 576 320 576C461.4 576 576 461.4 576 320C576 178.6 461.4 64 320 64C234.3 64 158.5 106.1 112 170.7L112 144C112 126.3 97.7 112 80 112C62.3 112 48 126.3 48 144L48 256C48 273.7 62.3 288 80 288L104.6 288C105.1 288 105.6 288 106.1 288L192.1 288C209.8 288 224.1 273.7 224.1 256C224.1 238.3 209.8 224 192.1 224L153.8 224C186.9 166.6 249 128 320 128zM344 216C344 202.7 333.3 192 320 192C306.7 192 296 202.7 296 216L296 320C296 326.4 298.5 332.5 303 337L375 409C384.4 418.4 399.6 418.4 408.9 409C418.2 399.6 418.3 384.4 408.9 375.1L343.9 310.1L343.9 216z" />
                                            </svg>
                                            Riwayat Pekerjaan
                                        </h3>


                                        <div class="space-y-3">
                                            <div class="border border-gray-200 rounded-lg p-3 shadow">
                                                <div class="overflow-x-auto">
                                                    <table class="w-full">
                                                        <thead class="bg-white">
                                                            <tr>
                                                                <th class="px-4 py-2 text-left text-gray-500 text-sm">Nama Proyek</th>
                                                                <th class="px-4 py-2 text-left text-gray-500 text-sm">Rentang Halaman</th>
                                                                <th class="px-4 py-2 text-left text-gray-500 text-sm">Total Upah</th>
                                                                <th class="px-4 py-2 text-center text-gray-500 text-sm">Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="divide-y divide-gray-200">
                                                            <template x-if="riwayatPembayaran.length">
                                                                <template x-for="item in riwayatPembayaran" :key="item.id">
                                                                    <tr class="bg-white">
                                                                        <td class="px-4 py-3">
                                                                            <p class="text-gray-900 text-sm" x-text="item.nama_proyek"></p>
                                                                            <p class="text-gray-500 text-xs" x-text="item.nama_sub_proyek"></p>
                                                                        </td>
                                                                        <td class="px-4 py-3 text-gray-500 text-sm">
                                                                            <span x-text="item.dari_halaman"></span> - <span x-text="item.sampai_halaman"></span>
                                                                        </td>
                                                                        <td class="px-4 py-3 text-gray-900 text-sm" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(item.total_pembayaran)"></td>
                                                                        <td class="text-center px-4 py-3">
                                                                            <span class="inline-flex px-2 py-1 rounded-full text-xs"
                                                                                :class="{
                                                                                'bg-green-100 text-green-700' : item.status === 'sudah_dibayar',
                                                                                'bg-red-100 text-red-700' : item.status === 'belum_dibayar'
                                                                                }" x-text="item.status ? item.status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) : '-'"></span>
                                                                        </td>
                                                                    </tr>
                                                                </template>
                                                            </template>
                                                            <template x-if="!riwayatPembayaran.length">
                                                                <tr>
                                                                    <td colspan="4" class="px-4 py-6 text-center text-gray-500 text-sm">
                                                                        Belum ada riwayat pekerjaan
                                                                    </td>
                                                                </tr>
                                                            </template>
                                                        </tbody>

                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <!-- FOOTER -->
                                <div class="p-4 border-t border-gray-200">
                                    <button @click="modalUser = false"
                                        class="w-full py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-sm">
                                        Tutup
                                    </button>
                                </div>

                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $pembayaran['rekening']['nama_bank'] ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $pembayaran['rekening']['no_akun'] ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <button class="text-brand-500 hover:underline text-sm">
                            Lihat File
                        </button>
                    </td>
                    <td class="px-6 py-4 text-gray-900">
                        Rp {{ number_format($pembayaran['total_pembayaran'], 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4">
                        <x-status :value="$pembayaran['status']"></x-status>
                    </td>
                    <td class=" px-6 py-4">
                        <button @click="
                                    modalDetail = true;
                                    pilihDetail = {{ json_encode($pembayaran) }};
                                    "
                            class="flex items-center gap-2 px-3 py-2 text-brand-500 hover:bg-blue-50 rounded-lg transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                                <path fill="currentColor" d="M320 144C254.8 144 201.2 173.6 160.1 211.7C121.6 247.5 95 290 81.4 320C95 350 121.6 392.5 160.1 428.3C201.2 466.4 254.8 496 320 496C385.2 496 438.8 466.4 479.9 428.3C518.4 392.5 545 350 558.6 320C545 290 518.4 247.5 479.9 211.7C438.8 173.6 385.2 144 320 144zM127.4 176.6C174.5 132.8 239.2 96 320 96C400.8 96 465.5 132.8 512.6 176.6C559.4 220.1 590.7 272 605.6 307.7C608.9 315.6 608.9 324.4 605.6 332.3C590.7 368 559.4 420 512.6 463.4C465.5 507.1 400.8 544 320 544C239.2 544 174.5 507.2 127.4 463.4C80.6 419.9 49.3 368 34.4 332.3C31.1 324.4 31.1 315.6 34.4 307.7C49.3 272 80.6 220 127.4 176.6zM320 400C364.2 400 400 364.2 400 320C400 290.4 383.9 264.5 360 250.7C358.6 310.4 310.4 358.6 250.7 360C264.5 383.9 290.4 400 320 400zM240.4 311.6C242.9 311.9 245.4 312 248 312C283.3 312 312 283.3 312 248C312 245.4 311.8 242.9 311.6 240.4C274.2 244.3 244.4 274.1 240.5 311.5zM286 196.6C296.8 193.6 308.2 192.1 319.9 192.1C328.7 192.1 337.4 193 345.7 194.7C346 194.8 346.2 194.8 346.5 194.9C404.4 207.1 447.9 258.6 447.9 320.1C447.9 390.8 390.6 448.1 319.9 448.1C258.3 448.1 206.9 404.6 194.7 346.7C192.9 338.1 191.9 329.2 191.9 320.1C191.9 309.1 193.3 298.3 195.9 288.1C196.1 287.4 196.2 286.8 196.4 286.2C208.3 242.8 242.5 208.6 285.9 196.7z" />
                            </svg>
                        </button>

                        <div x-show="modalDetail"
                            x-transition.opacity
                            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
                            x-cloak>
                            <div
                                class="relative w-full max-w-5xl max-h-[90vh] overflow-hidden rounded-2xl bg-white shadow-2xl">
                                <!-- Header -->
                                <div class="sticky top-0 z-20 flex items-start justify-between border-b border-gray-200 bg-white px-6 py-5">
                                    <div>
                                        <h2 class="text-xl font-semibold text-gray-900">Detail Pembayaran</h2>
                                        <p class="mt-1 text-sm text-gray-500">
                                            Tinjau detail pembayaran freelancer, informasi proyek, dan bukti transfer.
                                        </p>
                                    </div>

                                    <button
                                        @click="modalDetail = false"
                                        class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 text-gray-500 transition hover:bg-gray-100 hover:text-gray-700"
                                        type="button">
                                        ✕
                                    </button>
                                </div>

                                <!-- Content -->
                                <div class="max-h-[calc(90vh-145px)] overflow-y-auto px-6 py-6 bg-gray-50">

                                    <!-- Summary -->
                                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 mb-6">
                                        <!-- Total Pembayaran -->
                                        <div class="rounded-2xl border border-blue-100 bg-blue-50 p-5 shadow-sm">
                                            <p class="text-sm font-medium text-brand-500">Total Pembayaran</p>
                                            <p
                                                class="mt-2 text-2xl font-bold text-brand-500"
                                                x-text="pilihDetail?.total_pembayaran 
                                                            ? new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(pilihDetail.total_pembayaran) 
                                                            : '-'"></p>
                                        </div>

                                        <!-- Status -->
                                        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                                            <p class="text-sm font-medium text-gray-500">Status Pembayaran</p>
                                            <div class="mt-3">
                                                <span
                                                    class="inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-semibold"
                                                    :class="{
                                                                    'bg-green-100 text-green-700 ring-1 ring-green-200': pilihDetail?.status === 'sudah_dibayar',
                                                                    'bg-red-100 text-red-700 ring-1 ring-red-200': pilihDetail?.status === 'belum_dibayar',
                                                                }"
                                                    x-text="pilihDetail?.status 
                                                                    ? pilihDetail.status.replaceAll('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) 
                                                                    : '-'">
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Main Detail Grid -->
                                    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">

                                        <!-- Left Content -->
                                        <div class="xl:col-span-2 space-y-6">

                                            <!-- Informasi Freelancer -->
                                            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                                                <h3 class="text-base font-semibold text-gray-900 mb-5">Informasi Freelancer</h3>

                                                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-500">Nama Freelancer</p>
                                                        <p class="mt-1 text-sm text-gray-900" x-text="pilihDetail?.user?.name ?? '-'"></p>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-500">Level Freelancer</p>
                                                        <p class="mt-1 text-sm text-gray-900" x-text="pilihDetail?.level?.nama_level ?? '-'"></p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Informasi Bank -->
                                            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                                                <h3 class="text-base font-semibold text-gray-900 mb-5">Informasi Rekening</h3>

                                                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-500">Nama Bank</p>
                                                        <p class="mt-1 text-sm text-gray-900" x-text="pilihDetail?.rekening?.nama_bank ?? '-'"></p>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-500">Nomor Rekening</p>
                                                        <p class="mt-1 text-sm text-gray-900 break-all" x-text="pilihDetail?.rekening?.no_akun ?? '-'"></p>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-500">Nama Pemilik Rekening</p>
                                                        <p class="mt-1 text-sm text-gray-900" x-text="pilihDetail?.rekening?.nama_akun ?? '-'"></p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Informasi Proyek -->
                                            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                                                <h3 class="text-base font-semibold text-gray-900 mb-5">Informasi Proyek</h3>

                                                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-500">Nama Proyek</p>
                                                        <p class="mt-1 text-sm text-gray-900" x-text="pilihDetail?.nama_proyek ?? '-'"></p>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-500">Sub Proyek</p>
                                                        <p class="mt-1 text-sm text-gray-900" x-text="pilihDetail?.nama_sub_proyek ?? '-'"></p>
                                                    </div>
                                                    <div class="md:col-span-2">
                                                        <p class="text-sm font-medium text-gray-500">Sub Sub Proyek</p>

                                                        <div class="mt-1 text-sm text-gray-900 flex items-center">
                                                            <p x-text="pilihDetail?.nama_halaman ?? '-'"></p>
                                                            <p class="text-gray-500">
                                                                (
                                                                <span x-text="pilihDetail?.pengambilan?.dari_halaman ?? '-'"></span>
                                                                -
                                                                <span x-text="pilihDetail?.pengambilan?.sampai_halaman ?? '-'"></span>
                                                                )
                                                                Halaman
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- File Tugas -->
                                            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                                                <h3 class="text-base font-semibold text-gray-900 mb-4">File Hasil Pekerjaan</h3>

                                                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between rounded-xl border border-dashed border-gray-300 bg-gray-50 p-4">
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-800">File tugas freelancer</p>
                                                        <p class="text-sm text-gray-500">Unduh file hasil pekerjaan untuk verifikasi.</p>
                                                    </div>

                                                    <a :href="pilihDetail?.pengambilan?.id ? `/pembayaran/${pilihDetail.pengambilan.id}/download/hasil` : '#'"
                                                        target="_blank"
                                                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-100"
                                                        :class="{ 'pointer-events-none opacity-50': !pilihDetail?.pengambilan?.id }">
                                                        Download File
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Right Sidebar -->
                                        <div class="space-y-6">

                                            <!-- Upload / Bukti Transfer -->
                                            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                                                <h3 class="text-base font-semibold text-gray-900 mb-4">Bukti Transfer</h3>

                                                <!-- Jika belum ada bukti transfer -->
                                                <template x-if="!pilihDetail?.bukti_transfer">
                                                    <form action="{{ route('pembayaran.update') }}" method="post" enctype="multipart/form-data" class="space-y-4">
                                                        @csrf
                                                        <input type="hidden" name="id" :value="pilihDetail?.id">

                                                        <div>
                                                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                                                Upload bukti transfer
                                                            </label>
                                                            <input
                                                                type="file"
                                                                name="bukti_transfer"
                                                                accept="image/*"
                                                                @change="
                                                                                let file = $event.target.files[0];
                                                                                if (file) {
                                                                                    paymentProof = URL.createObjectURL(file);
                                                                                }
                                                                            "
                                                                class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 file:mr-4 file:rounded-md file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-brand-500 hover:file:bg-blue-100">
                                                        </div>

                                                        <template x-if="paymentProof">
                                                            <div class="rounded-xl border border-gray-200 bg-gray-50 p-3">
                                                                <p class="mb-2 text-sm font-medium text-gray-700">Preview</p>
                                                                <img :src="paymentProof" class="max-h-72 w-full rounded-lg border object-contain bg-white">
                                                            </div>
                                                        </template>

                                                        <button
                                                            type="submit"
                                                            class="inline-flex w-full items-center justify-center rounded-lg bg-brand-500 px-4 py-3 text-sm font-semibold text-white transition hover:bg-brand-500">
                                                            Konfirmasi Pembayaran
                                                        </button>
                                                    </form>
                                                </template>

                                                <!-- Jika sudah ada bukti transfer -->
                                                <template x-if="pilihDetail?.bukti_transfer">
                                                    <div class="space-y-4">
                                                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-3">
                                                            <p class="mb-2 text-sm font-medium text-gray-700">Preview Bukti Transfer</p>
                                                            <img
                                                                :src="'/storage/' + pilihDetail.bukti_transfer"
                                                                class="max-h-80 w-full rounded-lg border object-contain bg-white">
                                                        </div>

                                                        <div class="rounded-xl border border-green-200 bg-green-50 p-4">
                                                            <p class="text-sm font-semibold text-green-700">Pembayaran telah dikonfirmasi</p>
                                                            <p class="mt-1 text-sm text-green-600">
                                                                Bukti transfer sudah tersedia pada sistem.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>

                                            <!-- Catatan -->
                                            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 shadow-sm">
                                                <h4 class="text-sm font-semibold text-amber-800">Catatan Verifikasi</h4>
                                                <p class="mt-2 text-sm leading-6 text-amber-700">
                                                    Pastikan nominal pembayaran, rekening tujuan, dan file pekerjaan sudah sesuai sebelum melakukan konfirmasi pembayaran.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Footer -->
                                <div class="sticky bottom-0 z-20 border-t border-gray-200 bg-white px-6 py-4">
                                    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                                        <button
                                            @click="modalDetail = false"
                                            type="button"
                                            class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-100">
                                            Tutup
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        Belum ada pembayaran yang perlu diproses
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</div>
</div>
@endsection