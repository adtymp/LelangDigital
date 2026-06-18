<x-modal-header
    show="modalDetail"
    title="Detail Freelancer"
    subtitle="Informasi penerimaan freelancer baru"
    maxWidth="max-w-xl">
    <div class="space-y-6">
        <div>
            <h3 class="text-sm font-semibold text-gray-600 mb-3">Informasi Akun</h3>
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-400">Nama</p>
                        <p class="font-medium text-gray-800" x-text="lihatDetail?.name ?? '-'"></p>
                    </div>

                    <div>
                        <p class="text-gray-400">Email</p>
                        <p class="font-medium text-gray-800 break-all" x-text="lihatDetail?.email ?? '-'"></p>
                    </div>

                    <div class="col-span-2">
                        <p class="text-gray-400 mb-1">Status</p>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"
                            :class="{
                                                                    'bg-yellow-100 text-yellow-700': lihatDetail?.status_verifikasi === 'permintaan',
                                                                    'bg-green-100 text-green-700': lihatDetail?.status_verifikasi === 'diterima',
                                                                    'bg-red-100 text-red-700': lihatDetail?.status_verifikasi === 'ditolak'
                                                                }"
                            x-text="lihatDetail?.status_verifikasi 
                                                                    ? lihatDetail.status_verifikasi.replaceAll('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) 
                                                                    : '-'">>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <h3 class="text-sm font-semibold text-gray-600 mb-3">Data Bank</h3>
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-400">Bank</p>
                        <p class="font-medium text-gray-800" x-text="lihatDetail?.rekening?.nama_bank ?? '-'"></p>
                    </div>

                    <div>
                        <p class="text-gray-400">No Rekening</p>
                        <p class="font-medium text-gray-800" x-text="lihatDetail?.rekening?.no_akun ?? '-'"></p>
                    </div>

                    <div class="col-span-2">
                        <p class="text-gray-400">Nama Pemilik</p>
                        <p class="font-medium text-gray-800" x-text="lihatDetail?.rekening?.nama_akun ?? '-'"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- PORTOFOLIO -->
        <div>
            <h3 class="text-sm font-semibold text-gray-600 mb-3">Portofolio</h3>

            <template x-if="!lihatDetail?.portofolio">
                <div class="text-sm text-gray-400 italic">
                    Tidak ada portofolio
                </div>
            </template>

            <template x-if="lihatDetail?.portofolio">
                <div class="space-y-3">

                    <!-- FILE -->
                    <template x-if="lihatDetail.portofolio.type === 'file'">
                        <div class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50 transition">
                            <div class="text-sm text-gray-700 truncate">
                                📄 <span x-text="lihatDetail.portofolio.file_path"></span>
                            </div>
                            <a :href="lihatDetail.portofolio_url" target="_blank"
                                class="text-xs px-3 py-1 border rounded-lg hover:bg-gray-100">
                                Lihat
                            </a>
                        </div>
                    </template>

                    <!-- LINK -->
                    <template x-if="lihatDetail.portofolio.type === 'link'">
                        <div class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50 transition">
                            <div class="text-sm text-brand-500 truncate">
                                🔗 <span x-text="lihatDetail.portofolio.link_url"></span>
                            </div>
                            <a :href="lihatDetail.portofolio.link_url" target="_blank"
                                class="text-xs px-3 py-1 border rounded-lg hover:bg-gray-100">
                                Kunjungi
                            </a>
                        </div>
                    </template>

                </div>
            </template>
        </div>
    </div>
</x-modal-header>