@extends('layouts.body', ['title' => 'Riwayat Proyek'])

@section('content')
<x-header
    :judul="'Riwayat Proyek'"
    :subjudul="'Data Proyek yang telah selesai'" />

<form action="" method="GET">
    <div class="bg-white rounded-xl border border-gray-200 p-5 mb-6 shadow-sm">

        <div class="flex flex-col md:flex-row md:items-end gap-4">

            {{-- SEARCH --}}
            <div class="flex-1 relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" viewBox="0 0 640 640" fill="currentColor">
                    <path d="M480 272C480 317.9 465.1 360.3 440 394.7L566.6 521.4C579.1 533.9 579.1 554.2 566.6 566.7C554.1 579.2 533.8 579.2 521.3 566.7L394.7 440C360.3 465.1 317.9 480 272 480C157.1 480 64 386.9 64 272C64 157.1 157.1 64 272 64C386.9 64 480 157.1 480 272zM272 416C351.5 416 416 351.5 416 272C416 192.5 351.5 128 272 128C192.5 128 128 192.5 128 272C128 351.5 192.5 416 272 416z" />
                </svg>

                <input
                    type="text"
                    name="cari"
                    value="{{ request('cari') }}"
                    placeholder="Cari proyek..."
                    class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent">
            </div>

            {{-- STATUS --}}
            <div class="w-full md:w-52 relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" viewBox="0 0 640 640" fill="currentColor">
                    <path d="M96 128C83.1 128 71.4 135.8 66.4 147.8C61.4 159.8 64.2 173.5 73.4 182.6L256 365.3L256 480C256 488.5 259.4 496.6 265.4 502.6L329.4 566.6C338.6 575.8 352.3 578.5 364.3 573.5C376.3 568.5 384 556.9 384 544L384 365.3L566.6 182.7C575.8 173.5 578.5 159.8 573.5 147.8C568.5 135.8 556.9 128 544 128L96 128z" />
                </svg>

                <select name="status"
                    class="w-full pl-10 pr-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent bg-white">
                    <option value="">Semua status</option>
                    <option value="selesai" @selected(request('status')==='selesai' )>Selesai</option>
                    <option value="dibatalkan" @selected(request('status')==='dibatalkan' )>Dibatalkan</option>
                </select>
            </div>

            {{-- BUTTON --}}
            <div class="w-full md:w-auto flex gap-2">

                <button type="submit"
                    class="px-5 py-2.5 text-sm font-semibold bg-brand-500 text-white rounded-lg hover:bg-blue-700 transition w-full md:w-auto">
                    Cari
                </button>

                {{-- RESET --}}
                <a href="{{ url()->current() }}"
                    class="px-5 py-2.5 text-sm font-semibold border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-100 transition text-center">
                    Reset
                </a>

            </div>

        </div>

    </div>
</form>

@forelse($proyeks as $proyek)

@php
$totalPendapatan = $proyek->subproyeks->flatMap->subsubproyeks->flatMap->pengambilans->sum('total_harga');
@endphp

