@extends('layouts.body', ['title' => 'Manajemen Freelancer'])

@section('content')
<x-header
    :judul="'Persetujuan Pendaftaran Freelancer'"
    :subjudul="'Kelola permintaan pendaftaran akun freelancer baru'" />

<div class="mb-8">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <x-stat-card
            title="Permintaan" :value="$statusVerifikasi['permintaan'] ?? 0"
            color="yellow" bg="yellow-50" brdr="yellow" />

        <x-stat-card
            title="Diterima" :value="$statusVerifikasi['diterima'] ?? 0"
            color="green" bg="green-50" brdr="green" />

        <x-stat-card
            title="Ditolak" :value="$statusVerifikasi['ditolak'] ?? 0"
            color="red" bg="red-50" brdr="red" />
    </div>
</div>

<!-- Search dan Filter -->
<x-search-filter
    :action="route('freelancer.halaman')"
    search-name="search"
    search-placeholder="Cari nama atau email freelancer..."
    :filters="[
        [
            'name' => 'status_verifikasi',
            'placeholder' => 'Semua Verifikasi',
            'options' => [
                'permintaan' => 'Permintaan',
                'diterima' => 'Diterima',
                'ditolak' => 'Ditolak',
            ]
        ],

        [
            'name' => 'status_akun',
            'placeholder' => 'Semua Status Akun',
            'options' => [
                'aktif' => 'Aktif',
                'nonaktif' => 'Non Aktif',
            ]
        ]
    ]" />

<!-- Tabel -->
<div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-linear-to-r from-brand-500 to-brand-700 rounded-t-2xl">
        <h2 class="text-white text-lg font-semibold">Daftar Freelancer</h2>
    </div>
    <div x-data="{
                    modalDetail: false,
                    lihatDetail: null
                }" class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 text-brand-500 text-xs uppercase border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left">Nama</th>
                    <th class="px-6 py-3 text-left">Email</th>
                    <th class="px-6 py-3 text-left">No Telp</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
                @forelse ($freelancers as $user)
                <tr class="bg-white text-sm">
                    <td class="px-6 py-3 text-gray-500 text-left font-semibold">{{ $user->name }}</td>
                    <td class="px-6 py-3 text-gray-500 text-left">{{ $user->email }}</td>
                    <td class="px-6 py-3 text-gray-500 text-left">{{ $user->no_telp }}</td>
                    <td class="px-6 py-3 text-gray-500 text-left">
                        <x-status :value="$user->status_verifikasi"></x-status>
                    </td>
                    <td class="px-6 py-3 text-gray-500 text-left flex justify-start gap-2 text-sm">
                        <x-secondary-button
                            @click="modalDetail = true;
                                lihatDetail = {{ @Js::from($user) }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2" height="20" width="20" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                                <path fill="currentColor" d="M320 144C254.8 144 201.2 173.6 160.1 211.7C121.6 247.5 95 290 81.4 320C95 350 121.6 392.5 160.1 428.3C201.2 466.4 254.8 496 320 496C385.2 496 438.8 466.4 479.9 428.3C518.4 392.5 545 350 558.6 320C545 290 518.4 247.5 479.9 211.7C438.8 173.6 385.2 144 320 144zM127.4 176.6C174.5 132.8 239.2 96 320 96C400.8 96 465.5 132.8 512.6 176.6C559.4 220.1 590.7 272 605.6 307.7C608.9 315.6 608.9 324.4 605.6 332.3C590.7 368 559.4 420 512.6 463.4C465.5 507.1 400.8 544 320 544C239.2 544 174.5 507.2 127.4 463.4C80.6 419.9 49.3 368 34.4 332.3C31.1 324.4 31.1 315.6 34.4 307.7C49.3 272 80.6 220 127.4 176.6zM320 400C364.2 400 400 364.2 400 320C400 290.4 383.9 264.5 360 250.7C358.6 310.4 310.4 358.6 250.7 360C264.5 383.9 290.4 400 320 400zM240.4 311.6C242.9 311.9 245.4 312 248 312C283.3 312 312 283.3 312 248C312 245.4 311.8 242.9 311.6 240.4C274.2 244.3 244.4 274.1 240.5 311.5zM286 196.6C296.8 193.6 308.2 192.1 319.9 192.1C328.7 192.1 337.4 193 345.7 194.7C346 194.8 346.2 194.8 346.5 194.9C404.4 207.1 447.9 258.6 447.9 320.1C447.9 390.8 390.6 448.1 319.9 448.1C258.3 448.1 206.9 404.6 194.7 346.7C192.9 338.1 191.9 329.2 191.9 320.1C191.9 309.1 193.3 298.3 195.9 288.1C196.1 287.4 196.2 286.8 196.4 286.2C208.3 242.8 242.5 208.6 285.9 196.7z" />
                            </svg>
                            Lihat Detail
                        </x-secondary-button>

                        <x-modals.detail-profil-freelancer></x-modals.detail-profil-freelancer>

                        @if($user->isPending())

                        <form method="POST"
                            action="{{ route('freelancer.update-verifikasi', $user) }}"
                            class="confirm inline-block">
                            @csrf
                            <input type="hidden" name="status_verifikasi" value="diterima">

                            <button type="submit"
                                class="px-4 py-2 rounded-2xl bg-green-600 hover:bg-green-700 text-white text-sm font-semibold">
                                Terima
                            </button>
                        </form>

                        <form method="POST"
                            action="{{ route('freelancer.update-verifikasi', $user) }}"
                            class="confirm inline-block">
                            @csrf
                            <input type="hidden" name="status_verifikasi" value="ditolak">

                            <button type="submit"
                                class="px-4 py-2 rounded-2xl bg-red-600 hover:bg-red-700 text-white text-sm font-semibold">
                                Tolak
                            </button>
                        </form>
                        @endif

                        @if($user->isAccepted() && $user->isActiveAccount())

                        <form method="POST"
                            action="{{ route('freelancer.update-akun', $user) }}"
                            class="confirm inline-block">
                            @csrf
                            <input type="hidden" name="status_akun" value="nonaktif">

                            <button type="submit"
                                class="px-4 py-2 rounded-2xl bg-red-600 hover:bg-red-700 text-white text-sm font-semibold">
                                Nonaktifkan
                            </button>
                        </form>

                        @endif

                        @if($user->isAccepted() && $user->isInactiveAccount())

                        <form method="POST"
                            action="{{ route('freelancer.update-akun', $user) }}"
                            class="confirm inline-block">
                            @csrf
                            <input type="hidden" name="status_akun" value="aktif">

                            <button type="submit"
                                class="px-4 py-2 rounded-2xl bg-green-600 hover:bg-green-700 text-white text-sm font-semibold">
                                Aktifkan
                            </button>
                        </form>

                        @endif

                        @if($user->isRejected())

                        <form method="POST"
                            action="{{ route('freelancer.update-verifikasi', $user) }}"
                            class="confirm inline-block">
                            @csrf
                            <input type="hidden" name="status_verifikasi" value="permintaan">

                            <button type="submit"
                                class="px-4 py-2 rounded-2xl bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold">
                                Ajukan Ulang
                            </button>
                        </form>

                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <x-list-empty title="Tidak Ada Freelancer" subtitle="Semua freelancer akan tampil disini">
                            <x-slot:icon>
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

@endsection