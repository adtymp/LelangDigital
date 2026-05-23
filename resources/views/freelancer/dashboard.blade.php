@extends('layouts.body', ['title' => 'Dashboard'])

@section('content')
<x-header
    :judul="'Dashboard Freelancer'"
    :subjudul="'Level ' . $user->level->nama_level . ' | Notifikasi pada 21.00'" />

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
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
        title="Tugas Selesai"
        :value="$proyekSelesai"
        color="orange">
        <x-slot:icon>
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 640 640">
                <path fill="currentColor" d="M128 464L512 464C520.8 464 528 456.8 528 448L528 208C528 199.2 520.8 192 512 192L362.7 192C345.4 192 328.5 186.4 314.7 176L276.3 147.2C273.5 145.1 270.2 144 266.7 144L128 144C119.2 144 112 151.2 112 160L112 448C112 456.8 119.2 464 128 464zM512 512L128 512C92.7 512 64 483.3 64 448L64 160C64 124.7 92.7 96 128 96L266.7 96C280.5 96 294 100.5 305.1 108.8L343.5 137.6C349 141.8 355.8 144 362.7 144L512 144C547.3 144 576 172.7 576 208L576 448C576 483.3 547.3 512 512 512z" />
            </svg>
        </x-slot:icon>
    </x-stat-card>

    <x-stat-card
        title="Total Pendapatan"
        :value="'Rp. '. number_format($pendapatan, 0, ',', '.')"
        color="green">
        <x-slot:icon>
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 640 640">
                <path fill="currentColor" d="M296 88C296 74.7 306.7 64 320 64C333.3 64 344 74.7 344 88L344 128L400 128C417.7 128 432 142.3 432 160C432 177.7 417.7 192 400 192L285.1 192C260.2 192 240 212.2 240 237.1C240 259.6 256.5 278.6 278.7 281.8L370.3 294.9C424.1 302.6 464 348.6 464 402.9C464 463.2 415.1 512 354.9 512L344 512L344 552C344 565.3 333.3 576 320 576C306.7 576 296 565.3 296 552L296 512L224 512C206.3 512 192 497.7 192 480C192 462.3 206.3 448 224 448L354.9 448C379.8 448 400 427.8 400 402.9C400 380.4 383.5 361.4 361.3 358.2L269.7 345.1C215.9 337.5 176 291.4 176 237.1C176 176.9 224.9 128 285.1 128L296 128L296 88z" />
            </svg>
        </x-slot:icon>
    </x-stat-card>
</div>

<!-- search -->
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
                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" />
        </div>

        {{-- Filter Harga --}}
        <div class="relative">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"
                viewBox="0 0 640 640" fill="currentColor">
                <path d="M96 128C83.1 128 71.4 135.8 66.4 147.8C61.4 159.8 64.2 173.5 73.4 182.6L256 365.3L256 480C256 488.5 259.4 496.6 265.4 502.6L329.4 566.6C338.6 575.8 352.3 578.5 364.3 573.5C376.3 568.5 384 556.9 384 544L384 365.3L566.6 182.7C575.8 173.5 578.5 159.8 573.5 147.8C568.5 135.8 556.9 128 544 128L96 128z" />
            </svg>

            <select
                class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent appearance-none bg-white">
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
                <span class="font-semibold text-red-500">
                    {{ $proyek->tanggal_selesai->format('d M Y H:i') }}
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
    <div class="col-span-full bg-white rounded-xl border border-gray-200 p-8 text-center">
        <p class="text-gray-500">Tidak ada proyek yang tersedia</p>
    </div>
    @endforelse
</div>
</div>
</div>
@endsection