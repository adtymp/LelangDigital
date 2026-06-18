<x-modal-header
    show="modalDetail"
    title="Detail Riwayat Pekerjaan"
    subtitle="Rincian informasi proyek dan evaluasi penilaian Anda."
    maxWidth="max-w-5xl">

    <div class="overflow-y-auto space-y-6 flex-1 bg-slate-50/30">

        <!-- Informasi Proyek -->
        <div class="bg-white rounded-2xl border border-gray-200 p-5 space-y-4 shadow-sm">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">Informasi Proyek</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-slate-400 text-xs font-medium">Nama Proyek</span>
                    <p class="font-bold text-slate-800 mt-0.5" x-text="lihatDetail?.penilaian?.pengambilan?.subsubproyeks?.subproyeks?.proyeks?.nama_proyek ?? '-'"></p>
                </div>
                <div>
                    <span class="text-slate-400 text-xs font-medium">Sub Proyek</span>
                    <p class="font-semibold text-slate-700 mt-0.5" x-text="lihatDetail?.penilaian?.pengambilan?.subsubproyeks?.subproyeks?.nama_sub_proyek ?? '-'"></p>
                </div>
                <div>
                    <span class="text-slate-400 text-xs font-medium">Sub Sub Proyek / Halaman</span>
                    <p class="font-semibold text-slate-700 mt-0.5" x-text="lihatDetail?.penilaian?.pengambilan?.subsubproyeks?.nama_halaman ?? '-'"></p>
                </div>
                <div>
                    <span class="text-slate-400 text-xs font-medium">Rentang Halaman Pengerjaan</span>
                    <p class="font-semibold text-slate-700 mt-0.5" x-text="(lihatDetail?.penilaian?.pengambilan?.dari_halaman ?? '-') + ' - ' + (lihatDetail?.penilaian?.pengambilan?.sampai_halaman ?? '-')"></p>
                </div>
                <div>
                    <span class="text-slate-400 text-xs font-medium">Tanggal Selesai</span>
                    <p class="font-semibold text-slate-700 mt-0.5" x-text="lihatDetail ? new Date(lihatDetail.updated_at).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'}) + ' WIB' : '-'"></p>
                </div>
                <div>
                    <span class="text-slate-400 text-xs font-medium">Total Upah (Pembayaran)</span>
                    <p class="text-xl font-extrabold text-emerald-600 mt-0.5" x-text="lihatDetail ? new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(lihatDetail.total_pembayaran) : '-'"></p>
                </div>
            </div>
        </div>

        <!-- Bagian Download File Excel (Hasil Tugas) -->
        <div class="bg-white rounded-2xl border border-gray-200 p-5 space-y-3 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h4 class="font-bold text-slate-800 text-sm">Berkas Hasil Pekerjaan</h4>
                <p class="text-xs text-slate-500 mt-0.5">Lembar kerja spreadsheet (.xlsx) yang telah Anda kumpulkan.</p>
            </div>
            <a :href="'/penilaian/' + lihatDetail?.penilaian?.pengambilan?.id + '/download/hasil'"
                target="_blank"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-brand-50 hover:bg-brand-100 text-brand-700 rounded-xl transition text-xs font-bold shadow-xs">
                <i class="fas fa-file-excel text-emerald-600 text-sm"></i> Unduh File Hasil (.xlsx)
            </a>
        </div>

        <!-- Hasil Penilaian (Total Skor, Poin, Skor per aspek, & catatan) -->
        <div class="bg-white rounded-2xl border border-gray-200 p-5 space-y-4 shadow-sm">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">Hasil Evaluasi & Poin</h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Total Skor Card -->
                <div class="flex items-center gap-4 bg-slate-50/50 p-4 rounded-xl border border-slate-100">
                    <div class="w-12 h-12 rounded-full bg-brand-600 flex items-center justify-center shrink-0 shadow-xs">
                        <span class="text-base font-extrabold text-white" x-text="lihatDetail?.penilaian?.total_skor"></span>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Skor</p>
                        <p class="text-xs font-bold text-slate-700 mt-0.5">Evaluasi Akurasi & Kualitas</p>
                    </div>
                </div>

                <!-- Total Poin Card -->
                <div class="flex items-center gap-4 bg-slate-50/50 p-4 rounded-xl border border-slate-100">
                    <div class="w-12 h-12 rounded-full bg-amber-500 flex items-center justify-center shrink-0 shadow-xs">
                        <span class="text-base font-extrabold text-white" x-text="'+' + (lihatDetail?.penilaian?.total_poin ?? 0)"></span>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Poin Diperoleh</p>
                        <p class="text-xs font-bold text-slate-700 mt-0.5">Poin Reputasi Akun</p>
                    </div>
                </div>
            </div>

            <!-- Aspek Detail (skor & catatan JSON) -->
            <div class="pt-2">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Rincian Nilai per Aspek</p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <template x-for="(nilai, aspek) in lihatDetail?.penilaian?.skor" :key="aspek">
                        <div class="p-3 bg-slate-50/50 rounded-xl border border-gray-200 flex flex-col justifysmetween">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider" x-text="aspek.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())"></span>
                                <span class="text-xs font-extrabold" :class="nilai >= 8 ? 'text-emerald-600' : (nilai >= 6 ? 'text-amber-500' : 'text-red-500')" x-text="nilai + '/10'"></span>
                            </div>
                            <!-- Catatan Evaluasi jika ada -->
                            <template x-if="lihatDetail?.penilaian?.catatan && lihatDetail?.penilaian?.catatan[aspek]">
                                <p class="text-[11px] text-slate-500 mt-2 bg-white p-2 rounded-lg border border-slate-150 leading-relaxed font-medium" x-text="lihatDetail.penilaian.catatan[aspek]"></p>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Bukti Transfer & Status Pembayaran -->
        <div class="bg-white rounded-2xl border border-gray-200 p-5 space-y-4 shadow-sm">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">Status Pembayaran</h4>

            <div class="flex items-center gap-3">
                <span class="text-xs font-semibold text-slate-600">Status Saat Ini:</span>
                <x-status-alpine status="lihatDetail?.status"></x-status-alpine>
            </div>

            <!-- Klik & Lihat Bukti Transfer secara dinamis (PDF/Image) -->
            <template x-if="lihatDetail?.bukti_transfer">
                <div class="space-y-3 pt-2">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Bukti Transfer</span>

                    <!-- PDF Dokumen -->
                    <template x-if="lihatDetail.bukti_transfer.endsWith('.pdf')">
                        <a :href="'/storage/' + lihatDetail.bukti_transfer"
                            target="_blank"
                            class="inline-flex items-center gap-2.5 px-4 py-3 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl transition text-xs font-bold text-slate-700 shadow-xs">
                            <i class="fas fa-file-pdf text-red-500 text-lg"></i> Lihat Dokumen PDF Bukti Transfer
                        </a>
                    </template>

                    <!-- Gambar Image (Dapat di klik & lihat) -->
                    <template x-if="!lihatDetail.bukti_transfer.endsWith('.pdf')">
                        <div class="space-y-2">
                            <a :href="'/storage/' + lihatDetail.bukti_transfer"
                                target="_blank"
                                class="group block relative overflow-hidden rounded-2xl border border-slate-200 max-w-xs shadow-xs hover:border-brand-500 transition-colors">
                                <img :src="'/storage/' + lihatDetail.bukti_transfer" class="w-full object-cover max-h-60 group-hover:scale-[1.02] transition-transform duration-300">
                                <div class="absolute inset-0 bg-slate-950/25 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity duration-200">
                                    <span class="bg-white/95 px-3 py-1.5 rounded-lg text-[10px] font-bold text-slate-800 shadow-sm flex items-center gap-1.5">
                                        <i class="fas fa-external-link-alt text-xs"></i> Buka Gambar Ukuran Penuh
                                    </span>
                                </div>
                            </a>
                        </div>
                    </template>
                </div>
            </template>
        </div>

    </div>

</x-modal-header>