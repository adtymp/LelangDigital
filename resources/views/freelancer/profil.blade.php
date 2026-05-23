@extends('layouts.body', ['title' => 'Dashboard'])

@section('content')
<x-header
    :judul="'Profil Saya'"
    :subjudul="'Informasi akun, progres level, statistik performa, dan riwayat poin Anda'" />

{{-- Hero Profile --}}
<div class="relative overflow-hidden rounded-3xl bg-linear-to-r from-brand-500 via-brand-700 to-brand-500 p-6 sm:p-8 text-white shadow-lg mb-8">
    <div class="absolute -top-16 -right-16 h-48 w-48 rounded-full bg-white/10 blur-2xl"></div>
    <div class="absolute -bottom-16 -left-16 h-48 w-48 rounded-full bg-white/10 blur-2xl"></div>

    <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
        <div class="flex items-center gap-5">
            <div class="h-24 w-24 rounded-2xl bg-white/10 backdrop-blur flex items-center justify-center ring-1 ring-white/20 shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 text-white" viewBox="0 0 640 640" fill="currentColor">
                    <path d="M463 448.2C440.9 409.8 399.4 384 352 384L288 384C240.6 384 199.1 409.8 177 448.2C212.2 487.4 263.2 512 320 512C376.8 512 427.8 487.3 463 448.2zM64 320C64 178.6 178.6 64 320 64C461.4 64 576 178.6 576 320C576 461.4 461.4 576 320 576C178.6 576 64 461.4 64 320zM320 336C359.8 336 392 303.8 392 264C392 224.2 359.8 192 320 192C280.2 192 248 224.2 248 264C248 303.8 280.2 336 320 336z" />
                </svg>
            </div>

            <div>
                <h2 class="text-2xl font-bold">{{ $user->name }}</h2>
                <p class="mt-1 text-blue-100 text-sm">{{ $user->email }}</p>

                <div class="mt-3 inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-sm font-medium backdrop-blur ring-1 ring-white/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-300" viewBox="0 0 640 640" fill="currentColor">
                        <path d="M320.3 192L235.7 51.1C229.2 40.3 215.6 36.4 204.4 42L117.8 85.3C105.9 91.2 101.1 105.6 107 117.5L176.6 256.6C146.5 290.5 128.3 335.1 128.3 384C128.3 490 214.3 576 320.3 576C426.3 576 512.3 490 512.3 384C512.3 335.1 494 290.5 464 256.6L533.6 117.5C539.5 105.6 534.7 91.2 522.9 85.3L436.2 41.9C425 36.3 411.3 40.3 404.9 51L320.3 192zM351.1 334.5C352.5 337.3 355.1 339.2 358.1 339.6L408.2 346.9C415.9 348 418.9 357.4 413.4 362.9L377.1 398.3C374.9 400.5 373.9 403.5 374.4 406.6L383 456.5C384.3 464.1 376.3 470 369.4 466.4L324.6 442.8C321.9 441.4 318.6 441.4 315.9 442.8L271.1 466.4C264.2 470 256.2 464.2 257.5 456.5L266.1 406.6C266.6 403.6 265.6 400.5 263.4 398.3L227.1 362.9C221.5 357.5 224.6 348.1 232.3 346.9L282.4 339.6C285.4 339.2 288.1 337.2 289.4 334.5L311.8 289.1C315.2 282.1 325.1 282.1 328.6 289.1L351 334.5z" />
                    </svg>
                    <span>Level {{ $user->level->nama_level ?? '-' }}</span>
                </div>
            </div>
        </div>

        {{-- Summary Stats --}}
        <div>
            <div x-data="{
                            profil: false,
                            modal: false,
                            pilihOpsi: null,
                            lihatPasswordLama : false,
                            lihatPasswordBaru : false,
                            lihatKonfirmasiPassword : false,
                            passwordLama : '',
                            passwordBaru : '',
                            konfirmasiPassword : ''
                            }" class="relative flex justify-end p-2">

                <!-- BUTTON -->
                <button
                    @click="profil = !profil"
                    class="text-white hover:bg-white/20 p-2 rounded-lg transition"
                    type="button">

                    <svg xmlns="http://www.w3.org/2000/svg" height="25" width="25" viewBox="0 0 640 640">
                        <path fill="currentColor" d="M320 208C289.1 208 264 182.9 264 152C264 121.1 289.1 96 320 96C350.9 96 376 121.1 376 152C376 182.9 350.9 208 320 208zM320 432C350.9 432 376 457.1 376 488C376 518.9 350.9 544 320 544C289.1 544 264 518.9 264 488C264 457.1 289.1 432 320 432zM376 320C376 350.9 350.9 376 320 376C289.1 376 264 350.9 264 320C264 289.1 289.1 264 320 264C350.9 264 376 289.1 376 320z" />
                    </svg>
                </button>

                <!-- DROPDOWN -->
                <div
                    x-show="profil"
                    @click.outside="profil = false"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 top-full mt-2 w-44 bg-white text-gray-700 rounded-xl shadow-xl overflow-hidden z-50">

                    <p class="block px-4 py-2 border-b text-center text-blue-600">Update Profil</p>
                    <button @click="modal=true; pilihOpsi='telepon';" type="button" class="block px-4 py-2 hover:bg-blue-100 w-full text-start">Telepon</button>
                    <button @click="modal=true; pilihOpsi='password';" type="button" class="block px-4 py-2 hover:bg-blue-100 w-full text-start">Password</button>
                    <div
                        x-show="modal"
                        x-transition
                        class="fixed inset-0 z-50 flex items-center justify-center p-4">

                        <!-- BACKDROP -->
                        <div
                            class="absolute inset-0 bg-black/50 backdrop-blur-sm"
                            @click="modal = false">
                        </div>

                        <!-- CONTENT -->
                        <div class="relative bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden">

                            <!-- HEADER -->
                            <div
                                class="bg-linear-to-r from-blue-600 to-indigo-600 px-6 py-5 text-white flex items-center justify-between">

                                <div>
                                    <h2 class="text-xl font-bold">
                                        Pengaturan Profil
                                    </h2>
                                    <p class="text-sm text-blue-100 mt-1">
                                        Kelola informasi akun anda
                                    </p>
                                </div>

                                <button
                                    @click="modal = false"
                                    class="h-10 w-10 rounded-full hover:bg-white/20 flex items-center justify-center transition">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor">

                                        <path stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div class="flex border-b bg-gray-50">
                                <button
                                    type="button"
                                    @click="pilihOpsi='telepon'"
                                    :class="pilihOpsi === 'telepon'? 'bg-white text-blue-600 border-b-2 border-blue-600': 'text-gray-500 hover:text-blue-600'"
                                    class="flex-1 py-3 font-medium">

                                    Telepon
                                </button>

                                <button
                                    type="button"
                                    @click="pilihOpsi='password'"
                                    :class="pilihOpsi === 'password' ? 'bg-white text-blue-600 border-b-2 border-blue-600': 'text-gray-500 hover:text-blue-600'"
                                    class="flex-1 py-3 font-medium">

                                    Password
                                </button>
                            </div>
                            <div class="p-6">

                                <!-- TELEPON -->
                                <div x-show="pilihOpsi === 'telepon'">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-1">
                                        Ubah Nomor Telepon
                                    </h3>

                                    <p class="text-sm text-gray-500 mb-5">
                                        Pastikan nomor yang dimasukkan aktif.
                                    </p>

                                    <div>
                                        <form action="{{ route('profil.updateTelepon', $user->id) }}" method="post">
                                            @csrf
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    Nomor Telepon Baru
                                                </label>

                                                <input
                                                    type="text" name="no_telp"
                                                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-700 focus:outline-none"
                                                    placeholder="08xxxxxxxxxx">
                                            </div>

                                            <button
                                                type="submit"
                                                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-medium">
                                                Simpan Perubahan
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- PASSWORD -->
                                <div x-show="pilihOpsi === 'password'">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-1">
                                        Ubah Password
                                    </h3>

                                    <p class="text-sm text-gray-500 mb-5">
                                        Gunakan password yang kuat dan aman.
                                    </p>

                                    <form action="{{ route('profil.updatePassword', $user->id) }}" method="post">
                                        @csrf
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Password Lama</label>
                                                <div class="relative">
                                                    <input :type="lihatPasswordLama ? 'text' : 'password'" name="password_lama" required x-model="passwordLama" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-700 focus:outline-none" placeholder="••••••••" />
                                                    <button type="button" class="absolute right-3 top-3 text-gray-400" @click="lihatPasswordLama = !lihatPasswordLama">
                                                        <i :class="lihatPasswordLama ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                                                <div class="relative">
                                                    <input :type="lihatPasswordBaru ? 'text' : 'password'" name="password" required x-model="passwordBaru" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-700 focus:outline-none" placeholder="••••••••" />
                                                    <button type="button" class="absolute right-3 top-3 text-gray-400" @click="lihatPasswordBaru = !lihatPasswordBaru">
                                                        <i :class="lihatPasswordBaru ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                                                <div class="relative">
                                                    <input :type="lihatKonfirmasiPassword ? 'text' : 'password'" name="password_confirmation" required x-model="konfirmasiPassword" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-700 focus:outline-none" placeholder="••••••••" />
                                                    <button type="button" class="absolute right-3 top-3 text-gray-400" @click="lihatKonfirmasiPassword = !lihatKonfirmasiPassword">
                                                        <i :class="lihatKonfirmasiPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <button
                                                type="submit"
                                                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-medium ">
                                                Update Password
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 w-full lg:w-auto">
                <div class="rounded-2xl bg-white/10 backdrop-blur p-4 ring-1 ring-white/15">
                    <p class="text-sm text-blue-100">Total Proyek</p>
                    <p class="mt-2 text-3xl font-bold">{{ $totalProyek }}</p>
                </div>
                <div class="rounded-2xl bg-white/10 backdrop-blur p-4 ring-1 ring-white/15">
                    <p class="text-sm text-blue-100">Proyek Selesai</p>
                    <p class="mt-2 text-3xl font-bold">{{ $totalProyekSelesai }}</p>
                </div>
                <div class="rounded-2xl bg-white/10 backdrop-blur p-4 ring-1 ring-white/15">
                    <p class="text-sm text-blue-100">Total Pendapatan</p>
                    <p class="mt-2 text-3xl font-bold">
                        Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Main Content --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    {{-- Left Content --}}
    <div class="xl:col-span-2 space-y-6">
        {{-- Progress Level --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-lg font-semibold text-slate-800">Progress Level</h2>
                    <p class="text-sm text-slate-500 mt-1">Pantau perkembangan poin dan level akun Anda.</p>
                </div>
                <div class="rounded-full bg-blue-50 px-3 py-1 text-sm font-medium text-blue-700">
                    {{ $levelSelanjutnya ? 'Menuju Level Berikutnya' : 'Level Maksimal' }}
                </div>
            </div>

            <div class="mb-4 flex items-center justify-between text-sm">
                <span class="font-medium text-slate-700">Level {{ $levelSekarang->nama_level ?? '-' }}</span>
                <span class="font-medium text-slate-500">
                    {{ $levelSelanjutnya ? 'Level ' . $levelSelanjutnya->nama_level : 'MAX' }}
                </span>
            </div>

            <div class="w-full rounded-full bg-slate-200 h-4 overflow-hidden">
                <div class="h-4 rounded-full bg-linear-to-r from-blue-500 to-indigo-600 transition-all duration-500"
                    style="width: {{ $progress }}%">
                </div>
            </div>

            <div class="mt-3 text-sm text-slate-500">
                @if ($levelSelanjutnya)
                {{ $user->poin_level }} / {{ $poinSelanjutnya }} poin menuju level berikutnya
                @else
                Level tertinggi telah tercapai
                @endif
            </div>

            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="rounded-2xl bg-blue-50 border border-blue-100 p-5">
                    <p class="text-sm text-blue-700">Total Poin</p>
                    <p class="mt-2 text-3xl font-bold text-blue-700">{{ $user->poin_level }} poin</p>
                </div>

                <div class="rounded-2xl bg-slate-50 border border-slate-200 p-5">
                    <p class="text-sm text-slate-500">Status Level</p>
                    <p class="mt-2 text-xl font-semibold text-slate-800">
                        {{ $levelSelanjutnya ? 'Masih berkembang' : 'Level tertinggi' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Statistik Performa --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-slate-800">Statistik Performa</h2>
                <p class="text-sm text-slate-500 mt-1">Ringkasan performa kerja dan data akun pembayaran Anda.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="rounded-2xl bg-slate-50 border border-slate-200 p-5">
                    <p class="text-sm text-slate-500">Rata-rata Skor</p>
                    <p class="mt-2 text-3xl font-bold text-blue-700">{{ $rataRataSkor }}/30</p>
                </div>

                <div class="rounded-2xl bg-slate-50 border border-slate-200 p-5">
                    <p class="text-sm text-slate-500">Tingkat Keberhasilan</p>
                    <p class="mt-2 text-3xl font-bold text-emerald-600">{{ $tingkatKeberhasilan }}%</p>
                </div>

                <div class="rounded-2xl bg-slate-50 border border-slate-200 p-5">
                    <p class="text-sm text-slate-500">No. Rekening</p>
                    <p class="mt-2 text-lg font-semibold text-slate-800">
                        {{ $user->rekening?->no_akun ?? '-' }}
                    </p>
                    <p class="mt-1 text-sm text-slate-500">
                        {{ $user->rekening?->nama_bank ?? '' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Log Poin --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-slate-800">Log Poin</h2>
                <p class="text-sm text-slate-500 mt-1">Riwayat perubahan poin berdasarkan aktivitas Anda.</p>
            </div>

            <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2">
                @forelse ($logpoins as $log)
                <div class="flex items-start justify-between gap-4 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <div>
                        <p class="font-medium text-slate-800">
                            {{ $log->jenis === 'tambah' ? 'Penambahan Poin' : 'Pengurangan Poin' }}
                        </p>

                        <p class="mt-1 text-sm text-slate-500">
                            {{ $log->created_at->format('d M Y, H:i') }}
                        </p>
                    </div>

                    <div class="text-right">
                        <p class="text-lg font-bold {{ $log->jenis === 'tambah' ? 'text-emerald-600' : 'text-red-600' }}">
                            {{ $log->jenis === 'tambah' ? '+' : '-' }}
                            {{ $log->jumlah_poin ?? $log->poin ?? 0 }} poin
                        </p>
                    </div>
                </div>
                @empty
                <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
                    <p class="text-slate-500">Belum ada riwayat log poin.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Right Sidebar --}}
    <div class="space-y-6">
        {{-- Akun Ringkas --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-800 mb-4">Ringkasan Akun</h2>

            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-500">Nama</span>
                    <span class="text-sm font-medium text-slate-800">{{ $user->name }}</span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-500">Email</span>
                    <span class="text-sm font-medium text-slate-800 truncate max-w-[180px]">{{ $user->email }}</span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-500">No. Telepon</span>
                    <span class="text-sm font-medium text-slate-800 truncate">{{ $user->no_telp ?? '-' }}</span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-500">Level</span>
                    <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-sm font-medium text-blue-700">
                        {{ $user->level->nama_level ?? '-' }}
                    </span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-500">Poin</span>
                    <span class="text-sm font-semibold text-slate-800">{{ $user->poin_level }} poin</span>
                </div>
            </div>
        </div>

        {{-- Keuntungan Level --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-slate-800">Keuntungan Level</h2>
                <p class="text-sm text-slate-500 mt-1">Semakin tinggi level, semakin besar prioritas Anda.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-1 gap-4">
                @php
                $benefits = [
                ['level' => 1, 'time' => 'Notifikasi standar', 'bg' => 'bg-slate-500'],
                ['level' => 2, 'time' => 'Notifikasi lebih cepat', 'bg' => 'bg-blue-500'],
                ['level' => 3, 'time' => 'Prioritas menengah', 'bg' => 'bg-indigo-500'],
                ['level' => 4, 'time' => 'Prioritas tinggi', 'bg' => 'bg-violet-500'],
                ['level' => 5, 'time' => 'Akses paling awal', 'bg' => 'bg-yellow-500'],
                ];
                @endphp

                @foreach ($benefits as $benefit)
                <div class="rounded-2xl border border-slate-200 p-4 transition hover:shadow-sm hover:border-blue-200">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 {{ $benefit['bg'] }} rounded-2xl flex items-center justify-center text-white font-bold shadow-sm">
                            {{ $benefit['level'] }}
                        </div>
                        <div>
                            <p class="font-semibold text-slate-800">Level {{ $benefit['level'] }}</p>
                            <p class="text-sm text-slate-500">{{ $benefit['time'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-6 rounded-2xl border border-yellow-200 bg-yellow-50 p-4">
                <p class="text-sm text-yellow-800">
                    <span class="font-medium">Tip:</span> Semakin tinggi level Anda, semakin awal Anda mendapatkan notifikasi proyek baru dan akses prioritas pengambilan tugas.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection