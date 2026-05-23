@extends('layouts.body', ['title' => 'Dashboard'])

@section('content')
<x-header
    :judul="'Upload Hasil Pekerjaan'"
    :subjudul="'Kirim hasil pekerjaan yang telah selesai dikerjakan'" />

<div class="space-y-5">

    @forelse ($tugases as $tugas)

    <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm">

        <div class="grid grid-cols-1 xl:grid-cols-12">

            {{-- CONTENT --}}
            <div class="xl:col-span-8 p-5 sm:p-7">

                <form
                    action="{{ route('freelancer.uploadHasil', $tugas['id']) }}"
                    method="POST"
                    enctype="multipart/form-data"
                    x-data="{ fileSelected: false }"
                    class="space-y-6">

                    @csrf

                    {{-- HEADER --}}
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">

                        <div class="min-w-0">
                            <h2 class="text-xl font-bold text-slate-900 leading-tight">
                                {{ $tugas['nama_proyek'] }}
                            </h2>

                            <p class="text-sm text-slate-500 mt-1">
                                {{ $tugas['nama_sub_proyek'] }}
                                <span class="mx-1">•</span>
                                {{ $tugas['nama_halaman'] }}
                            </p>
                        </div>

                        <div class="shrink-0">
                            <x-status :value="$tugas['status']" />
                        </div>
                    </div>

                    {{-- QUICK INFO --}}
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">

                        <div class="rounded-2xl border border-slate-200 p-4">
                            <p class="text-xs text-slate-500 mb-1">
                                Halaman
                            </p>

                            <p class="font-semibold text-slate-900">
                                {{ $tugas['dari_halaman'] }} - {{ $tugas['sampai_halaman'] }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 p-4">
                            <p class="text-xs text-slate-500 mb-1">
                                Total
                            </p>

                            <p class="font-semibold text-slate-900">
                                {{ $tugas['total_halaman'] }} Hal
                            </p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 p-4">
                            <p class="text-xs text-slate-500 mb-1">
                                Estimasi
                            </p>

                            <p class="font-semibold text-brand-600">
                                Rp {{ number_format($tugas['total_harga'], 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 p-4">
                            <p class="text-xs text-slate-500 mb-1">
                                Deadline
                            </p>

                            <p class="font-semibold text-slate-900 text-sm">
                                {{ $tugas['tanggal_selesai']->format('d M Y') }}
                            </p>
                        </div>

                    </div>

                    {{-- PERIODE --}}
                    <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                            <div>
                                <p class="text-sm font-medium text-slate-900">
                                    Periode Pengerjaan
                                </p>

                                <p class="text-sm text-slate-500 mt-1">
                                    {{ $tugas['tanggal_mulai']->format('d M Y H:i') }}
                                    —
                                    {{ $tugas['tanggal_selesai']->format('d M Y H:i') }} WIB
                                </p>
                            </div>

                            <div class="flex flex-wrap gap-2">

                                <a
                                    href="{{ route('freelancer.downloadPdfTugas', $tugas['id']) }}"
                                    class="inline-flex items-center justify-center px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-medium">

                                    PDF Tugas
                                </a>

                                <a
                                    href="{{ route('freelancer.downloadTemplateTugas', $tugas['id']) }}"
                                    class="inline-flex items-center justify-center px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition text-sm font-medium">

                                    Template Excel
                                </a>

                            </div>

                        </div>

                    </div>

                    {{-- UPLOAD --}}
                    @if (!$tugas['xls_hasil'])

                    <div class="space-y-4">

                        <div>

                            <label class="block text-sm font-semibold text-slate-800 mb-3">
                                Upload File Hasil
                            </label>

                            <label
                                for="xls_hasil_{{ $tugas['id'] }}"
                                class="group relative flex flex-col items-center justify-center rounded-3xl border-2 border-dashed border-slate-300 bg-slate-50 hover:bg-brand-50 hover:border-brand-300 transition-all duration-200 px-6 py-10 cursor-pointer">

                                <div class="w-14 h-14 rounded-2xl bg-white border border-slate-200 flex items-center justify-center mb-4 shadow-sm">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        width="24"
                                        height="24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        class="text-slate-500">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                        <polyline points="17 8 12 3 7 8" />
                                        <line x1="12" y1="3" x2="12" y2="15" />
                                    </svg>

                                </div>

                                <div class="text-center">
                                    <p class="font-medium text-slate-900">
                                        Klik untuk upload file
                                    </p>

                                    <p class="text-sm text-slate-500 mt-1">
                                        Format .xls atau .xlsx
                                    </p>
                                </div>

                                <input
                                    id="xls_hasil_{{ $tugas['id'] }}"
                                    type="file"
                                    name="xls_hasil"
                                    accept=".xls,.xlsx"
                                    class="hidden"
                                    @change="fileSelected = $event.target.files.length > 0">
                            </label>

                        </div>

                        <div class="flex items-start gap-3 rounded-2xl bg-brand-50 border border-brand-100 p-4">

                            <div class="text-brand-600 mt-0.5">
                                ✓
                            </div>

                            <p class="text-sm text-brand-700">
                                Pastikan file sudah sesuai template sebelum dikirim.
                            </p>

                        </div>

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

                    {{-- BUTTON --}}
                    @unless ($tugas['xls_hasil'])

                    <div class="pt-2">

                        <x-primary-button
                            type="submit"
                            ::disabled="!fileSelected"
                            class="w-full sm:w-auto">

                            Kirim Untuk Penilaian

                        </x-primary-button>

                    </div>

                    @endunless

                </form>

            </div>

            {{-- SIDEBAR --}}
            <div class="xl:col-span-4 bg-slate-50 border-t xl:border-t-0 xl:border-l border-slate-200 p-5 sm:p-7">

                <div class="xl:sticky xl:top-24 space-y-5">

                    <div>

                        <h3 class="font-semibold text-slate-900">
                            Informasi Tugas
                        </h3>

                        <p class="text-sm text-slate-500 mt-1 leading-relaxed">
                            Kelola tugas dan pantau status pengiriman hasil pekerjaan Anda.
                        </p>

                    </div>

                    @unless ($tugas['xls_hasil'])

                    <div class="rounded-2xl border border-red-200 bg-red-50 p-4">

                        <p class="font-medium text-red-700 text-sm">
                            Pembatalan dikenakan penalti poin
                        </p>

                        <p class="text-sm text-red-600 mt-2 leading-relaxed">
                            Sistem akan menghitung penalti otomatis berdasarkan progres dan durasi pengerjaan.
                        </p>

                    </div>

                    @else

                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4">

                        <p class="font-medium text-emerald-700 text-sm">
                            Tugas telah dikirim
                        </p>

                        <p class="text-sm text-emerald-600 mt-2">
                            Menunggu proses penilaian admin.
                        </p>

                    </div>

                    @endunless

                    {{-- ACTION --}}
                    @unless ($tugas['xls_hasil'])

                    <div
                        x-data="{ modalBatal: false, detailModal: null }"
                        class="space-y-3">

                        <x-danger-button
                            type="button"
                            full
                            x-on:click='modalBatal = true; detailModal = {{ Js::from($tugas) }}'>

                            Batal Ambil Proyek

                        </x-danger-button>

                        {{-- MODAL --}}
                        <div
                            x-show="modalBatal"
                            x-transition.opacity
                            x-cloak
                            class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 px-4"
                            @click.self="modalBatal = false"
                            @keydown.escape.window="modalBatal = false">

                            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">
                                <div class="px-6 py-5 border-b border-gray-200">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Pembatalan</h3>
                                            <p class="text-sm text-gray-500 mt-1">
                                                Tinjau konsekuensi pembatalan sebelum melanjutkan.
                                            </p>
                                        </div>

                                        <button
                                            type="button"
                                            @click="modalBatal = false"
                                            class="text-gray-400 hover:text-gray-600 transition-colors">
                                            ✕
                                        </button>
                                    </div>
                                </div>

                                <div class="px-6 py-5 space-y-5">
                                    <div class="flex items-start gap-3 p-4 bg-red-50 border border-red-100 rounded-xl">
                                        <div class="text-red-600 mt-0.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 640 640">
                                                <path fill="currentColor" d="M320 64C334.7 64 348.2 72.1 355.2 85L571.2 485C577.9 497.4 577.6 512.4 570.4 524.5C563.2 536.6 550.1 544 536 544L104 544C89.9 544 76.8 536.6 69.6 524.5C62.4 512.4 62.1 497.4 68.8 485L284.8 85C291.8 72.1 305.3 64 320 64zM320 416C302.3 416 288 430.3 288 448C288 465.7 302.3 480 320 480C337.7 480 352 465.7 352 448C352 430.3 337.7 416 320 416zM320 224C301.8 224 287.3 239.5 288.6 257.7L296 361.7C296.9 374.2 307.4 384 319.9 384C332.5 384 342.9 374.3 343.8 361.7L351.2 257.7C352.5 239.5 338.1 224 319.8 224z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-red-700">Pembatalan akan mengurangi poin Anda</p>
                                            <p class="text-sm text-red-600 mt-1">
                                                Tugas akan dilepas dan dapat diambil kembali oleh freelancer lain.
                                            </p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="p-4 rounded-xl bg-gray-50 border border-gray-200">
                                            <p class="text-xs text-gray-500 mb-1">Halaman Diambil</p>
                                            <p class="text-sm font-semibold text-gray-900">
                                                <span x-text="detailModal?.dari_halaman ?? '-'"></span> -
                                                <span x-text="detailModal?.sampai_halaman ?? '-'"></span>
                                            </p>
                                        </div>

                                        <div class="p-4 rounded-xl bg-gray-50 border border-gray-200">
                                            <p class="text-xs text-gray-500 mb-1">Kualitas</p>
                                            <p class="text-sm font-semibold text-gray-900">
                                                <span x-text="detailModal?.kualitas ?? 0"></span>/10
                                            </p>
                                        </div>

                                        <div class="p-4 rounded-xl bg-gray-50 border border-gray-200">
                                            <p class="text-xs text-gray-500 mb-1">Hari Sejak Diambil</p>
                                            <p class="text-sm font-semibold text-gray-900">
                                                <span x-text="detailModal?.penalti_detail?.hari_diambil ?? 0"></span> hari
                                            </p>
                                        </div>

                                        <div class="p-4 rounded-xl bg-gray-50 border border-gray-200">
                                            <p class="text-xs text-gray-500 mb-1">Durasi Proyek</p>
                                            <p class="text-sm font-semibold text-gray-900">
                                                <span x-text="detailModal?.penalti_detail?.total_durasi ?? 0"></span> hari
                                            </p>
                                        </div>
                                    </div>

                                    <div class="rounded-2xl border border-red-100 p-5">
                                        <p class="text-sm text-gray-500 mb-1">Estimasi Penalti</p>
                                        <div class="flex items-end justify-between">
                                            <div>
                                                <p class="text-3xl font-bold text-red-600">
                                                    -<span x-text="detailModal?.penalti_detail?.penalti ?? 0"></span>
                                                </p>
                                                <p class="text-sm text-gray-500 mt-1">poin akan dikurangi dari akun Anda</p>
                                            </div>

                                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">
                                                Rasio:
                                                <span x-text="detailModal?.penalti_detail?.faktor_rasio ?? 0"></span>
                                            </span>
                                        </div>
                                    </div>

                                    <p class="text-xs text-gray-400 leading-relaxed">
                                        Dengan menekan tombol <span class="font-medium text-gray-600">“Ya, Batalkan”</span>,
                                        Anda menyetujui pembatalan pengambilan tugas ini beserta pengurangan poin sesuai perhitungan sistem.
                                    </p>
                                </div>

                                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex gap-3">
                                    <button
                                        type="button"
                                        class="flex-1 px-4 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors"
                                        @click="modalBatal = false">
                                        Kembali
                                    </button>

                                    <form :action="detailModal?.id ? `/upload-tugas/${detailModal.id}/proses-batal` : '#'" method="POST" class="flex-1">
                                        @csrf
                                        <x-danger-button type="submit" full>Ya, Batalkan</x-danger-button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>

                    @endunless

                </div>

            </div>

        </div>

    </div>

    @empty

    <div class="bg-white border border-slate-200 rounded-3xl p-10 text-center">

        <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-5">

            <svg xmlns="http://www.w3.org/2000/svg"
                width="32"
                height="32"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                class="text-slate-400">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                <polyline points="14 2 14 8 20 8" />
            </svg>

        </div>

        <h3 class="text-lg font-semibold text-slate-900">
            Belum Ada Upload
        </h3>

        <p class="text-slate-500 mt-2">
            Tidak ada tugas yang perlu diupload saat ini.
        </p>

    </div>

    @endforelse

</div>
@endsection