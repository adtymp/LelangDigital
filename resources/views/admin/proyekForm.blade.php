@extends('layouts.body', ['title' => 'Tambah Proyek'])

@section('content')

<x-header
    :judul="$proyek ? 'Edit Proyek' : 'Tambah Proyek Baru'"
    :subjudul="'Lengkapi informasi proyek dan sub-proyek'" />

<div x-data="proyekForm()" x-init="initProyek(@js($proyek), @js(old('sub_proyek')))" class="pb-28">
    <form action="{{ $proyek ? route('proyek.update', $proyek->id) : route('proyek.add') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @if($proyek)
        @method('POST')
        @endif

        <!-- proyek -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6 transition-all hover:shadow-md">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2.5 rounded-xl bg-brand-50 text-brand-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-slate-800">Informasi Proyek</h2>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-600 mb-2">Nama Proyek</label>
                    <input type="text"
                        name="nama_proyek"
                        x-model="nama_proyek"
                        class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-brand-500/20 focus:border-brand-500 transition-all placeholder:text-slate-400"
                        placeholder="Contoh: Input Data Pelanggan Bulan November">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-600 mb-2">Tanggal Mulai</label>
                        <input type="datetime-local"
                            name="tanggal_mulai"
                            x-model="tanggal_mulai"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-brand-500/20 focus:border-brand-500 transition-all text-slate-700">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-600 mb-2">Tanggal Selesai</label>
                        <input type="datetime-local"
                            name="tanggal_selesai"
                            x-model="tanggal_selesai"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-brand-500/20 focus:border-brand-500 transition-all text-slate-700">
                    </div>
                </div>
            </div>
        </div>

        <!-- subproyek -->
        <div class="space-y-6">
            <template x-for="(sub, subIdx) in sub_proyek" :key="sub.key">
                <div class="border border-slate-200 p-6 rounded-2xl bg-white shadow-sm hover:shadow-md transition-all">

                    <!-- HEADER SUB PROYEK -->
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-4 mb-6">
                        <div class="flex items-center gap-3">
                            <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-brand-100 text-brand-700 font-bold text-sm" x-text="subIdx + 1"></span>
                            <h3 class="text-lg font-bold text-slate-800">Sub Proyek</h3>
                        </div>

                        <div class="flex items-center gap-3">
                            <!-- STATS BADGES -->
                            <div class="flex gap-2">
                                <span class="bg-slate-50 text-slate-600 border border-slate-200 px-3 py-1 rounded-full text-xs font-semibold">
                                    Total Halaman: <span x-text="sub.total_halaman"></span> Hlm
                                </span>
                            </div>
                            <!-- TOMBOL HAPUS SUB PROYEK -->
                            <button type="button"
                                @click="hapusSubProyek(subIdx)"
                                class="text-red-500 hover:text-white hover:bg-red-500 border border-red-200 hover:border-red-500 px-3 py-1.5 rounded-xl text-xs font-semibold flex items-center gap-1.5 transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Hapus Sub-Proyek
                            </button>
                        </div>
                    </div>

                    <!-- HIDDEN FIELD FOR SUBPROYEK -->
                    <input type="hidden" :name="`sub_proyek[${subIdx}][id]`" x-model="sub.id">
                    <input type="hidden" :name="`sub_proyek[${subIdx}][total_halaman]`" x-model="sub.total_halaman">

                    <!-- INPUT NAMA SUB PROYEK -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-600 mb-2">Nama Sub Proyek</label>
                        <input type="text"
                            :name="`sub_proyek[${subIdx}][nama]`"
                            x-model="sub.nama"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-brand-500/20 focus:border-brand-500 transition-all placeholder:text-slate-400"
                            placeholder="Contoh: Berkas Scan Wilayah Utara">
                    </div>

                    <!-- subsubproyek -->
                    <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 md:p-6 mb-6 space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-200 pb-3 mb-2">
                            <span class="text-sm font-bold text-slate-700">Daftar Subsub Proyek</span>
                            <span class="text-xs text-slate-400">Total Halaman & Subtotal akan terhitung otomatis</span>
                        </div>

                        <!-- LOOP SUB-SUB PROYEK -->
                        <template x-for="(ss, ssIdx) in sub.sub_sub" :key="ss.key">
                            <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm hover:shadow transition-all relative">

                                <!-- HAPUS SUBSUB BUTTON (X) -->
                                <button type="button"
                                    @click="hapusSubSubProyek(subIdx, ssIdx)"
                                    class="absolute top-4 right-4 text-slate-400 hover:text-red-500 transition-colors"
                                    title="Hapus Halaman Tugas">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>

                                <input type="hidden" :name="`sub_proyek[${subIdx}][sub_sub][${ssIdx}][id]`" x-model="ss.id">
                                <input type="hidden" :name="`sub_proyek[${subIdx}][sub_sub][${ssIdx}][total_halaman]`" x-model="ss.total_halaman">

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 pr-6">
                                    <!-- NAMA BAGIAN/HALAMAN -->
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-semibold text-slate-500 mb-1">Nama Halaman Tugas</label>
                                        <input type="text"
                                            :name="`sub_proyek[${subIdx}][sub_sub][${ssIdx}][nama_halaman]`"
                                            x-model="ss.nama_halaman"
                                            class="w-full px-3.5 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand-500"
                                            placeholder="Contoh: Halaman 1-50 (Bundel A)">
                                    </div>

                                    <!-- FILE PDF AREA -->
                                    <div class="border border-slate-100 bg-slate-50/50 rounded-lg p-3">
                                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">File Tugas PDF</label>

                                        <!-- PDF Existing (Edit Mode) -->
                                        <template x-if="ss.file_pdf_existing">
                                            <div class="flex items-center gap-2 text-xs bg-brand-50 border border-brand-100 text-brand-800 rounded-md p-2 mb-2">
                                                <span>📄</span>
                                                <a :href="`/storage/${ss.file_pdf_existing}`" target="_blank" class="underline font-semibold flex-1 truncate" x-text="ss.file_pdf_existing.split('/').pop()"></a>
                                                <span class="text-[10px] text-brand-500">(Tersimpan)</span>
                                            </div>
                                        </template>

                                        <input type="file"
                                            :id="`file_pdf_${subIdx}_${ssIdx}`"
                                            :name="ss.temp_pdf ? '' : `sub_proyek[${subIdx}][sub_sub][${ssIdx}][file_pdf]`"
                                            accept="application/pdf"
                                            @change="handlePdf($event, subIdx, ssIdx)"
                                            class="w-full text-xs text-slate-500 file:mr-3 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer">

                                        <input type="hidden"
                                            :name="`sub_proyek[${subIdx}][sub_sub][${ssIdx}][temp_pdf]`"
                                            x-model="ss.temp_pdf">

                                        <!-- REAL-TIME PAGE COUNT -->
                                        <!-- UPLOAD PROGRESS + PAGE COUNT -->
                                        <div class="mt-2 space-y-1.5">
                                            <!-- PROGRESS BAR (muncul saat upload) -->
                                            <template x-if="ss.isUploading">
                                                <div>
                                                    <div class="flex items-center justify-between text-xs mb-1">
                                                        <div class="flex items-center gap-1.5 text-brand-600 font-medium">
                                                            <svg class="animate-spin h-3.5 w-3.5" fill="none" viewBox="0 0 24 24">
                                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                            </svg>
                                                            <span>Mengunggah PDF...</span>
                                                        </div>
                                                        <!-- UBAH BAGIAN PERSENTASE INI DENGAN TOMBOL BATAL -->
                                                        <div class="flex items-center gap-2">
                                                            <span class="text-brand-700 font-bold" x-text="ss.uploadProgress + '%'"></span>
                                                            <button type="button"
                                                                @click="cancelUpload(subIdx, ssIdx)"
                                                                class="text-gray-400 hover:text-gray-600 font-bold text-[10px] px-1.5 py-0.5 transition">
                                                                X
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                                                        <div class="bg-brand-500 h-2 rounded-full transition-all duration-300 ease-out"
                                                            :style="'width: ' + ss.uploadProgress + '%'"></div>
                                                    </div>
                                                </div>
                                            </template>

                                            <!-- ERROR MESSAGE -->
                                            <template x-if="ss.uploadError">
                                                <div class="flex items-center gap-1.5 text-xs text-red-600 bg-red-50 border border-red-100 rounded-md p-2">
                                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span x-text="ss.uploadError"></span>
                                                </div>
                                            </template>

                                            <!-- JUMLAH HALAMAN (muncul saat tidak upload) -->
                                            <template x-if="!ss.isUploading">
                                                <div class="flex items-center gap-2 text-xs">
                                                    <span class="text-slate-500">Jumlah Halaman:</span>
                                                    <span class="font-bold text-slate-700" x-text="ss.total_halaman > 0 ? ss.total_halaman + ' Halaman' : '- Halaman (Upload PDF)'"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    <!-- FILE XLS AREA -->
                                    <div class="border border-slate-100 bg-slate-50/50 rounded-lg p-3">
                                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">File Template XLS</label>

                                        <!-- XLS Existing (Edit Mode) -->
                                        <template x-if="ss.file_xls_existing">
                                            <div class="flex items-center gap-2 text-xs bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-md p-2 mb-2">
                                                <span>📊</span>
                                                <span class="flex-1 truncate font-semibold" x-text="ss.file_xls_existing.split('/').pop()"></span>
                                                <span class="text-[10px] text-emerald-500">(Tersimpan)</span>
                                            </div>
                                        </template>

                                        <input type="file"
                                            :name="`sub_proyek[${subIdx}][sub_sub][${ssIdx}][file_xls]`"
                                            accept=".xls,.xlsx"
                                            class="w-full text-xs text-slate-500 file:mr-3 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                                    </div>

                                    <!-- HARGA PER LEMBAR -->
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-500 mb-1">Harga Per Lembar (Rp)</label>
                                        <input type="number"
                                            :name="`sub_proyek[${subIdx}][sub_sub][${ssIdx}][harga]`"
                                            x-model.number="ss.harga"
                                            @input="hitungSubtotal(subIdx, ssIdx)"
                                            class="w-full px-3.5 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand-500"
                                            placeholder="Contoh: 1500">
                                    </div>

                                    <!-- KUALITAS SELECT -->
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-500 mb-1">Kualitas</label>
                                        <select :name="`sub_proyek[${subIdx}][sub_sub][${ssIdx}][kualitas]`"
                                            x-model="ss.kualitas"
                                            class="w-full px-3.5 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 text-slate-700">
                                            <option value="">Pilih Kualitas</option>
                                            <option value="2">Sangat Kurang (2)</option>
                                            <option value="4">Kurang (4)</option>
                                            <option value="6">Sedang (6)</option>
                                            <option value="8">Baik (8)</option>
                                            <option value="10">Sangat Baik (10)</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- INDIVIDUAL SUB-SUB TOTAL -->
                                <div class="flex justify-between items-center bg-slate-50 border border-slate-100 p-2.5 rounded-lg text-xs font-semibold">
                                    <span class="text-slate-500">Subtotal Subsub Proyek:</span>
                                    <span class="text-emerald-600 font-bold text-sm" x-text="formatRupiah(ss.subtotal)"></span>
                                </div>
                            </div>
                        </template>

                        <!-- JIKA TIDAK ADA SUB-SUB -->
                        <template x-if="sub.sub_sub.length === 0">
                            <div class="text-center py-6 border-2 border-dashed border-slate-200 rounded-xl text-sm text-slate-400 bg-white">
                                Belum ada halaman tugas. Silakan tambahkan minimal 1.
                            </div>
                        </template>
                    </div>

                    <!-- BUTTON TAMBAH SUB-SUB -->
                    <x-primary-button type="button" @click="addSubSubProyek(subIdx)">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Tambah Subsub Proyek (Halaman)
                    </x-primary-button>
                </div>
            </template>
        </div>

        <!-- BUTTON UTAMA: TAMBAH SUB PROYEK -->
        <div class="mt-6 ">
            <x-primary-button type="button" @click="addSubProyek()" full>
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Tambah Sub Proyek Baru
            </x-primary-button>
        </div>

        <!-- STICKY FLOATING FOOTER SUMMARY (PERSISTENT & ACCESSIBLE) -->
        <div class="fixed bottom-0 left-0 lg:left-64 right-0 z-20 bg-white/95 backdrop-blur-md border-t border-slate-200 py-4 px-6 shadow-2xl flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-6">
                <!-- TOTAL HALAMAN -->
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Halaman Proyek</p>
                    <p class="text-xl font-bold text-brand-600"><span x-text="totalProyekHalaman"></span> Halaman</p>
                </div>
                <!-- ESTIMASI BIAYA -->
                <div class="border-l border-slate-200 pl-6">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Estimasi Harga</p>
                    <p class="text-xl font-bold text-emerald-600" x-text="formatRupiah(totalProyekHarga)"></p>
                </div>
            </div>

            <!-- BUTTON ACTIONS -->
            <x-primary-button type="submit">{{ $proyek ? 'Perbarui Proyek' : 'Simpan Proyek' }}</x-primary-button>
        </div>

    </form>
