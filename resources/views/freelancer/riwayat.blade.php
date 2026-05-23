@extends('layouts.body', ['title' => 'Dashboard'])

@section('content')
<x-header
    :judul="'Riwayat Pekerjaan'"
    :subjudul="'Lihat aspek penilaian dan bobotnya'" />

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl p-6 border border-gray-200">
        <p class="text-gray-500 mb-2">Total Proyek</p>
        <p class="text-3xl text-blue-600">{{ $totalProyek }}</p>
    </div>
    <div class="bg-white rounded-xl p-6 border border-gray-200">
        <p class="text-gray-500 mb-2">Sudah Dibayar</p>
        <p class="text-3xl text-green-600">{{ $sudahDibayar }}</p>
    </div>
    <div class="bg-white rounded-xl p-6 border border-gray-200">
        <p class="text-gray-500 mb-2">Belum Dibayar</p>
        <p class="text-3xl text-orange-600">{{ $belumDibayar }}</p>
    </div>
    <div class="bg-white rounded-xl p-6 border border-gray-200">
        <p class="text-gray-500 mb-2">Total Pendapatan</p>
        <p class="text-3xl text-blue-600">
            {{ number_format($totalPendapatan, 0, ',', '.') }}
        </p>
    </div>
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

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-gray-900 font-semibold">Daftar Riwayat Pekerjaan</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-gray-500">Nama Proyek</th>
                    <th class="px-6 py-3 text-left text-gray-500">Sub Proyek</th>
                    <th class="px-6 py-3 text-left text-gray-500">Rentang Halaman</th>
                    <th class="px-6 py-3 text-left text-gray-500">Tanggal Selesai</th>
                    <th class="px-6 py-3 text-left text-gray-500">Total Upah</th>
                    <th class="px-6 py-3 text-left text-gray-500">Status Pembayaran</th>
                    <th class="px-6 py-3 text-left text-gray-500">Aksi</th>
                </tr>
            </thead>
            <tbody x-data="{
                        modalDetail : false,
                        lihatDetail : null}" class="divide-y divide-gray-200">
                @forelse ($pembayarans as $pembayaran)
                <tr>
                    <td class="px-6 py-4">
                        <p class="text-gray-900">{{ $pembayaran->penilaian->pengambilan->subsubproyeks->subproyeks->proyeks->nama_proyek }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-500">{{ $pembayaran->penilaian->pengambilan->subsubproyeks->subproyeks->nama_sub_proyek }}</p>
                        <p class="text-gray-500 text-xs">{{ $pembayaran->penilaian->pengambilan->subsubproyeks->nama_halaman }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-500">{{ $pembayaran->penilaian->pengambilan->dari_halaman }} - {{ $pembayaran->penilaian->pengambilan->sampai_halaman }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-500">{{ $pembayaran->updated_at->format('d/m/Y H:i') }}</xp>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-900">Rp. {{ number_format($pembayaran->total_pembayaran, 0, ',', '.') }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span @class([ 'inline-flex px-3 py-1 rounded-full text-xs' , 'bg-green-200 text-green-800'=> $pembayaran->status === 'sudah_dibayar',
                            'bg-red-200 text-red-800' => $pembayaran->status === 'belum_dibayar'
                            ])>
                            {{ $pembayaran->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <button @click="
                                    modalDetail = true;
                                    lihatDetail = @js($pembayaran)"
                            class="flex items-center px-3 py-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                                <path fill="currentColor" d="M320 144C254.8 144 201.2 173.6 160.1 211.7C121.6 247.5 95 290 81.4 320C95 350 121.6 392.5 160.1 428.3C201.2 466.4 254.8 496 320 496C385.2 496 438.8 466.4 479.9 428.3C518.4 392.5 545 350 558.6 320C545 290 518.4 247.5 479.9 211.7C438.8 173.6 385.2 144 320 144zM127.4 176.6C174.5 132.8 239.2 96 320 96C400.8 96 465.5 132.8 512.6 176.6C559.4 220.1 590.7 272 605.6 307.7C608.9 315.6 608.9 324.4 605.6 332.3C590.7 368 559.4 420 512.6 463.4C465.5 507.1 400.8 544 320 544C239.2 544 174.5 507.2 127.4 463.4C80.6 419.9 49.3 368 34.4 332.3C31.1 324.4 31.1 315.6 34.4 307.7C49.3 272 80.6 220 127.4 176.6zM320 400C364.2 400 400 364.2 400 320C400 290.4 383.9 264.5 360 250.7C358.6 310.4 310.4 358.6 250.7 360C264.5 383.9 290.4 400 320 400zM240.4 311.6C242.9 311.9 245.4 312 248 312C283.3 312 312 283.3 312 248C312 245.4 311.8 242.9 311.6 240.4C274.2 244.3 244.4 274.1 240.5 311.5zM286 196.6C296.8 193.6 308.2 192.1 319.9 192.1C328.7 192.1 337.4 193 345.7 194.7C346 194.8 346.2 194.8 346.5 194.9C404.4 207.1 447.9 258.6 447.9 320.1C447.9 390.8 390.6 448.1 319.9 448.1C258.3 448.1 206.9 404.6 194.7 346.7C192.9 338.1 191.9 329.2 191.9 320.1C191.9 309.1 193.3 298.3 195.9 288.1C196.1 287.4 196.2 286.8 196.4 286.2C208.3 242.8 242.5 208.6 285.9 196.7z" />
                            </svg>
                            <span>Lihat Detail</span>
                        </button>

                        <div x-show="modalDetail" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                            <div class="bg-white rounded-xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
                                <div class="p-6 border-b border-gray-200 sticky top-0 bg-white z-10">
                                    <h2 class="text-gray-900">Detail Riwayat Pekerjaan</h2>
                                    <p class="text-gray-500 text-sm mt-1">
                                        Informasi lengkap proyek yang telah diselesaikan
                                    </p>
                                </div>

                                <div class="p-6">
                                    <div class="bg-gray-50 rounded-xl p-6 mb-6">
                                        <h3 class="text-gray-900 mb-4">Informasi Proyek</h3>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <p class="text-gray-500 text-sm mb-1">Nama Proyek</p>
                                                <p class="text-gray-900" x-text="lihatDetail?.penilaian?.pengambilan?.subsubproyeks?.subproyeks?.proyeks?.nama_proyek ?? '-'"></p>
                                            </div>
                                            <div>
                                                <p class="text-gray-500 text-sm mb-1">Sub Proyek</p>
                                                <p class="text-gray-900" x-text="lihatDetail?.penilaian?.pengambilan?.subsubproyeks?.subproyeks?.nama_sub_proyek ?? '-'"></p>
                                            </div>
                                            <div>
                                                <p class="text-gray-500 text-sm mb-1">Sub Sub Proyek</p>
                                                <p class="text-gray-900" x-text="lihatDetail?.penilaian?.pengambilan?.subsubproyeks?.nama_halaman ?? '-'"></p>
                                            </div>
                                            <div>
                                                <p class="text-gray-500 text-sm mb-1">Rentang Halaman</p>
                                                <div class="flex items-center">
                                                    <span class="text-gray-900" x-text="lihatDetail?.penilaian?.pengambilan?.dari_halaman ?? '-'"></span> -
                                                    <span class="text-gray-900" x-text="lihatDetail?.penilaian?.pengambilan?.sampai_halaman ?? '-'"></span>
                                                </div>
                                            </div>
                                            <div>
                                                <p class=" text-gray-500 text-sm mb-1">Tanggal Selesai</p>
                                                <p class="text-gray-900" x-text="lihatDetail?.update_at"></p>
                                            </div>
                                            <div>
                                                <p class="text-gray-500 text-sm mb-1">Total Upah</p>
                                                <p class="text-2xl text-blue-600" x-text="lihatDetail?.total_pembayaran 
                                                            ? new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(lihatDetail.total_pembayaran) 
                                                            : '-'"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-gray-50 rounded-xl p-6 mb-6">
                                        <h3 class="text-gray-900 mb-4">File Hasil yang Diupload</h3>
                                        <button class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                            Download File Hasil (.xlsx)
                                        </button>
                                    </div>

                                    <div class="bg-gray-50 rounded-xl p-6 mb-6">
                                        <h3 class="text-gray-900 mb-4">Nilai Akhir</h3>
                                        <div class="flex items-center gap-4">
                                            <div class="w-20 h-20 rounded-full bg-blue-600 flex items-center justify-center">
                                                <span class="text-2xl text-white" x-text="lihatDetail?.penilaian?.total_skor"></span>
                                            </div>
                                            <div>
                                                <p class="text-gray-900">Total Skor Penilaian</p>
                                                <p class="text-gray-500 text-sm">
                                                    Dari rata-rata akurasi, kecepatan, dan kualitas
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-gray-50 rounded-xl p-6">
                                        <h3 class="text-gray-900 mb-4">Status Pembayaran</h3>
                                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full"
                                            :class="{
                                                        'text-green-800 bg-green-200' : lihatDetail?.status === 'sudah_dibayar',
                                                        'text-red-800 bg-red-200' : lihatDetail?.status === 'belum_dibayar',
                                                        }" x-text="lihatDetail?.status 
                                                    ? lihatDetail.status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) 
                                                    : '-'"></span>

                                        <template x-if="lihatDetail?.bukti_transfer">
                                            <div>
                                                <p class="text-sm text-gray-500 mb-2">Bukti Transfer:</p>

                                                <img :src="'storage/'+ lihatDetail.bukti_transfer"
                                                    class="rounded border max-h-60 mb-3">

                                                <div class="bg-green-100 text-green-700 p-3 rounded">
                                                    Pembayaran telah dikonfirmasi
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <div class="p-6 border-t border-gray-200 sticky bottom-0 bg-white">
                                    <button @click="modalDetail = false" class="w-full px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        Belum ada riwayat pekerjaan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection