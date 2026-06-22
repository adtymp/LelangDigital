@extends('layouts.body', ['title' => 'Pembayaran'])

@section('content')
<x-header
    :judul="'Pembayaran'"
    :subjudul="'Kelola pembayaran upah freelancer'" />

<!-- Kartu Ringkasan Statistik -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <x-stat-card
        title="Belum Dibayar"
        :value="$belumDibayar"
        color="red"
        brdr="red"/>
    <x-stat-card
        title="Sudah Dibayar"
        :value="$sudahDibayar"
        color="green"
        brdr="green"/>
    <x-stat-card
        title="Total Pembayaran"
        :value="'Rp. ' . number_format($pengupahan, 0, ',', '.')"
        color="blue"
        brdr="blue">
    </x-stat-card>
</div>

<!-- Konfigurasi Filter untuk Komponen search-filter -->
@php
$months = [
'01' => 'Januari',
'02' => 'Februari',
'03' => 'Maret',
'04' => 'April',
'05' => 'Mei',
'06' => 'Juni',
'07' => 'Juli',
'08' => 'Agustus',
'09' => 'September',
'10' => 'Oktober',
'11' => 'November',
'12' => 'Desember'
];

$currentYear = date('Y');
$years = [];
for ($y = $currentYear; $y >= 2024; $y--) {
$years[$y] = $y;
}

$bankOptions = [];
foreach ($availableBanks as $bankName) {
$bankOptions[$bankName] = strtoupper($bankName);
}

$filterConfig = [
[
'placeholder' => 'Status Pembayaran',
'name' => 'status',
'options' => [
'belum_dibayar' => 'Belum Dibayar',
'sudah_dibayar' => 'Sudah Dibayar'
]
],
[
'placeholder' => 'Pilih Bank',
'name' => 'bank',
'options' => $bankOptions
],
[
'placeholder' => 'Pilih Bulan',
'name' => 'bulan',
'options' => $months
],
[
'placeholder' => 'Pilih Tahun',
'name' => 'tahun',
'options' => $years
]
];
@endphp

<!-- Integrasi Komponen Search Filter Bawaan Sistem -->
<x-search-filter
    :action="url()->current()"
    search-placeholder="Cari nama freelancer, email, nama proyek, atau halaman..."
    :filters="$filterConfig" />

<!-- Tabel Daftar Pembayaran -->
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-200 bg-linear-to-r from-brand-500 to-brand-700 rounded-t-2xl">
        <h2 class="text-lg font-semibold text-white">Daftar Pembayaran</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 text-brand-500 uppercase text-xs border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left">Nama Freelancer</th>
                    <th class="px-6 py-3 text-center">Nama Bank</th>
                    <th class="px-6 py-3 text-left">No Rekening</th>
                    <th class="px-6 py-3 text-left">Pembayaran</th>
                    <th class="px-6 py-3 text-center">Status</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
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
                    riwayatPembayaran : []
                }" class="divide-y divide-gray-200">

                @forelse ($pembayarans as $pembayaran)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <button @click="
                                    modalUser = true;
                                    pilihUser = {{ Js::from($pembayaran) }};
                                    riwayatPembayaran = semuaPembayaran.filter(p => p.user.id === pilihUser.user.id);"
                            class="text-brand-500 hover:underline cursor-pointer font-semibold text-sm">
                            {{ $pembayaran['user']['name'] }}
                        </button>
                        <x-modals.detail-riwayat-freelancer></x-modals.detail-riwayat-freelancer>
                    </td>
                    <td class="text-center px-6 py-4 text-blue-600 text-xs font-semibold uppercase">{{ $pembayaran['rekening']['nama_bank'] ?? '-' }}</td>
                    <td class="text-left px-6 py-4 text-gray-500">{{ $pembayaran['rekening']['no_akun'] ?? '-' }}</td>
                    <td class="text-left px-6 py-4 text-green-600 font-semibold">
                        Rp {{ number_format($pembayaran['total_pembayaran'], 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <x-status :value="$pembayaran['status']"></x-status>
                    </td>
                    <td class=" px-6 py-4 justify-center flex">
                        <x-secondary-button @click="
                                    modalDetail = true;
                                    pilihDetail = {{ Js::from($pembayaran) }};
                                    " class="text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2" height="20" width="20" viewBox="0 0 640 640">
                                <path fill="currentColor" d="M320 144C254.8 144 201.2 173.6 160.1 211.7C121.6 247.5 95 290 81.4 320C95 350 121.6 392.5 160.1 428.3C201.2 466.4 254.8 496 320 496C385.2 496 438.8 466.4 479.9 428.3C518.4 392.5 545 350 558.6 320C545 290 518.4 247.5 479.9 211.7C438.8 173.6 385.2 144 320 144zM127.4 176.6C174.5 132.8 239.2 96 320 96C400.8 96 465.5 132.8 512.6 176.6C559.4 220.1 590.7 272 605.6 307.7C608.9 315.6 608.9 324.4 605.6 332.3C590.7 368 559.4 420 512.6 463.4C465.5 507.1 400.8 544 320 544C239.2 544 174.5 507.2 127.4 463.4C80.6 419.9 49.3 368 34.4 332.3C31.1 324.4 31.1 315.6 34.4 307.7C49.3 272 80.6 220 127.4 176.6zM320 400C364.2 400 400 364.2 400 320C400 290.4 383.9 264.5 360 250.7C358.6 310.4 310.4 358.6 250.7 360C264.5 383.9 290.4 400 320 400zM240.4 311.6C242.9 311.9 245.4 312 248 312C283.3 312 312 283.3 312 248C312 245.4 311.8 242.9 311.6 240.4C274.2 244.3 244.4 274.1 240.5 311.5zM286 196.6C296.8 193.6 308.2 192.1 319.9 192.1C328.7 192.1 337.4 193 345.7 194.7C346 194.8 346.2 194.8 346.5 194.9C404.4 207.1 447.9 258.6 447.9 320.1C447.9 390.8 390.6 448.1 319.9 448.1C258.3 448.1 206.9 404.6 194.7 346.7C192.9 338.1 191.9 329.2 191.9 320.1C191.9 309.1 193.3 298.3 195.9 288.1C196.1 287.4 196.2 286.8 196.4 286.2C208.3 242.8 242.5 208.6 285.9 196.7z" />
                            </svg>
                            Lihat Detail
                        </x-secondary-button>

                        <x-modals.detail-pembayaran></x-modals.detail-pembayaran>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <x-list-empty title="Tidak Ada Pembayaran" subtitle="Semua pembayaran akan tampil disini">
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