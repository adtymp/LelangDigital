@extends('layouts.body', ['title' => 'Dashboard'])

@section('content')

<div class="mb-8 mt-12">
    <a href="{{ url()->previous() }}"
        class="inline-flex items-center gap-1.5 text-sm text-gray-500 border border-gray-200 rounded-lg px-3 py-1.5 hover:bg-gray-100 transition">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 20 20" fill="none">
            <path d="M13 4L7 10l6 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        Kembali
    </a>
    <div>
        <p class="text-blue-600 text-2xl font-semibold mb-2">Detail Pengambilan</p>
        <p class="text-gray-500">Daftar user yang mengambil bagian dari
            <span class="font-medium text-gray-700">{{ $subsubproyek->nama_halaman }}</span>,
            diurutkan berdasarkan halaman.
        </p>
    </div>
</div>

{{-- BREADCRUMB --}}
<nav class="text-sm text-gray-500 mb-4">
    <a href="{{ route('dashboard.admin') }}" class="hover:text-blue-600">Dashboard</a>
    <span class="mx-2">/</span>
    <span>{{ $subsubproyek->subproyeks->proyeks->nama_proyek }}</span>
    <span class="mx-2">/</span>
    <span>{{ $subsubproyek->subproyeks->nama_sub_proyek }}</span>
    <span class="mx-2">/</span>
    <span class="text-gray-800 font-medium">{{ $subsubproyek->nama_halaman }}</span>
</nav>


{{-- KARTU RINGKASAN --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-gray-50 rounded-xl border border-gray-200 px-5 py-4">
        <p class="text-xs text-gray-500 mb-1">Total pengambil</p>
        <p class="text-2xl font-semibold text-gray-800">{{ $totalPengambil }} user</p>
    </div>
    <div class="bg-gray-50 rounded-xl border border-gray-200 px-5 py-4">
        <p class="text-xs text-gray-500 mb-1">Total halaman diambil</p>
        <p class="text-2xl font-semibold text-gray-800">{{ number_format($totalHalaman, 0, ',', '.') }} hal</p>
    </div>
    <div class="bg-gray-50 rounded-xl border border-gray-200 px-5 py-4">
        <p class="text-xs text-gray-500 mb-1">Total pendapatan</p>
        <p class="text-2xl font-semibold text-gray-800">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
    </div>
</div>

{{-- TABEL PENGAMBILAN --}}
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">

    {{-- HEADER TABEL --}}
    <div class="flex items-center justify-between px-6 py-4 border-b bg-gray-50">
        <h2 class="text-sm font-semibold text-gray-700">Daftar pengambilan</h2>
        <span class="text-xs text-gray-400">Diurutkan: dari halaman (asc)</span>
    </div>

    {{-- TABLE --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-gray-700">
            <thead class="bg-gray-50 text-gray-500 text-xs">
                <tr>
                    <th class="px-4 py-3 text-left w-8">#</th>
                    <th class="px-4 py-3 text-left">Nama user</th>
                    <th class="px-4 py-3 text-center">Dari hal</th>
                    <th class="px-4 py-3 text-center">Sampai hal</th>
                    <th class="px-4 py-3 text-center">Total harga</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pengambilans as $index => $item)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 text-gray-400">{{ $index + 1 }}</td>
                    <td class="px-4 py-3 font-medium">{{ $item->user->name }}</td>
                    <td class="px-4 py-3 text-center">{{ $item->dari_halaman }}</td>
                    <td class="px-4 py-3 text-center">{{ $item->sampai_halaman }}</td>
                    <td class="px-4 py-3 text-center">
                        Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if ($item->status === 'selesai')
                        <span class="rounded-full px-2.5 py-1 text-xs bg-green-100 text-green-800">Selesai</span>
                        @elseif ($item->status === 'diambil')
                        <span class="rounded-full px-2.5 py-1 text-xs bg-blue-100 text-blue-800">Diambil</span>
                        @elseif ($item->status === 'menunggu')
                        <span class="rounded-full px-2.5 py-1 text-xs bg-yellow-100 text-yellow-800">Menunggu</span>
                        @elseif ($item->status === 'dibatalkan')
                        <span class="rounded-full px-2.5 py-1 text-xs bg-red-100 text-red-800">Dibatalkan</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 640 640">
                            <path fill="currentColor" d="M416 208C416 305.2 330 384 224 384C197.3 384 171.9 379 148.8 370L67.2 413.2C57.9 418.1 46.5 416.4 39 409C31.5 401.6 29.8 390.1 34.8 380.8L70.4 313.6C46.3 284.2 32 247.6 32 208C32 110.8 118 32 224 32C330 32 416 110.8 416 208zM416 576C321.9 576 243.6 513.9 227.2 432C347.2 430.5 451.5 345.1 463 229.3C546.3 248.5 608 317.6 608 400C608 439.6 593.7 476.2 569.6 505.6L605.2 572.8C610.1 582.1 608.4 593.5 601 601C593.6 608.5 582.1 610.2 572.8 605.2L491.2 562C468.1 571 442.7 576 416 576z" />
                        </svg>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-12 text-gray-400">
                        📭 Belum ada user yang mengambil subsubproyek ini
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