</div>

<script>
    function proyekForm() {
        return {
            nama_proyek: '',
            tanggal_mulai: '',
            tanggal_selesai: '',
            sub_proyek: [],

            uploadQueue: [],
            isUploadingGlobal: false,
            enqueueUpload(task) {
                this.uploadQueue.push(task);
                this.processQueue();
            },
            processQueue() {
                if (this.isUploadingGlobal) return;
                if (this.uploadQueue.length === 0) {
                    this.isUploadingGlobal = false;
                    return;
                }

                this.isUploadingGlobal = true;
                let task = this.uploadQueue.shift();

                task(() => {
                    this.isUploadingGlobal = false;
                    this.processQueue();
                });
            },

            initProyek(proyekData, oldSubProyek) {
                // 1. Inisialisasi field basic utama
                this.nama_proyek = @js(old('nama_proyek')) || (proyekData ? proyekData.nama_proyek : '');
                this.tanggal_mulai = @js(old('tanggal_mulai')) || (proyekData ? this.formatDateTime(proyekData.tanggal_mulai) : '');
                this.tanggal_selesai = @js(old('tanggal_selesai')) || (proyekData ? this.formatDateTime(proyekData.tanggal_selesai) : '');

                // 2. Inisialisasi struktur sub_proyek & sub_sub proyek
                if (oldSubProyek && Object.keys(oldSubProyek).length > 0) {
                    // Kasus A: Jika terdapat validasi error dari Laravel (old input)
                    this.sub_proyek = Object.values(oldSubProyek).map((sub, sIdx) => ({
                        key: 'sub_old_' + sIdx + '_' + Date.now(),
                        id: sub.id || null,
                        nama: sub.nama,
                        total_halaman: sub.total_halaman || 0,
                        sub_sub: sub.sub_sub ? Object.values(sub.sub_sub).map((ss, ssIdx) => ({
                            key: 'ss_old_' + sIdx + '_' + ssIdx + '_' + Date.now(),
                            id: ss.id || null,
                            nama_halaman: ss.nama_halaman,
                            file_pdf_existing: null,
                            file_xls_existing: null,
                            total_halaman: ss.total_halaman || 0,
                            harga: ss.harga || '',
                            kualitas: ss.kualitas || '',
                            subtotal: (parseInt(ss.harga) || 0) * (parseInt(ss.total_halaman) || 0),
                            isReadingPdf: false
                        })) : []
                    }));
                } else if (proyekData && proyekData.subproyeks) {
                    // Kasus B: Mode Edit Data Proyek dari Database
                    this.sub_proyek = proyekData.subproyeks.map((sub, sIdx) => ({
                        key: 'sub_db_' + sub.id,
                        id: sub.id,
                        nama: sub.nama_sub_proyek,
                        total_halaman: sub.total_halaman,
                        sub_sub: sub.subsubproyeks.map((ss, ssIdx) => ({
                            key: 'ss_db_' + ss.id,
                            id: ss.id,
                            nama_halaman: ss.nama_halaman,
                            file_pdf_existing: ss.file_pdf,
                            file_xls_existing: ss.file_xls,
                            total_halaman: ss.total_halaman,
                            harga: ss.harga_perlembar,
                            kualitas: ss.kualitas,
                            subtotal: ss.total_halaman * ss.harga_perlembar,
                            isReadingPdf: false
                        }))
                    }));
                } else {
                    // Kasus C: Form Baru
                    this.addSubProyek();
                }
            },

            formatDateTime(datetimeStr) {
                if (!datetimeStr) return '';
                let date = new Date(datetimeStr);
                let pad = (num) => String(num).padStart(2, '0');
                return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
            },

            addSubProyek() {
                this.sub_proyek.push({
                    key: 'sub_' + Date.now() + Math.random(),
                    id: null,
                    nama: '',
                    total_halaman: 0,
                    sub_sub: []
                });
                this.addSubSubProyek(this.sub_proyek.length - 1);
            },

            addSubSubProyek(subIndex) {
                this.sub_proyek[subIndex].sub_sub.push({
                    key: 'ss_' + Date.now() + Math.random(),
                    id: null,
                    nama_halaman: '',
                    file_pdf_existing: null,
                    file_xls_existing: null,
                    total_halaman: 0,
                    harga: '',
                    kualitas: '',
                    subtotal: 0,
                    isUploading: false,
                    uploadProgress: 0,
                    uploadError: null,
                    temp_pdf: null,
                    xhr: null
                });
            },

            hapusSubProyek(subIndex) {
                this.sub_proyek.splice(subIndex, 1);
            },

            hapusSubSubProyek(subIndex, ssIndex) {
                this.sub_proyek[subIndex].sub_sub.splice(ssIndex, 1);
                this.hitungTotalSub(subIndex);
            },

            async handlePdf(event, subIndex, ssIndex) {
                let file = event.target.files[0];
                if (!file) return;

                let ss = this.sub_proyek[subIndex].sub_sub[ssIndex];
                ss.uploadError = null;

                // Validasi ukuran file di frontend (500MB max)
                let maxSize = 500 * 1024 * 1024; // 500MB dalam bytes
                if (file.size > maxSize) {
                    ss.uploadError = `File terlalu besar (${(file.size / 1024 / 1024).toFixed(1)} MB). Maksimal 500 MB.`;
                    event.target.value = ''; // reset input file
                    return;
                }

                // Bungkus proses pengunggahan ke dalam antrean (queue)
                this.enqueueUpload((doneCallback) => {
                    ss.isUploading = true;
                    ss.uploadProgress = 0;

                    let formData = new FormData();
                    formData.append('file', file);
                    formData.append('_token', '{{ csrf_token() }}');

                    let xhr = new XMLHttpRequest();
                    ss.xhr = xhr;
                    xhr.open('POST', '{{ route("ajax.pdf.info") }}', true);

                    xhr.setRequestHeader('Accept', 'application/json');
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

                    // Monitor progress upload
                    xhr.upload.onprogress = (e) => {
                        if (e.lengthComputable) {
                            ss.uploadProgress = Math.round((e.loaded / e.total) * 100);
                        }
                    };

                    // Handler ketika upload dibatalkan
                    xhr.onabort = () => {
                        ss.isUploading = false;
                        ss.uploadProgress = 0;
                        ss.xhr = null;
                        ss.uploadError = 'Upload dibatalkan oleh pengguna.';

                        // Reset pilihan input file HTML
                        let fileInput = document.getElementById(`file_pdf_${subIndex}_${ssIndex}`);
                        if (fileInput) fileInput.value = '';

                        doneCallback(); // Lanjutkan antrean berikutnya
                    };

                    // Callback ketika selesai
                    xhr.onload = () => {
                        ss.isUploading = false;
                        ss.xhr = null;
                        if (xhr.status >= 200 && xhr.status < 300) {
                            try {
                                let data = JSON.parse(xhr.responseText);
                                ss.total_halaman = data.total_halaman || 0;
                                ss.temp_pdf = data.temp_pdf || null;
                                ss.uploadError = null;
                                this.hitungSubtotal(subIndex, ssIndex);
                            } catch (err) {
                                ss.uploadError = 'Gagal membaca respons server.';
                            }
                        } else {
                            // Reset input file jika terjadi error dari server
                            let fileInput = document.getElementById(`file_pdf_${subIndex}_${ssIndex}`);
                            if (fileInput) fileInput.value = '';

                            if (xhr.status === 413) {
                                ss.uploadError = 'File terlalu besar untuk server. Hubungi administrator.';
                            } else if (xhr.status === 422) {
                                try {
                                    let errData = JSON.parse(xhr.responseText);
                                    ss.uploadError = errData.message || 'File tidak valid.';
                                } catch {
                                    ss.uploadError = 'Validasi file gagal.';
                                }
                            } else {
                                ss.uploadError = `Upload gagal (status ${xhr.status}). Coba lagi.`;
                            }
                        }

                        doneCallback(); // Lanjutkan antrean berikutnya
                    };

                    xhr.onerror = () => {
                        ss.isUploading = false;
                        ss.xhr = null;
                        ss.uploadError = 'Koneksi terputus saat mengunggah. Periksa jaringan atau batasan proxy (Cloudflare).';

                        let fileInput = document.getElementById(`file_pdf_${subIndex}_${ssIndex}`);
                        if (fileInput) fileInput.value = '';

                        doneCallback(); // Lanjutkan antrean berikutnya
                    };

                    // BARIS xhr.timeout DAN xhr.ontimeout SEKARANG SUDAH DIHAPUS

                    xhr.send(formData);
                });
            },

            cancelUpload(subIndex, ssIndex) {
                let ss = this.sub_proyek[subIndex].sub_sub[ssIndex];
                if (ss.xhr) {
                    ss.xhr.abort(); // Membatalkan request. Ini otomatis memicu event onabort di atas
                }
            },

            hitungSubtotal(subIndex, ssIndex) {
                let ss = this.sub_proyek[subIndex].sub_sub[ssIndex];
                let harga = parseInt(ss.harga) || 0;
                let halaman = parseInt(ss.total_halaman) || 0;
                ss.subtotal = harga * halaman;

                this.hitungTotalSub(subIndex);
            },

            hitungTotalSub(subIndex) {
                let sub = this.sub_proyek[subIndex];
                sub.total_halaman = sub.sub_sub.reduce((acc, ss) => acc + parseInt(ss.total_halaman || 0), 0);
            },

            formatRupiah(value) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(value || 0);
            },

            get totalProyekHalaman() {
                return this.sub_proyek.reduce((acc, sub) => acc + parseInt(sub.total_halaman || 0), 0);
            },

            get totalProyekHarga() {
                return this.sub_proyek.reduce((acc, sub) => {
                    let subHarga = sub.sub_sub.reduce((subAcc, ss) => subAcc + (parseInt(ss.subtotal) || 0), 0);
                    return acc + subHarga;
                }, 0);
            }
        };
    }
</script>
@endsection