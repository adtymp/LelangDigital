<x-modal-header
    show="showModal"
    titleAlpine="detail.nama"
    subtitle="Informasi freelancer yang mengambil tugas"
    maxWidth="max-w-5xl">
    <!-- INFORMASI -->
    <div class="space-y-4">
        <div class="grid md:grid-cols-2 gap-4 m">

            <div class="rounded-2xl border border-gray-200 p-5 shadow-sm">
                <p class="text-sm font-medium text-gray-500">Proyek</p>
                <p class="mt-2 text-2xl font-bold uppercase text-brand-500" x-text="detail.proyek"></p>
            </div>

            <div class="rounded-2xl border border-gray-200  p-5 shadow-sm">
                <p class="text-sm font-medium text-gray-500">Sub Proyek</p>
                <p class="mt-2 text-2xl font-bold text-brand-500" x-text="detail.subproyek"></p>
            </div>

        </div>

        <!-- STAT -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

            <div class="bg-blue-50 rounded-2xl p-4 shadow border border-blue-200">
                <p class="text-xs text-blue-600">
                    Total Halaman
                </p>
                <p class="font-bold text-xl"
                    x-text="detail.total_halaman"></p>
            </div>

            <div class="bg-amber-50 rounded-2xl p-4 shadow border border-yellow-200">
                <p class="text-xs text-yellow-600">
                    Harga/Lembar
                </p>
                <p class="font-bold">
                    Rp <span x-text="Number(detail.harga_perlembar).toLocaleString('id-ID')"></span>
                </p>
            </div>

            <div class="bg-emerald-50 rounded-2xl p-4 shadow border border-green-200">
                <p class="text-xs text-green-600">
                    Subtotal
                </p>
                <p class="font-bold">
                    Rp <span x-text="Number(detail.subtotal).toLocaleString('id-ID')"></span>
                </p>
            </div>

            <div class="bg-purple-50 rounded-2xl p-4 shadow border border-purple-200">
                <p class="text-xs text-purple-600">
                    Freelancer
                </p>
                <p class="font-bold text-xl"
                    x-text="detail.pengambilans?.length ?? 0"></p>
            </div>

        </div>

        <!-- LIST PENGAMBIL -->
        <div>

            <div class="flex items-center justify-between mb-4">
                <h4 class="font-semibold text-slate-800">
                    Daftar Pengambil
                </h4>

                <span class="text-xs px-3 py-1 rounded-full bg-slate-100">
                    <span x-text="detail.pengambilans?.length ?? 0"></span>
                    Freelancer
                </span>
            </div>

            <div class="space-y-3">

                <template
                    x-for="item in detail.pengambilans"
                    :key="item.id">

                    <div class="border border-gray-200 rounded-2xl p-4">

                        <div class="flex justify-between items-center">

                            <div>
                                <p class="font-semibold"
                                    x-text="item.user?.name ?? '-'"></p>

                                <p class="text-xs text-gray-500">
                                    Halaman
                                    <span x-text="item.dari_halaman"></span>
                                    -
                                    <span x-text="item.sampai_halaman"></span>
                                </p>
                            </div>

                            <x-status-alpine status="item.status"></x-status-alpine>

                        </div>

                    </div>

                </template>

                <template
                    x-if="!detail.pengambilans || detail.pengambilans.length === 0">

                    <div class="text-center py-8 text-gray-400">
                        Belum ada pengambil
                    </div>

                </template>

            </div>

        </div>
    </div>
</x-modal-header>