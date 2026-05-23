@extends('layouts.body', ['title' => 'Dashboard'])

@section('content')
<x-header
    :judul="'Dashboard Admin'"
    :subjudul="'Kelola proyek dan monitor aktivitas'" />

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <x-stat-card
        title="Total Proyek"
        :value="$totalProyek"
        color="orange">
        <x-slot:icon>
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 640 640">
                <path fill="currentColor" d="M128 464L512 464C520.8 464 528 456.8 528 448L528 208C528 199.2 520.8 192 512 192L362.7 192C345.4 192 328.5 186.4 314.7 176L276.3 147.2C273.5 145.1 270.2 144 266.7 144L128 144C119.2 144 112 151.2 112 160L112 448C112 456.8 119.2 464 128 464zM512 512L128 512C92.7 512 64 483.3 64 448L64 160C64 124.7 92.7 96 128 96L266.7 96C280.5 96 294 100.5 305.1 108.8L343.5 137.6C349 141.8 355.8 144 362.7 144L512 144C547.3 144 576 172.7 576 208L576 448C576 483.3 547.3 512 512 512z" />
            </svg>
        </x-slot:icon>
    </x-stat-card>
    <x-stat-card
        title="Proyek Aktif"
        :value="$proyekAktif"
        color="blue">
        <x-slot:icon>
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 640 640">
                <path fill="currentColor" d="M129.5 464L179.5 304L558.9 304L508.9 464L129.5 464zM320.2 512L509 512C530 512 548.6 498.4 554.8 478.3L604.8 318.3C614.5 287.4 591.4 256 559 256L179.6 256C158.6 256 140 269.6 133.8 289.7L112.2 358.4L112.2 160C112.2 151.2 119.4 144 128.2 144L266.9 144C270.4 144 273.7 145.1 276.5 147.2L314.9 176C328.7 186.4 345.6 192 362.9 192L480.2 192C489 192 496.2 199.2 496.2 208L544.2 208C544.2 172.7 515.5 144 480.2 144L362.9 144C356 144 349.2 141.8 343.7 137.6L305.3 108.8C294.2 100.5 280.8 96 266.9 96L128.2 96C92.9 96 64.2 124.7 64.2 160L64.2 448C64.2 483.3 92.9 512 128.2 512L320.2 512z" />
            </svg>
        </x-slot:icon>
    </x-stat-card>
    <x-stat-card
        title="Total Freelancer"
        :value="$freelancer"
        color="red">
        <x-slot:icon>
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                <path fill="currentColor" d="M320 80C377.4 80 424 126.6 424 184C424 241.4 377.4 288 320 288C262.6 288 216 241.4 216 184C216 126.6 262.6 80 320 80zM96 152C135.8 152 168 184.2 168 224C168 263.8 135.8 296 96 296C56.2 296 24 263.8 24 224C24 184.2 56.2 152 96 152zM0 480C0 409.3 57.3 352 128 352C140.8 352 153.2 353.9 164.9 357.4C132 394.2 112 442.8 112 496L112 512C112 523.4 114.4 534.2 118.7 544L32 544C14.3 544 0 529.7 0 512L0 480zM521.3 544C525.6 534.2 528 523.4 528 512L528 496C528 442.8 508 394.2 475.1 357.4C486.8 353.9 499.2 352 512 352C582.7 352 640 409.3 640 480L640 512C640 529.7 625.7 544 608 544L521.3 544zM472 224C472 184.2 504.2 152 544 152C583.8 152 616 184.2 616 224C616 263.8 583.8 296 544 296C504.2 296 472 263.8 472 224zM160 496C160 407.6 231.6 336 320 336C408.4 336 480 407.6 480 496L480 512C480 529.7 465.7 544 448 544L192 544C174.3 544 160 529.7 160 512L160 496z" />
            </svg>
        </x-slot:icon>
    </x-stat-card>
    <x-stat-card
        title="Upah Dibayar"
        :value="'Rp. ' . number_format($upah, 0, ',', '.')"
        color="green">
        <x-slot:icon>
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 640 640">
                <path fill="currentColor" d="M296 88C296 74.7 306.7 64 320 64C333.3 64 344 74.7 344 88L344 128L400 128C417.7 128 432 142.3 432 160C432 177.7 417.7 192 400 192L285.1 192C260.2 192 240 212.2 240 237.1C240 259.6 256.5 278.6 278.7 281.8L370.3 294.9C424.1 302.6 464 348.6 464 402.9C464 463.2 415.1 512 354.9 512L344 512L344 552C344 565.3 333.3 576 320 576C306.7 576 296 565.3 296 552L296 512L224 512C206.3 512 192 497.7 192 480C192 462.3 206.3 448 224 448L354.9 448C379.8 448 400 427.8 400 402.9C400 380.4 383.5 361.4 361.3 358.2L269.7 345.1C215.9 337.5 176 291.4 176 237.1C176 176.9 224.9 128 285.1 128L296 128L296 88z" />
            </svg>
        </x-slot:icon>
    </x-stat-card>