<div class="bg-white border border-gray-200 rounded-xl overflow-hidden mb-3"
    x-data="{ openProyek: false }">

    {{-- ROW PROYEK --}}
    <div class="flex items-center justify-between px-5 py-4 cursor-pointer hover:bg-gray-50 transition"
        @click="openProyek = !openProyek">
        <div class="flex items-center gap-3">
            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200"
                :class="openProyek ? 'rotate-90' : ''"
                viewBox="0 0 20 20" fill="none">
                <path d="M7 4l6 6-6 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <div>
                <p class="text-sm font-semibold text-gray-800">{{ $proyek->nama_proyek }}</p>
                <p class="text-xs text-gray-400 mt-0.5">
                    {{ $proyek->tanggal_mulai->format('d M Y') }} – {{ $proyek->tanggal_selesai->format('d M Y') }}
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-sm text-gray-500">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</span>
            @if($proyek->status === 'selesai')
            <span class="rounded-full px-2.5 py-1 text-xs bg-green-100 text-green-800">Selesai</span>
            @else
            <span class="rounded-full px-2.5 py-1 text-xs bg-red-100 text-red-800">Dibatalkan</span>
            @endif
        </div>
    </div>

    {{-- LEVEL 2: SUBPROYEK --}}
    <div x-show="openProyek" x-transition x-cloak
        class="border-t border-gray-100 bg-gray-50 p-4 space-y-3">

        @foreach($proyek->subproyeks as $sub)
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden"
            x-data="{ openSub: false }">

            {{-- ROW SUBPROYEK --}}
            <div class="flex items-center justify-between px-4 py-3 cursor-pointer hover:bg-gray-50 transition"
                @click="openSub = !openSub">
                <div class="flex items-center gap-2.5">
                    <svg class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200"
                        :class="openSub ? 'rotate-90' : ''"
                        viewBox="0 0 20 20" fill="none">
                        <path d="M7 4l6 6-6 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700">{{ $sub->nama_sub_proyek }}</span>
                </div>
                <span class="text-xs text-gray-400">{{ $sub->total_halaman }} hal</span>
            </div>

            {{-- LEVEL 3: SUBSUBPROYEK --}}
            <div x-show="openSub" x-transition x-cloak
                class="border-t border-gray-100 bg-gray-50 p-3 space-y-2">

                @foreach($sub->subsubproyeks as $sss)
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden"
                    x-data="{ openSss: false }">

                    {{-- ROW SUBSUBPROYEK --}}
                    <div class="flex items-center justify-between px-3 py-2.5 cursor-pointer hover:bg-gray-50 transition"
                        @click="openSss = !openSss">
                        <div class="flex items-center gap-2">
                            <svg class="w-3 h-3 text-gray-400 transition-transform duration-200"
                                :class="openSss ? 'rotate-90' : ''"
                                viewBox="0 0 20 20" fill="none">
                                <path d="M7 4l6 6-6 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span class="text-xs font-medium text-gray-700">{{ $sss->nama_subsubproyek }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-xs text-gray-400">
                            <span>{{ $sss->total_halaman }} hal</span>
                            <span>Rp {{ number_format($sss->total_halaman * $sss->harga_perlembar, 0, ',', '.') }}</span>
                            <span class="text-gray-300">|</span>
                            <span class="text-blue-500">{{ $sss->pengambilans->count() }} pengambil</span>
                        </div>
                    </div>

                    {{-- LEVEL 4: TABEL PENGAMBILAN --}}
                    <div x-show="openSss" x-transition x-cloak
                        class="border-t border-gray-100 bg-gray-50 p-3">

                        {{-- CHIPS RINGKASAN --}}
                        <div class="flex gap-2 mb-3 flex-wrap">
                            <span class="text-xs bg-white border border-gray-200 rounded-md px-2.5 py-1 text-gray-500">
                                <span class="font-medium text-gray-700">{{ $sss->pengambilans->count() }}</span> pengambil
                            </span>
                            <span class="text-xs bg-white border border-gray-200 rounded-md px-2.5 py-1 text-gray-500">
                                Total <span class="font-medium text-gray-700">{{ $sss->pengambilans->sum('total_halaman') }}</span> hal
                            </span>
                            <span class="text-xs bg-white border border-gray-200 rounded-md px-2.5 py-1 text-gray-500">
                                Pendapatan <span class="font-medium text-gray-700">Rp {{ number_format($sss->pengambilans->sum('total_harga'), 0, ',', '.') }}</span>
                            </span>
                        </div>

                        {{-- TABEL --}}
                        @if($sss->pengambilans->count() > 0)
                        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                            <table class="w-full text-xs">
                                <thead class="bg-gray-50 text-gray-500">
                                    <tr>
                                        <th class="px-3 py-2 text-left w-6">#</th>
                                        <th class="px-3 py-2 text-left">Nama user</th>
                                        <th class="px-3 py-2 text-center">Dari hal</th>
                                        <th class="px-3 py-2 text-center">Sampai hal</th>
                                        <th class="px-3 py-2 text-center">Total harga</th>
                                        <th class="px-3 py-2 text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($sss->pengambilans->sortBy('dari_halaman') as $index => $ambil)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-3 py-2 text-gray-400">{{ $index + 1 }}</td>
                                        <td class="px-3 py-2 font-medium text-gray-700">{{ $ambil->user->name }}</td>
                                        <td class="px-3 py-2 text-center">{{ $ambil->dari_halaman }}</td>
                                        <td class="px-3 py-2 text-center">{{ $ambil->sampai_halaman }}</td>
                                        <td class="px-3 py-2 text-center">Rp {{ number_format($ambil->total_harga, 0, ',', '.') }}</td>
                                        <td class="px-3 py-2 text-center">
                                            @if($ambil->status === 'selesai')
                                            <span class="rounded-full px-2 py-0.5 text-[10px] bg-green-100 text-green-800">Selesai</span>
                                            @elseif($ambil->status === 'diambil')
                                            <span class="rounded-full px-2 py-0.5 text-[10px] bg-blue-100 text-blue-800">Diambil</span>
                                            @elseif($ambil->status === 'menunggu')
                                            <span class="rounded-full px-2 py-0.5 text-[10px] bg-yellow-100 text-yellow-800">Menunggu</span>
                                            @elseif($ambil->status === 'dibatalkan')
                                            <span class="rounded-full px-2 py-0.5 text-[10px] bg-red-100 text-red-800">Dibatalkan</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <p class="text-center text-xs text-gray-400 py-4">Belum ada pengambilan untuk subsubproyek ini</p>
                        @endif

                    </div>
                </div>
                @endforeach

            </div>
        </div>
        @endforeach

    </div>
</div>

@empty
<div class="text-center py-16 text-gray-400 bg-white border border-gray-200 rounded-xl overflow-hidden mb-3">
    <p class="text-lg mb-1">Belum ada riwayat proyek</p>
    <p class="text-sm">Proyek yang selesai atau dibatalkan akan muncul di sini</p>
</div>
@endforelse

</div>
</div>

@endsection