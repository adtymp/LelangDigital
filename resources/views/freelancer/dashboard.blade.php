@extends('layouts.body', ['title' => 'Dashboard'])

@section('content')

@php
$delay = $level?->delay_notifikasi ?? 0;

// Formula kata-kata keunggulan berdasarkan menit delay notifikasi
$keunggulanText = $delay == 0
? "Akses Proyek Instan! Anda melihat proyek baru secara real-time tanpa penundaan waktu."
: "Notifikasi proyek tertunda {$delay} menit.";
@endphp

<x-header
    :judul="'Dashboard Freelancer'"
    :subjudul="'Level ' . $level?->nama_level . ' • ' . $keunggulanText" />

<div x-data="resetCountdown()" x-init="startCountdown()">

    @if($resetLevel)
    <div class="mt-4 bg-white border border-gray-200 rounded-2xl p-4 mb-5 shadow">

        <p class="text-sm text-gray-500 mb-1">
            Reset level berikutnya
        </p>

        <p x-text="countdown"
            class="text-xl font-semibold text-brand-500">
        </p>

    </div>
    @endif

</div>

<!-- statcard -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
    <x-stat-card
        title="Proyek Aktif"
        :value="$proyekAktif"
        color="blue"
        brdr="blue"
        shade="600">
        <x-slot:icon>
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 640 640">
                <path fill="currentColor" d="M129.5 464L179.5 304L558.9 304L508.9 464L129.5 464zM320.2 512L509 512C530 512 548.6 498.4 554.8 478.3L604.8 318.3C614.5 287.4 591.4 256 559 256L179.6 256C158.6 256 140 269.6 133.8 289.7L112.2 358.4L112.2 160C112.2 151.2 119.4 144 128.2 144L266.9 144C270.4 144 273.7 145.1 276.5 147.2L314.9 176C328.7 186.4 345.6 192 362.9 192L480.2 192C489 192 496.2 199.2 496.2 208L544.2 208C544.2 172.7 515.5 144 480.2 144L362.9 144C356 144 349.2 141.8 343.7 137.6L305.3 108.8C294.2 100.5 280.8 96 266.9 96L128.2 96C92.9 96 64.2 124.7 64.2 160L64.2 448C64.2 483.3 92.9 512 128.2 512L320.2 512z" />
            </svg>
        </x-slot:icon>
    </x-stat-card>

    <x-stat-card
        title="Tugas Selesai"
        :value="$proyekSelesai"
        color="yellow"
        brdr="yellow"
        shade="600">
        <x-slot:icon>
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 640 640">
                <path fill="currentColor" d="M128 464L512 464C520.8 464 528 456.8 528 448L528 208C528 199.2 520.8 192 512 192L362.7 192C345.4 192 328.5 186.4 314.7 176L276.3 147.2C273.5 145.1 270.2 144 266.7 144L128 144C119.2 144 112 151.2 112 160L112 448C112 456.8 119.2 464 128 464zM512 512L128 512C92.7 512 64 483.3 64 448L64 160C64 124.7 92.7 96 128 96L266.7 96C280.5 96 294 100.5 305.1 108.8L343.5 137.6C349 141.8 355.8 144 362.7 144L512 144C547.3 144 576 172.7 576 208L576 448C576 483.3 547.3 512 512 512z" />
            </svg>
        </x-slot:icon>
    </x-stat-card>

    <x-stat-card
        title="Total Pendapatan"
        :value="'Rp. '. number_format($pendapatan, 0, ',', '.')"
        shade="600"
        brdr="green"
        color="green">
        <x-slot:icon>
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 640 640">
                <path fill="currentColor" d="M296 88C296 74.7 306.7 64 320 64C333.3 64 344 74.7 344 88L344 128L400 128C417.7 128 432 142.3 432 160C432 177.7 417.7 192 400 192L285.1 192C260.2 192 240 212.2 240 237.1C240 259.6 256.5 278.6 278.7 281.8L370.3 294.9C424.1 302.6 464 348.6 464 402.9C464 463.2 415.1 512 354.9 512L344 512L344 552C344 565.3 333.3 576 320 576C306.7 576 296 565.3 296 552L296 512L224 512C206.3 512 192 497.7 192 480C192 462.3 206.3 448 224 448L354.9 448C379.8 448 400 427.8 400 402.9C400 380.4 383.5 361.4 361.3 358.2L269.7 345.1C215.9 337.5 176 291.4 176 237.1C176 176.9 224.9 128 285.1 128L296 128L296 88z" />
            </svg>
        </x-slot:icon>
    </x-stat-card>
</div>

<!-- search -->
<x-search-filter
    :action="route('dashboard.freelance')"
    searchName="search"
    searchPlaceholder="Cari proyek..."
    :filters="[
        [
            'name' => 'kualitas',
            'placeholder' => 'Semua Kualitas',
            'options' => [
                '2' => 'Sangat Kurang',
                '4' => 'Kurang',
                '6' => 'Sedang',
                '8' => 'Baik',
                '10' => 'Sangat Baik',
            ]
        ],
        [
            'name' => 'sort',
            'placeholder' => 'Urutkan',
            'options' => [
                'terbaru' => 'Terbaru',
                'terlama' => 'Terlama',
                'deadline' => 'Deadline Terdekat',
                'halaman_terbanyak' => 'Halaman Tersedia Terbanyak',
            ]
        ]
    ]" />

<div class="items-center justify-center grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse ($proyeks as $proyek)

    @foreach ($proyek->subproyeks as $sub)

    @foreach ($sub->subsubproyeks as $sss)

    <!-- CARD -->
    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-200 hover:shadow-lg transition">

        <!-- HEADER -->
        <div class="space-y-1">
            <h2 class="text-xl font-bold text-gray-800">
                {{ $proyek->nama_proyek }}
            </h2>

            <h3 class="text-sm text-gray-500">
                {{ $sss->nama_halaman }}
            </h3>

            <p class="text-brand-600 text-sm font-medium">
                {{ $sub->nama_sub_proyek }}
            </p>
        </div>

        <!-- KUALITAS -->
        <div class="flex items-center justify-between mt-4"
            x-data="{ kualitas: '{{ $sss->kualitas }}' }">

            <span class="text-sm text-gray-500">Kualitas</span>

            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"
                :class="{
                'bg-yellow-100 text-yellow-700' : kualitas === '2',
                'bg-orange-100 text-orange-700' : kualitas === '4',
                'bg-green-100 text-green-700': kualitas === '6',
                'bg-cyan-100 text-cyan-700': kualitas === '8',
                'bg-blue-100 text-blue-700': kualitas === '10',
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

        <!-- INFO -->
        <div class="mt-4 border-t border-gray-200 pt-4 space-y-3 text-sm">

            <div class="flex justify-between">
                <span class="text-gray-500">Total Halaman</span>
                <span class="font-semibold">
                    {{ $sss->total_halaman }} halaman
                </span>
            </div>

            <div class="flex justify-between">
                <span class="text-gray-500">Harga / Lembar</span>
                <span class="font-semibold text-blue-600">
                    Rp {{ number_format($sss->harga_perlembar, 0, ',', '.') }}
                </span>
            </div>

            <div class="flex justify-between">
                <span class="text-gray-500">Deadline</span>
                <span class="font-semibold text-red-600">
                    {{ $proyek->tanggal_selesai->format('d M Y H:i') }}
                </span>
            </div>

            <div class="flex justify-between">
                <span class="text-gray-500">Halaman Tersedia</span>
                <span class="font-semibold text-green-600">
                    {{ $sss->sisa_halaman }} Halaman
                </span>
            </div>

        </div>

        <div class="mt-6 items-center justify-center">
            <x-anchor link="{{ route('freelance.ambil', $sss->id) }}" full>Lihat dan Ambil</x-anchor>
        </div>

    </div>

    @endforeach
    @endforeach
    @empty
    <x-list-empty title="Tidak Ada Proyek Tersedia" subtitle="Semua proyek aktif akan tampil disini">
        <x-slot:icon>
            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M9 8h6m2 12H7a2 2 0 01-2-2V6a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V18a2 2 0 01-2 2z" />
            </svg>
        </x-slot:icon>
    </x-list-empty>
    @endforelse
</div>

<script>
    function resetCountdown() {
        return {
            resetLevel: @json($resetLevel),
            countdown: '',
            countdownInterval: null,
            sedangSinkron: false,

            async startCountdown() {
                //Hindari interval dobel

                if (this.countdownInterval) {
                    clearInterval(this.countdownInterval)
                }

                this.countdownInterval = setInterval(async () => {

                    //Belum pernah reset

                    if (!this.resetLevel.last_reset_at) {

                        this.countdown = 'Belum ada reset';

                        return;
                    }

                    //Hitung reset berikutnya

                    let lastReset = new Date(this.resetLevel.last_reset_at);

                    let nextReset = new Date(lastReset);

                    nextReset.setDate(
                        nextReset.getDate() +
                        parseInt(this.resetLevel.lama_hari)
                    );

                    let jam = this.resetLevel.jam_reset.split(':');

                    nextReset.setHours(parseInt(jam[0]));
                    nextReset.setMinutes(parseInt(jam[1]));
                    nextReset.setSeconds(0);

                    let sekarang = new Date();

                    let diff = nextReset - sekarang;

                    //waktu reset

                    if (diff <= 0) {

                        this.countdown = 'Sedang sinkronisasi reset...';

                        if (!this.sedangSinkron) {

                            this.sedangSinkron = true;

                            try {

                                await this.ambilResetTerbaru();

                            } finally {

                                this.sedangSinkron = false;
                            }
                        }

                        return;
                    }

                    let hari = Math.floor(
                        diff / (1000 * 60 * 60 * 24)
                    );

                    let jamSisa = Math.floor(
                        (diff / (1000 * 60 * 60)) % 24
                    );

                    let menit = Math.floor(
                        (diff / (1000 * 60)) % 60
                    );

                    let detik = Math.floor(
                        (diff / 1000) % 60
                    );

                    this.countdown =
                        `${hari} hari ${jamSisa} jam ${menit} menit ${detik} detik`;

                }, 1000);
            },
        }
    }
</script>
@endsection