</div>

<x-anchor link="{{ route('proyek.halaman') }}">+ Tambah Proyek Baru</x-anchor>

<div class="bg-white rounded-2xl border border-gray-200 mt-8 shadow-sm"
    x-data="{ openProyek: null, openSub: null }">

    <!-- HEADER -->
    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-200 bg-linear-to-r from-brand-500 to-brand-700 rounded-t-2xl">
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
    <div class="overflow-x-auto">
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
                            @click="openProyek === {{ $proyek->id }} ? openProyek=null : openProyek={{ $proyek->id }}">

                            <div>
                                <p class="font-semibold text-brand-500">
                                    {{ $proyek->nama_proyek }}
                                </p>
                                <p class="text-xs text-gray-400">
                                    Klik untuk melihat detail
                                </p>
                            </div>

                            <!-- CHEVRON -->
                            <svg class="ml-auto w-4 h-4 transition-transform" :class="openProyek === {{ $proyek->id }} ? 'rotate-180' : ''"
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
                <tr x-show="openProyek === {{ $proyek->id }}" x-transition>
                    <td colspan="5" class="bg-gray-50 px-6 py-5">

                        <div class="bg-white rounded-xl border border-gray-200 p-4">
                            <h3 class="text-sm font-semibold text-gray-700 mb-3">
                                Sub Proyek
                            </h3>

                            <div class="space-y-2">
                                @foreach($proyek->subproyeks as $sub)

                                <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition cursor-pointer"
                                    @click="openSub === {{ $sub->id }} ? openSub=null : openSub={{ $sub->id }}">

                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="font-medium text-gray-800">
                                                {{ $sub->nama_sub_proyek }}
                                            </p>
                                            <p class="text-xs text-gray-400">
                                                {{ $sub->total_halaman }} halaman
                                            </p>
                                        </div>

                                        <span class="text-xs bg-green-100 text-green-600 px-2 py-1 rounded">
                                            File
                                        </span>
                                    </div>

                                    <!-- SUB DETAIL -->
                                    <div x-show="openSub === {{ $sub->id }}" class="mt-3 text-xs">
                                        <div class="bg-gray-50 rounded p-3">
                                            <table class="w-full">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th>PDF</th>
                                                        <th>XLS</th>
                                                        <th>Halaman</th>
                                                        <th>Harga</th>
                                                        <th>Subtotal</th>
                                                        <th>Pengambilan</th> {{-- TAMBAHAN --}}
                                                    </tr>
                                                </thead>

                                                @foreach($sub->subsubproyeks as $sss)
                                                <tbody>
                                                    <tr class="gap-4 p-2">
                                                        <td class="text-center">
                                                            <a href="{{ asset('storage/'.$sss->file_pdf) }}" target="_blank" class="text-brand-500 hover:underline">PDF</a>
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="{{ asset('storage/'.$sss->file_xls) }}" target="_blank" class="text-green-600 hover:underline">XLS</a>
                                                        </td>
                                                        <td class="text-center">{{ $sss->total_halaman }}</td>
                                                        <td class="text-center">Rp {{ number_format($sss->harga_perlembar, 0, ',', '.') }}</td>
                                                        <td class="text-center font-semibold">
                                                            Rp {{ number_format($sss->total_halaman * $sss->harga_perlembar, 0, ',', '.') }}
                                                        </td>
                                                        {{-- TAMBAHAN: tombol lihat pengambilan --}}
                                                        <td class="text-center">
                                                            <a href="{{ route('monitoring', $sss->id) }}"
                                                                @click.stop
                                                                class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-[11px] bg-blue-50 text-blue-700 hover:bg-blue-100 transition">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 20 20" fill="currentColor">
                                                                    <path d="M10 3C5 3 1.73 7.11 1.05 9.71a1 1 0 0 0 0 .58C1.73 12.89 5 17 10 17s8.27-4.11 8.95-6.71a1 1 0 0 0 0-.58C18.27 7.11 15 3 10 3zm0 11a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm0-6a2 2 0 1 0 0 4 2 2 0 0 0 0-4z" />
                                                                </svg>
                                                                Lihat
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                @endforeach
                                            </table>
                                        </div>
                                    </div>

                                </div>

                                @endforeach
                            </div>
                        </div>

                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="5" class="text-center py-12 text-gray-400">
                        Belum ada proyek yang bisa ditampilkan
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