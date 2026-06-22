@extends('layouts.body', ['title' => 'Upload Tugas'])

@section('content')
<x-header
    :judul="'Upload Hasil Pekerjaan'"
    :subjudul="'Kirim hasil pekerjaan yang telah selesai dikerjakan'" />

<div class="space-y-6">

    <!-- Stat Card Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <x-stat-card
            title="Tugas Diambil"
            :value="$tugasDiambil"
            color="blue"
            brdr="blue">
        </x-stat-card>

        <x-stat-card
            title="Menunggu Penilaian"
            :value="$tugasMenunggu"
            color="yellow"
            brdr="yellow">
        </x-stat-card>

        <div class="relative overflow-hidden bg-linear-to-r from-brand-500 to-brand-700 rounded-3xl p-6 border-l-8 border-2 border-brand-500 hover:border-l-8 transition hover:-translate-y-1 hover:shadow-2xl">
            <p class="text-white font-medium text-sm mb-2">
                Status
            </p>

            <p class="text-3xl text-white font-semibold">
                Penugasan Aktif
            </p>
        </div>
    </div>

    <!-- Task List Area -->
    <div class="space-y-5">
        @forelse ($tugases as $tugas)

        <!-- Setiap card membungkus state modalnya sendiri secara independen -->
        <div x-data="{
            modalUpload: false,
            modalBatal: false,
            fileSelected: false,
            fileName: ''
        }" class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 p-6 sm:p-7 space-y-5">

            <!-- 1. HEADER: INFO UTAMA & STATUS BADGE -->
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                <div class="min-w-0">
                    <h2 class="text-xl font-bold text-slate-900 leading-tight">
                        {{ $tugas['nama_proyek'] }}
                    </h2>

                    <p class="text-sm font-medium text-slate-500 mt-1">
                        {{ $tugas['nama_sub_proyek'] }}
                        <span class="mx-2 text-slate-300">•</span>
                        <span class="text-slate-700">{{ $tugas['nama_halaman'] }}</span>
                    </p>
                </div>

                <div class="shrink-0 sm:self-start">
                    <x-status :value="$tugas['status']" />
                </div>
            </div>

            <!-- 2. DETAIL TUGAS: 4 KARTU INFORMASI KECIL -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">

                <!-- Kartu 1: Halaman Kerja -->
                <div class="rounded-2xl border border-gray-200 shadow p-4">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Halaman</p>
                    <p class="font-bold text-slate-800 mt-1 text-sm sm:text-base">
                        {{ $tugas['dari_halaman'] }} - {{ $tugas['sampai_halaman'] }}
                    </p>
                </div>

                <!-- Kartu 2: Total Volume Halaman -->
                <div class="rounded-2xl border border-gray-200 shadow p-4">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Volume</p>
                    <p class="font-bold text-slate-800 mt-1 text-sm sm:text-base">
                        {{ $tugas['total_halaman'] }} Halaman
                    </p>
                </div>

                <!-- Kartu 3: Estimasi Fee -->
                <div class="rounded-2xl border border-gray-200 shadow p-4">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Estimasi Pendapatan</p>
                    <p class="font-bold text-green-600 mt-1 text-sm sm:text-base">
                        Rp {{ number_format($tugas['total_harga'], 0, ',', '.') }}
                    </p>
                </div>

                <!-- Kartu 4: Batas Waktu / Deadline -->
                <div class="rounded-2xl border border-gray-200 shadow p-4">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Batas Waktu</p>
                    <p class="font-bold text-red-600 mt-1 text-sm sm:text-base">
                        {{ $tugas['tanggal_selesai']->format('d M Y H:i') }}
                    </p>
                </div>

            </div>

            <!-- 3. BAGIAN BAWAH KANAN: TOMBOL AKSI -->
            @if (!$tugas['xls_hasil'])
            <div class="pt-5 border-t border-slate-100 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">

                <x-danger-button
                    type="button"
                    @click="modalBatal = true"
                    class="justify-center shadow-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" class="mr-2" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                        <path fill="currentColor" d="M504.6 148.5C515.9 134.9 514.1 114.7 500.5 103.4C486.9 92.1 466.7 93.9 455.4 107.5L320 270L184.6 107.5C173.3 93.9 153.1 92.1 139.5 103.4C125.9 114.7 124.1 134.9 135.4 148.5L278.3 320L135.4 491.5C124.1 505.1 125.9 525.3 139.5 536.6C153.1 547.9 173.3 546.1 184.6 532.5L320 370L455.4 532.5C466.7 546.1 486.9 547.9 500.5 536.6C514.1 525.3 515.9 505.1 504.6 491.5L361.7 320L504.6 148.5z" />
                    </svg>
                    Batalkan Proyek
                </x-danger-button>

                <x-primary-button
                    type="button"
                    @click="modalUpload = true"
                    class="justify-center shadow-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" class="mr-2" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                        <path fill="currentColor" d="M342.6 73.4C330.1 60.9 309.8 60.9 297.3 73.4L169.3 201.4C156.8 213.9 156.8 234.2 169.3 246.7C181.8 259.2 202.1 259.2 214.6 246.7L288 173.3L288 384C288 401.7 302.3 416 320 416C337.7 416 352 401.7 352 384L352 173.3L425.4 246.7C437.9 259.2 458.2 259.2 470.7 246.7C483.2 234.2 483.2 213.9 470.7 201.4L342.7 73.4zM160 416C160 398.3 145.7 384 128 384C110.3 384 96 398.3 96 416L96 480C96 533 139 576 192 576L448 576C501 576 544 533 544 480L544 416C544 398.3 529.7 384 512 384C494.3 384 480 398.3 480 416L480 480C480 497.7 465.7 512 448 512L192 512C174.3 512 160 497.7 160 480L160 416z" />
                    </svg>
                    Upload Tugas
                </x-primary-button>
            </div>

            @else
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5">

                <div class="flex items-start gap-3">

                    <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-emerald-600 border border-emerald-100">
                        ✓
                    </div>

                    <div class="min-w-0">
                        <p class="font-semibold text-emerald-700">
                            File berhasil diupload
                        </p>

                        <p class="text-sm text-emerald-600 mt-1 break-all">
                            {{ $tugas['xls_hasil'] }}
                        </p>
                    </div>

                </div>

            </div>
            @endif
            <!-- MODAL 1: FORM UPLOAD TUGAS -->
            <div x-show="modalUpload"
                x-transition.opacity
                x-cloak
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center z-50 px-4"
                @click.self="modalUpload = false"
                @keydown.escape.window="modalUpload = false">

                <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all">

                    <!-- Header Modal -->
                    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">Upload Hasil Pekerjaan</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Kirimkan berkas lembar kerja yang telah Anda selesaikan.</p>
                        </div>
                        <button type="button" @click="modalUpload = false" class="text-slate-400 hover:text-slate-600 transition-colors text-lg focus:outline-none">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <form action="{{ route('freelancer.uploadHasil', $tugas['id']) }}" method="POST" enctype="multipart/form-data" class="m-0">
                        @csrf

                        <!-- Body Modal -->
                        <div class="px-6 py-6 space-y-5">

                            <!-- Periode & Download Info -->
                            <div class="rounded-2xl bg-slate-50 border border-slate-200/60 p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Periode Pengerjaan</p>
                                    <p class="text-xs font-semibold text-slate-700 mt-1">
                                        {{ $tugas['tanggal_mulai']->format('d M Y H:i') }} — {{ $tugas['tanggal_selesai']->format('d M Y H:i') }} WIB
                                    </p>
                                </div>
                                <div class="flex flex-wrap gap-2 shrink-0">
                                    <a href="{{ route('freelancer.downloadPdfTugas', $tugas['id']) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-xs font-semibold text-slate-700 shadow-xs">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-600" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                                            <path fill="currentColor" d="M240 112L128 112C119.2 112 112 119.2 112 128L112 512C112 520.8 119.2 528 128 528L208 528L208 576L128 576C92.7 576 64 547.3 64 512L64 128C64 92.7 92.7 64 128 64L261.5 64C278.5 64 294.8 70.7 306.8 82.7L429.3 205.3C441.3 217.3 448 233.6 448 250.6L448 400.1L400 400.1L400 272.1L312 272.1C272.2 272.1 240 239.9 240 200.1L240 112.1zM380.1 224L288 131.9L288 200C288 213.3 298.7 224 312 224L380.1 224zM272 444L304 444C337.1 444 364 470.9 364 504C364 537.1 337.1 564 304 564L292 564L292 592C292 603 283 612 272 612C261 612 252 603 252 592L252 464C252 453 261 444 272 444zM304 524C315 524 324 515 324 504C324 493 315 484 304 484L292 484L292 524L304 524zM400 444L432 444C460.7 444 484 467.3 484 496L484 560C484 588.7 460.7 612 432 612L400 612C389 612 380 603 380 592L380 464C380 453 389 444 400 444zM432 572C438.6 572 444 566.6 444 560L444 496C444 489.4 438.6 484 432 484L420 484L420 572L432 572zM508 464C508 453 517 444 528 444L576 444C587 444 596 453 596 464C596 475 587 484 576 484L548 484L548 508L576 508C587 508 596 517 596 528C596 539 587 548 576 548L548 548L548 592C548 603 539 612 528 612C517 612 508 603 508 592L508 464z" />
                                        </svg>
                                        PDF Tugas
                                    </a>

                                    <a href="{{ route('freelancer.downloadTemplateTugas', $tugas['id']) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-xs font-semibold text-slate-700 shadow-xs">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                                            <path fill="currentColor" d="M192 112L304 112L304 200C304 239.8 336.2 272 376 272L464 272L464 512C464 520.8 456.8 528 448 528L192 528C183.2 528 176 520.8 176 512L176 128C176 119.2 183.2 112 192 112zM352 131.9L444.1 224L376 224C362.7 224 352 213.3 352 200L352 131.9zM192 64C156.7 64 128 92.7 128 128L128 512C128 547.3 156.7 576 192 576L448 576C483.3 576 512 547.3 512 512L512 250.5C512 233.5 505.3 217.2 493.3 205.2L370.7 82.7C358.7 70.7 342.5 64 325.5 64L192 64zM291.2 329.6C283.2 319 268.2 316.8 257.6 324.8C247 332.8 244.8 347.8 252.8 358.4L290 408L252.8 457.6C244.8 468.2 247 483.2 257.6 491.2C268.2 499.2 283.2 497 291.2 486.4L320 448L348.8 486.4C356.8 497 371.8 499.2 382.4 491.2C393 483.2 395.2 468.2 387.2 457.6L350 408L387.2 358.4C395.2 347.8 393 332.8 382.4 324.8C371.8 316.8 356.8 319 348.8 329.6L320 368L291.2 329.6z" />
                                        </svg>
                                        Template Excel
                                    </a>
                                </div>
                            </div>

                            <!-- Upload Box Area -->
                            @if (!$tugas['xls_hasil'])
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Berkas Lembar Kerja</label>

                                <label for="xls_hasil_{{ $tugas['id'] }}"
                                    class="group relative flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 hover:bg-brand-50/40 hover:border-brand-300 transition-all duration-200 px-6 py-10 cursor-pointer">

                                    <div class="w-12 h-12 rounded-2xl bg-white border border-slate-200 flex items-center justify-center mb-3 shadow-xs group-hover:scale-105 transition-transform duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" class="text-xl text-slate-400 group-hover:text-brand-500 transition-colors" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                                            <path fill="currentColor" d="M352 173.3L352 384C352 401.7 337.7 416 320 416C302.3 416 288 401.7 288 384L288 173.3L246.6 214.7C234.1 227.2 213.8 227.2 201.3 214.7C188.8 202.2 188.8 181.9 201.3 169.4L297.3 73.4C309.8 60.9 330.1 60.9 342.6 73.4L438.6 169.4C451.1 181.9 451.1 202.2 438.6 214.7C426.1 227.2 405.8 227.2 393.3 214.7L352 173.3zM320 464C364.2 464 400 428.2 400 384L480 384C515.3 384 544 412.7 544 448L544 480C544 515.3 515.3 544 480 544L160 544C124.7 544 96 515.3 96 480L96 448C96 412.7 124.7 384 160 384L240 384C240 428.2 275.8 464 320 464zM464 488C477.3 488 488 477.3 488 464C488 450.7 477.3 440 464 440C450.7 440 440 450.7 440 464C440 477.3 450.7 488 464 488z" />
                                        </svg>
                                    </div>

                                    <div class="text-center">
                                        <span x-show="!fileSelected" class="block font-semibold text-sm text-slate-700">Pilih berkas dari komputer Anda</span>
                                        <span x-show="fileSelected" class="block font-mono text-sm font-bold text-brand-600 break-all px-4" x-text="fileName"></span>

                                        <span x-show="!fileSelected" class="block text-xs text-slate-400 mt-1">Hanya format file spreadsheet (.xls atau .xlsx)</span>
                                        <span x-show="fileSelected" class="block text-xs text-slate-500 mt-1">Klik kembali untuk mengganti berkas</span>
                                    </div>

                                    <input id="xls_hasil_{{ $tugas['id'] }}"
                                        type="file"
                                        name="xls_hasil"
                                        accept=".xls,.xlsx"
                                        class="hidden"
                                        @change="fileSelected = $event.target.files.length > 0; fileName = $event.target.files.length > 0 ? $event.target.files[0].name : ''">
                                </label>
                            </div>

                            <div class="flex items-start gap-3 rounded-2xl bg-amber-50/60 border border-amber-100 p-4">
                                <i class="fas fa-info-circle text-amber-600 mt-0.5 shrink-0 text-sm"></i>
                                <p class="text-xs text-amber-800 leading-relaxed font-medium">
                                    Pastikan pengisian data berkas Anda telah sesuai dengan aturan kolom pada template Excel agar proses penilaian otomatis berjalan lancar.
                                </p>
                            </div>
                            @else
                            <!-- Success State -->
                            <div class="rounded-2xl border border-emerald-100 bg-emerald-50/40 p-5">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-emerald-600 border border-emerald-100 shadow-xs shrink-0">
                                        <i class="fas fa-check-circle text-lg"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-800 text-sm">Berkas Berhasil Terkirim</p>
                                        <p class="text-xs text-slate-500 mt-1.5 break-all bg-white/70 p-2.5 rounded-xl border border-slate-100 font-mono">{{ $tugas['xls_hasil'] }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif

                        </div>

                        <!-- Footer Modal -->
                        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                            <button type="button" @click="modalUpload = false" class="px-4 py-2.5 bg-white border border-slate-200 text-sm font-semibold text-slate-700 rounded-xl hover:bg-slate-50 transition-colors shadow-xs">
                                Tutup
                            </button>

                            @unless ($tugas['xls_hasil'])
                            <x-primary-button type="submit" ::disabled="!fileSelected" class="shadow-xs">
                                Kirim Hasil Pekerjaan
                            </x-primary-button>
                            @endunless
                        </div>
                    </form>

                </div>
            </div>

            <!-- MODAL 2: KONFIRMASI PEMBATALAN TUGAS -->
            <div x-show="modalBatal"
                x-transition.opacity
                x-cloak
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center z-50 px-4"
                @click.self="modalBatal = false"
                @keydown.escape.window="modalBatal = false">

                <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all">

                    <!-- Header Modal -->
                    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">Konfirmasi Pembatalan</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Tinjau konsekuensi pembatalan sebelum melanjutkan.</p>
                        </div>
                        <button type="button" @click="modalBatal = false" class="text-slate-400 hover:text-slate-600 transition-colors text-lg focus:outline-none">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <!-- Body Modal -->
                    <div class="px-6 py-5 space-y-5">

                        <!-- Warning Alert Box -->
                        <div class="flex items-start gap-3 p-4 bg-red-50 border border-red-100 rounded-2xl">
                            <i class="fas fa-exclamation-triangle text-red-600 mt-0.5 shrink-0 text-sm"></i>
                            <div>
                                <p class="text-xs font-bold text-red-800 uppercase tracking-wider">Poin Reputasi Akun Dikurangi</p>
                                <p class="text-xs text-red-700 mt-1 leading-relaxed font-medium">
                                    Tindakan pembatalan proyek ini akan mengurangi poin reputasi Anda. Pekerjaan akan dibebaskan kembali agar bisa diambil oleh mitra freelancer lain.
                                </p>
                            </div>
                        </div>

                        <!-- Data Penalti Info Grid -->
                        <div class="grid grid-cols-2 gap-3">
                            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/60">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Halaman Diambil</p>
                                <p class="text-sm font-bold text-slate-800 mt-0.5">
                                    {{ $tugas['penalti_detail']['faktor_halaman'] ?? 0 }} Halaman
                                </p>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/60">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Kualitas Kerja</p>
                                <p class="text-sm font-bold text-slate-800 mt-0.5">
                                    {{ $tugas['kualitas'] ?? 0 }}/10
                                </p>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/60">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Interval Hari Diambil</p>
                                <p class="text-sm font-bold text-slate-800 mt-0.5">
                                    {{ $tugas['penalti_detail']['hari_diambil'] ?? 0 }} Hari
                                </p>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/60">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Durasi Proyek</p>
                                <p class="text-sm font-bold text-slate-800 mt-0.5">
                                    {{ $tugas['penalti_detail']['total_durasi_hari'] ?? 0 }} Hari
                                </p>
                            </div>
                        </div>

                        <!-- Penalty Cost Box -->
                        <div class="rounded-2xl border border-red-150 bg-red-50/30 p-5 flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold text-slate-500">Estimasi Pengurangan Poin</p>
                                <p class="text-3xl font-extrabold text-red-600 mt-1">
                                    -{{ $tugas['penalti_detail']['penalti'] ?? 0 }}
                                </p>
                                <p class="text-[10px] text-slate-400 mt-1">Poin akan langsung dikurangi dari saldo reputasi Anda</p>
                            </div>

                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-700 border border-red-200/40">
                                Rasio: {{ $tugas['penalti_detail']['faktor_rasio'] ?? 0 }}
                            </span>
                        </div>

                        <p class="text-[11px] text-slate-400 leading-relaxed text-center">
                            Dengan menekan tombol <span class="font-bold text-slate-600">"Ya, Batalkan Pekerjaan"</span>, Anda secara sadar menyetujui pembatalan tugas ini beserta seluruh perhitungan pengurangan poin reputasi dari sistem.
                        </p>
                    </div>

                    <!-- Footer Modal -->
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex gap-3">
                        <button type="button" @click="modalBatal = false" class="flex-1 px-4 py-2.5 bg-white border border-slate-200 text-sm font-semibold text-slate-700 rounded-xl hover:bg-slate-50 transition-colors shadow-xs">
                            Kembali
                        </button>

                        <form action="/upload-tugas/{{ $tugas['id'] }}/proses-batal" method="POST" class="flex-1">
                            @csrf
                            <x-danger-button type="submit" full class="shadow-xs">Ya, Batalkan Pekerjaan</x-danger-button>
                        </form>
                    </div>

                </div>
            </div>

        </div>

        @empty
        <!-- Empty State ketika tidak ada tugas -->
        <x-list-empty title="Tidak Ada Tugas Aktif" subtitle="Anda tidak memiliki tugas yag perlu dikerjakan atau diunggah saat ini">
            <x-slot:icon>
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M9 8h6m2 12H7a2 2 0 01-2-2V6a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V18a2 2 0 01-2 2z" />
                </svg>
            </x-slot:icon>
        </x-list-empty>
        @endforelse
    </div>

</div>
@endsection