@extends('layouts.body', ['title' => 'Riwayat Pekerjaan'])

@section('content')
<x-header
    :judul="'Riwayat Pekerjaan'"
    :subjudul="'Lihat aspek penilaian, bobot, dan status pembayaran pekerjaan Anda'" />

<!-- Ringkasan Statistik -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <x-stat-card title="Total Proyek" :value="$totalProyek" color="yellow" brdr="yellow"></x-stat-card>
    <x-stat-card title="Sudah Dibayar" :value="$sudahDibayar" color="blue" brdr="blue"></x-stat-card>
    <x-stat-card title="Belum Dibayar" :value="$belumDibayar" color="red" brdr="red"></x-stat-card>
    <x-stat-card title="Total Pendapatan" :value="'Rp. ' . number_format($totalPendapatan, 0, ',', '.')" color="green" brdr="green"></x-stat-card>
</div>

<!-- Konfigurasi Filter Bulan & Tahun untuk Komponen search-filter -->
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

// Buat daftar pilihan tahun secara dinamis (sejak tahun awal sistem berjalan)
$currentYear = date('Y');
$years = [];
for ($y = $currentYear; $y >= 2024; $y--) {
$years[$y] = $y;
}

$filterConfig = [
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
    search-placeholder="Cari nama proyek, sub-proyek, atau halaman..."
    :filters="$filterConfig" />

<!-- Container Alpine.js -->
<div x-data="riwayatComponent()" class="space-y-6">

    <!-- AREA DAFTAR RIWAYAT (DIKELOMPOKKAN PER BULAN) -->
    <div class="space-y-8">
        <template x-for="group in groupedPembayarans" :key="group.monthYear">
            <div class="space-y-4">

                <!-- Pemisah Kelompok Bulan -->
                <div class="sticky top-0 z-10 w-full flex justify-end">
                    <div class="flex items-center gap-3">
                        <span class="bg-linear-to-r from-brand-500 to-brand-700 text-white text-sm font-semibold border border-brand-100 px-2 py-2 rounded-2xl  uppercase" x-text="group.monthYear"></span>
                        <div class="flex-1 h-px bg-slate-200"></div>
                    </div>
                </div>

                <!-- TAMPILAN MOBILE (BLOCK MD:HIDDEN) -->
                <div class="block md:hidden space-y-4">
                    <template x-for="p in group.items" :key="p.id">
                        <div class="bg-white border border-slate-200 rounded-3xl p-5 space-y-4 shadow-xs">
                            <div class="flex justify-between items-start gap-3">
                                <div class="min-w-0">
                                    <h4 class="font-bold text-slate-900 text-sm leading-snug" x-text="p.penilaian.pengambilan.subsubproyeks.subproyeks.proyeks.nama_proyek"></h4>
                                    <p class="text-xs text-slate-500 mt-1">
                                        <span x-text="p.penilaian.pengambilan.subsubproyeks.subproyeks.nama_sub_proyek"></span>
                                        <span class="mx-1.5 text-slate-300">•</span>
                                        <span class="text-slate-700" x-text="p.penilaian.pengambilan.subsubproyeks.nama_halaman"></span>
                                    </p>
                                </div>
                                <div class="shrink-0">
                                    <x-status-alpine status="p.status" brdr=""></x-status-alpine>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3 text-xs border-t border-slate-100 pt-3.5">
                                <div>
                                    <span class="text-slate-400 font-bold uppercase tracking-wider text-[9px]">Halaman Kerja</span>
                                    <p class="font-bold text-slate-800 mt-0.5" x-text="p.penilaian.pengambilan.dari_halaman + ' - ' + p.penilaian.pengambilan.sampai_halaman"></p>
                                </div>
                                <div>
                                    <span class="text-slate-400 font-bold uppercase tracking-wider text-[9px]">Tanggal Selesai</span>
                                    <p class="font-bold text-slate-800 mt-0.5" x-text="new Date(p.updated_at).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'})"></p>
                                </div>
                                <div class="col-span-2">
                                    <span class="text-slate-400 font-bold uppercase tracking-wider text-[9px]">Total Upah</span>
                                    <p class="font-extrabold text-emerald-600 text-sm mt-0.5" x-text="new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(p.total_pembayaran)"></p>
                                </div>
                            </div>

                            <button @click="openModal(p)" class="w-full text-center py-2.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 rounded-xl transition text-xs font-bold">
                                <i class="fas fa-eye mr-1.5"></i> Detail Evaluasi
                            </button>
                        </div>
                    </template>
                </div>

                <!-- TAMPILAN TABLET & DESKTOP (HIDDEN MD:BLOCK) -->
                <div class="hidden md:block bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xs">
                    <div class="px-6 py-5 border-b border-gray-200 bg-linear-to-r from-brand-500 to-brand-800 rounded-t-2xl">
                        <h2 class="text-lg font-semibold text-white">
                            Daftar Riwayat Pekerjaan
                        </h2>
                    </div>
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-xs font-bold text-brand-500 uppercase tracking-wider border-b border-slate-200">
                                <th class="px-6 py-4">Nama Proyek</th>
                                <th class="px-6 py-4 text-center">Rentang Halaman</th>
                                <th class="px-6 py-4 text-center">Tanggal Selesai</th>
                                <th class="px-6 py-4">Total Upah</th>
                                <th class="px-6 py-4 text-center">Status Pembayaran</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            <template x-for="p in group.items" :key="p.id">
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-slate-800" x-text="p.penilaian.pengambilan.subsubproyeks.subproyeks.proyeks.nama_proyek"></p>
                                        <p class="text-xs text-slate-500 mt-0.5">
                                            <span x-text="p.penilaian.pengambilan.subsubproyeks.subproyeks.nama_sub_proyek"></span>
                                            <span class="mx-1 text-slate-300">•</span>
                                            <span x-text="p.penilaian.pengambilan.subsubproyeks.nama_halaman"></span>
                                        </p>
                                    </td>
                                    <td class="px-6 py-4 text-center font-semibold text-slate-700" x-text="p.penilaian.pengambilan.dari_halaman + ' - ' + p.penilaian.pengambilan.sampai_halaman"></td>
                                    <td class="px-6 py-4 text-center font-medium text-slate-500" x-text="new Date(p.updated_at).toLocaleDateString('id-ID', {day: '2-digit', month: '2-digit', year: 'numeric'})"></td>
                                    <td class="px-6 py-4 font-bold text-emerald-600" x-text="new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(p.total_pembayaran)"></td>
                                    <td class="px-6 py-4 text-center">
                                        <x-status-alpine status="p.status" brdr=""></x-status-alpine>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <x-secondary-button type="button" @click="openModal(p)">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2" height="20" width="20" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                                                <path fill="currentColor" d="M320 144C254.8 144 201.2 173.6 160.1 211.7C121.6 247.5 95 290 81.4 320C95 350 121.6 392.5 160.1 428.3C201.2 466.4 254.8 496 320 496C385.2 496 438.8 466.4 479.9 428.3C518.4 392.5 545 350 558.6 320C545 290 518.4 247.5 479.9 211.7C438.8 173.6 385.2 144 320 144zM127.4 176.6C174.5 132.8 239.2 96 320 96C400.8 96 465.5 132.8 512.6 176.6C559.4 220.1 590.7 272 605.6 307.7C608.9 315.6 608.9 324.4 605.6 332.3C590.7 368 559.4 420 512.6 463.4C465.5 507.1 400.8 544 320 544C239.2 544 174.5 507.2 127.4 463.4C80.6 419.9 49.3 368 34.4 332.3C31.1 324.4 31.1 315.6 34.4 307.7C49.3 272 80.6 220 127.4 176.6zM320 400C364.2 400 400 364.2 400 320C400 290.4 383.9 264.5 360 250.7C358.6 310.4 310.4 358.6 250.7 360C264.5 383.9 290.4 400 320 400zM240.4 311.6C242.9 311.9 245.4 312 248 312C283.3 312 312 283.3 312 248C312 245.4 311.8 242.9 311.6 240.4C274.2 244.3 244.4 274.1 240.5 311.5zM286 196.6C296.8 193.6 308.2 192.1 319.9 192.1C328.7 192.1 337.4 193 345.7 194.7C346 194.8 346.2 194.8 346.5 194.9C404.4 207.1 447.9 258.6 447.9 320.1C447.9 390.8 390.6 448.1 319.9 448.1C258.3 448.1 206.9 404.6 194.7 346.7C192.9 338.1 191.9 329.2 191.9 320.1C191.9 309.1 193.3 298.3 195.9 288.1C196.1 287.4 196.2 286.8 196.4 286.2C208.3 242.8 242.5 208.6 285.9 196.7z" />
                                            </svg>
                                            Lihat Detail
                                        </x-secondary-button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

            </div>
        </template>

        <!-- Tampilan Ketiadaan Riwayat -->
        <template x-if="groupedPembayarans.length === 0">
            <x-list-empty title="Tidak Ada Riwayat Pekerjaan" subtitle="Semua riwayat pekerjaan akan tampil disini">
                <x-slot:icon>
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M9 8h6m2 12H7a2 2 0 01-2-2V6a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V18a2 2 0 01-2 2z" />
                    </svg>
                </x-slot:icon>
            </x-list-empty>
        </template>
    </div>

    <!-- MODAL DETAIL RIWAYAT PEKERJAAN (Tunggal di Luar Loop) -->
    <x-modals.detail-riwayat-pekerjaan brdr=""></x-modals.detail-riwayat-pekerjaan>

</div>

<!-- Alpine JS Controller -->
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('riwayatComponent', () => ({
            pembayarans: @js($pembayarans),
            modalDetail: false,
            lihatDetail: null,

            openModal(p) {
                this.lihatDetail = p;
                this.modalDetail = true;
            },

            // Menyaring dan mengelompokkan data secara real-time berdasarkan pencarian, bulan, dan tahun
            get groupedPembayarans() {
                // Pengelompokan data per Bulan-Tahun dari backend
                let groups = {};
                this.pembayarans.forEach(p => {
                    let date = new Date(p.updated_at);
                    let monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                    let key = monthNames[date.getMonth()] + ' ' + date.getFullYear();

                    if (!groups[key]) {
                        groups[key] = {
                            monthYear: key,
                            monthIndex: date.getMonth(),
                            year: date.getFullYear(),
                            items: []
                        };
                    }
                    groups[key].items.push(p);
                });

                // Urutkan kelompok bulan terbaru di atas (descending)
                return Object.values(groups).sort((a, b) => {
                    if (b.year !== a.year) {
                        return b.year - a.year;
                    }
                    return b.monthIndex - a.monthIndex;
                });
            }
        }));
    });
</script>
@endsection