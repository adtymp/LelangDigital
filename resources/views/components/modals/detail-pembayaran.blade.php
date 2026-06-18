<x-modal-header
    show="modalDetail"
    title="Detail Pembayaran"
    subtitle="Informasi lengkap pembayaran freelancer"
    maxWidth="max-w-5xl">
    <!-- Summary -->
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 mb-6">
        <!-- Total Pembayaran -->
        <div class="rounded-2xl border border-blue-100 bg-blue-50 p-5 shadow-sm">
            <p class="text-sm font-medium text-brand-500">Total Pembayaran</p>
            <p
                class="mt-2 text-2xl font-bold text-brand-500"
                x-text="pilihDetail?.total_pembayaran 
                                                            ? new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(pilihDetail.total_pembayaran) 
                                                            : '-'"></p>
        </div>

        <!-- Status -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Status Pembayaran</p>
            <div class="mt-3">
                <span
                    class="inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-semibold"
                    :class="{
                                                                    'bg-green-100 text-green-700 ring-1 ring-green-200': pilihDetail?.status === 'sudah_dibayar',
                                                                    'bg-red-100 text-red-700 ring-1 ring-red-200': pilihDetail?.status === 'belum_dibayar',
                                                                }"
                    x-text="pilihDetail?.status 
                                                                    ? pilihDetail.status.replaceAll('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) 
                                                                    : '-'">
                </span>
            </div>
        </div>
    </div>

    <!-- Informasi Proyek -->
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm mb-6">
        <div class="flex items-center text-base font-bold text-brand-500 mb-5 uppercase">
            <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                <path fill="currentColor" d="M88 289.6L64.4 360.2L64.4 160C64.4 124.7 93.1 96 128.4 96L267.1 96C280.9 96 294.4 100.5 305.5 108.8L343.9 137.6C349.4 141.8 356.2 144 363.1 144L480.4 144C515.7 144 544.4 172.7 544.4 208L544.4 224L179 224C137.7 224 101 250.4 87.9 289.6zM509.8 512L131 512C98.2 512 75.1 479.9 85.5 448.8L133.5 304.8C140 285.2 158.4 272 179 272L557.8 272C590.6 272 613.7 304.1 603.3 335.2L555.3 479.2C548.8 498.8 530.4 512 509.8 512z" />
            </svg>
            <h3 class="ml-2">Informasi Proyek</h3>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 mb-4">
            <div>
                <p class="text-sm font-medium text-gray-500">Nama Proyek</p>
                <p class="mt-1 text-sm text-gray-900" x-text="pilihDetail?.nama_proyek ?? '-'"></p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Sub Proyek</p>
                <p class="mt-1 text-sm text-gray-900" x-text="pilihDetail?.nama_sub_proyek ?? '-'"></p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm font-medium text-gray-500">Sub Sub Proyek</p>

                <div class="mt-1 text-sm text-gray-900 flex items-center">
                    <p x-text="pilihDetail?.nama_halaman ?? '-'"></p>
                    <p class="text-gray-500">
                        (
                        <span x-text="pilihDetail?.pengambilan?.dari_halaman ?? '-'"></span>
                        -
                        <span x-text="pilihDetail?.pengambilan?.sampai_halaman ?? '-'"></span>
                        )
                        Halaman
                    </p>
                </div>
            </div>
        </div>
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between rounded-xl border border-dashed border-gray-300 bg-gray-50 p-4">
            <div>
                <p class="text-sm font-medium text-gray-800">File tugas freelancer</p>
                <p class="text-sm text-gray-500">Unduh file hasil pekerjaan untuk verifikasi.</p>
            </div>

            <a :href="pilihDetail?.pengambilan?.id ? `/pembayaran/${pilihDetail.pengambilan.id}/download/hasil` : '#'"
                target="_blank"
                class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-100"
                :class="{ 'pointer-events-none opacity-50': !pilihDetail?.pengambilan?.id }">
                Download File
            </a>
        </div>
    </div>

    <!-- Main Detail Grid -->
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">

        <!-- Left Content -->
        <div class="xl:col-span-2 space-y-6">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm mb-6">
                <div class="flex items-center text-base font-bold text-brand-500 mb-5 uppercase">
                    <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                        <path fill="currentColor" d="M88 289.6L64.4 360.2L64.4 160C64.4 124.7 93.1 96 128.4 96L267.1 96C280.9 96 294.4 100.5 305.5 108.8L343.9 137.6C349.4 141.8 356.2 144 363.1 144L480.4 144C515.7 144 544.4 172.7 544.4 208L544.4 224L179 224C137.7 224 101 250.4 87.9 289.6zM509.8 512L131 512C98.2 512 75.1 479.9 85.5 448.8L133.5 304.8C140 285.2 158.4 272 179 272L557.8 272C590.6 272 613.7 304.1 603.3 335.2L555.3 479.2C548.8 498.8 530.4 512 509.8 512z" />
                    </svg>
                    <h3 class="ml-2">Rincian Poin per Aspek</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <template x-for="(nilai, aspek) in pilihDetail?.penilaian?.skor" :key="aspek">
                        <div class="p-3 bg-slate-50/50 rounded-xl border border-slate-200/60 flex flex-col justify-between">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider" x-text="aspek.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())"></span>
                                <span class="text-xs font-extrabold" :class="nilai >= 8 ? 'text-emerald-600' : (nilai >= 6 ? 'text-amber-500' : 'text-red-500')" x-text="nilai + '/10'"></span>
                            </div>
                            <!-- Catatan Evaluasi jika ada -->
                            <template x-if="pilihDetail?.penilaian?.catatan && pilihDetail?.penilaian?.catatan[aspek]">
                                <p class="text-[11px] text-slate-500 mt-2 bg-white p-2 rounded-lg border border-slate-150 leading-relaxed font-medium" x-text="pilihDetail.penilaian.catatan[aspek]"></p>
                            </template>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Informasi Freelancer -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex items-center text-base font-bold text-brand-500 mb-5 uppercase">
                    <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                        <path fill="currentColor" d="M320 312C386.3 312 440 258.3 440 192C440 125.7 386.3 72 320 72C253.7 72 200 125.7 200 192C200 258.3 253.7 312 320 312zM290.3 368C191.8 368 112 447.8 112 546.3C112 562.7 125.3 576 141.7 576L498.3 576C514.7 576 528 562.7 528 546.3C528 447.8 448.2 368 349.7 368L290.3 368z" />
                    </svg>
                    <h3 class="ml-2">Informasi Freelancer</h3>
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Nama Freelancer</p>
                        <p class="mt-1 text-sm text-gray-900" x-text="pilihDetail?.user?.name ?? '-'"></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Level Freelancer</p>
                        <p class="mt-1 text-sm text-gray-900" x-text="pilihDetail?.level?.nama_level ?? '-'"></p>
                    </div>
                </div>
            </div>

            <!-- Informasi Bank -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex items-center text-base font-bold text-brand-500 mb-5 uppercase">
                    <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                        <path fill="currentColor" d="M512 176C520.8 176 528 183.2 528 192L528 224L112 224L112 192C112 183.2 119.2 176 128 176L512 176zM528 288L528 448C528 456.8 520.8 464 512 464L128 464C119.2 464 112 456.8 112 448L112 288L528 288zM128 128C92.7 128 64 156.7 64 192L64 448C64 483.3 92.7 512 128 512L512 512C547.3 512 576 483.3 576 448L576 192C576 156.7 547.3 128 512 128L128 128zM144 408C144 421.3 154.7 432 168 432L216 432C229.3 432 240 421.3 240 408C240 394.7 229.3 384 216 384L168 384C154.7 384 144 394.7 144 408zM288 408C288 421.3 298.7 432 312 432L376 432C389.3 432 400 421.3 400 408C400 394.7 389.3 384 376 384L312 384C298.7 384 288 394.7 288 408z" />
                    </svg>
                    <h3 class="ml-2">Informasi Rekening</h3>
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Nama Bank</p>
                        <p class="mt-1 text-blue-600 font-semibold text-xs uppercase" x-text="pilihDetail?.rekening?.nama_bank ?? '-'"></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Nomor Rekening</p>
                        <p class="mt-1 text-sm text-gray-900 break-all" x-text="pilihDetail?.rekening?.no_akun ?? '-'"></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Nama Pemilik Rekening</p>
                        <p class="mt-1 text-sm text-gray-900" x-text="pilihDetail?.rekening?.nama_akun ?? '-'"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="space-y-6">

            <!-- Upload / Bukti Transfer -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Bukti Transfer</h3>

                <!-- Jika belum ada bukti transfer -->
                <template x-if="!pilihDetail?.bukti_transfer">
                    <form action="{{ route('pembayaran.update') }}" method="post" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <input type="hidden" name="id" :value="pilihDetail?.id">

                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Upload bukti transfer
                            </label>
                            <input
                                type="file"
                                name="bukti_transfer"
                                accept="image/*"
                                @change="
                                                                                let file = $event.target.files[0];
                                                                                if (file) {
                                                                                    paymentProof = URL.createObjectURL(file);
                                                                                }
                                                                            "
                                class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 file:mr-4 file:rounded-md file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-brand-500 hover:file:bg-blue-100">
                        </div>

                        <template x-if="paymentProof">
                            <div class="rounded-xl border border-gray-200 bg-gray-50 p-3">
                                <p class="mb-2 text-sm font-medium text-gray-700">Preview</p>
                                <img :src="paymentProof" class="max-h-72 w-full rounded-lg border object-contain bg-white">
                            </div>
                        </template>

                        <button
                            type="submit"
                            class="inline-flex w-full items-center justify-center rounded-lg bg-brand-500 px-4 py-3 text-sm font-semibold text-white transition hover:bg-brand-500">
                            Konfirmasi Pembayaran
                        </button>
                    </form>
                </template>

                <!-- Jika sudah ada bukti transfer -->
                <template x-if="pilihDetail?.bukti_transfer">
                    <div class="space-y-4">
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-3">
                            <p class="mb-2 text-sm font-medium text-gray-700">Preview Bukti Transfer</p>
                            <img
                                :src="'/storage/' + pilihDetail.bukti_transfer"
                                class="max-h-80 w-full rounded-lg border object-contain bg-white">
                        </div>

                        <div class="rounded-xl border border-green-200 bg-green-50 p-4">
                            <p class="text-sm font-semibold text-green-700">Pembayaran telah dikonfirmasi</p>
                            <p class="mt-1 text-sm text-green-600">
                                Bukti transfer sudah tersedia pada sistem.
                            </p>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Catatan -->
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 shadow-sm">
                <h4 class="text-sm font-semibold text-amber-800">Catatan Verifikasi</h4>
                <p class="mt-2 text-sm leading-6 text-amber-700">
                    Pastikan nominal pembayaran, rekening tujuan, dan file pekerjaan sudah sesuai sebelum melakukan konfirmasi pembayaran.
                </p>
            </div>
        </div>
    </div>
</x-modal-header>