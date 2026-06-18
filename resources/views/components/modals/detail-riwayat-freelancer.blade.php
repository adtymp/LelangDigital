<x-modal-header
    show="modalUser"
    maxWidth="max-w-3xl">
    <x-slot name="header">
        <div>
            <h2
                class="text-xl font-bold text-white"
                x-text="pilihUser?.user?.name ?? '-'">
            </h2>

            <p
                class="mt-1 text-sm text-white/80"
                x-text="pilihUser?.user?.email ?? '-'">
            </p>
        </div>
    </x-slot>
    <div class="p-6 space-y-6 overflow-y-auto text-sm">

        <!-- informasi akun -->
        <div>
            <h3 class="text-brand-500 mb-4 flex items-center gap-2 font-bold uppercase">
                <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                    <path fill="currentColor" d="M320 312C386.3 312 440 258.3 440 192C440 125.7 386.3 72 320 72C253.7 72 200 125.7 200 192C200 258.3 253.7 312 320 312zM290.3 368C191.8 368 112 447.8 112 546.3C112 562.7 125.3 576 141.7 576L498.3 576C514.7 576 528 562.7 528 546.3C528 447.8 448.2 368 349.7 368L290.3 368z" />
                </svg>
                Informasi Akun
            </h3>
            <div class="border border-gray-200 rounded-2xl p-4 shadow-sm">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">

                    <div>
                        <p class="text-gray-400">Level</p>
                        <p class="font-medium text-gray-800"
                            x-text="pilihUser?.level?.nama_level ? 'Level ' + pilihUser.level.nama_level : '-'"></p>
                    </div>

                    <div>
                        <p class="text-gray-400">Total Poin</p>
                        <p class="font-medium text-gray-800"
                            x-text="pilihUser?.user?.poin_level ?? '-'"></p>
                    </div>

                    <div>
                        <p class="text-gray-400">Status</p>
                        <x-status-alpine status="pilihUser?.user?.status"></x-status-alpine>
                    </div>

                </div>
            </div>
        </div>


        <!-- BANK -->
        <div>
            <h3 class="text-brand-500 mb-4 flex items-center gap-2 font-bold uppercase">
                <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                    <path fill="currentColor" d="M512 176C520.8 176 528 183.2 528 192L528 224L112 224L112 192C112 183.2 119.2 176 128 176L512 176zM528 288L528 448C528 456.8 520.8 464 512 464L128 464C119.2 464 112 456.8 112 448L112 288L528 288zM128 128C92.7 128 64 156.7 64 192L64 448C64 483.3 92.7 512 128 512L512 512C547.3 512 576 483.3 576 448L576 192C576 156.7 547.3 128 512 128L128 128zM144 408C144 421.3 154.7 432 168 432L216 432C229.3 432 240 421.3 240 408C240 394.7 229.3 384 216 384L168 384C154.7 384 144 394.7 144 408zM288 408C288 421.3 298.7 432 312 432L376 432C389.3 432 400 421.3 400 408C400 394.7 389.3 384 376 384L312 384C298.7 384 288 394.7 288 408z" />
                </svg>
                Informasi Bank
            </h3>
            <div class="border border-gray-200 rounded-2xl p-4 shadow-sm">



                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-400">Bank</p>
                        <p class="font-medium text-gray-800 uppercase" x-text="pilihUser?.rekening?.nama_bank ?? '-'"></p>
                    </div>
                    <div>
                        <p class="text-gray-400">No Rekening</p>
                        <p class="font-medium text-gray-800" x-text="pilihUser?.rekening?.no_akun ?? '-'"></p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-gray-400">Nama</p>
                        <p class="font-medium text-gray-800" x-text="pilihUser?.rekening?.nama_akun ?? '-'"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- PORTOFOLIO -->
        <div>
            <h3 class="text-brand-500 mb-4 flex items-center gap-2 font-bold uppercase">
                <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                    <path fill="currentColor" d="M128 128C128 92.7 156.7 64 192 64L448 64C483.3 64 512 92.7 512 128L512 512C512 547.3 483.3 576 448 576L192 576C156.7 576 128 547.3 128 512L128 128zM208 432C208 440.8 215.2 448 224 448L416 448C424.8 448 432 440.8 432 432C432 387.8 396.2 352 352 352L288 352C243.8 352 208 387.8 208 432zM320 312C350.9 312 376 286.9 376 256C376 225.1 350.9 200 320 200C289.1 200 264 225.1 264 256C264 286.9 289.1 312 320 312z" />
                </svg>
                Portfolio
            </h3>
            <div class="border border-gray-200 rounded-2xl p-4 shadow-sm">
                <template x-if="!pilihUser?.portofolio">
                    <p class="text-gray-400 italic">Tidak ada</p>
                </template>

                <template x-if="pilihUser?.portofolio">
                    <div class="flex items-center justify-between border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition">

                        <div class="truncate text-gray-700">
                            <span x-text="pilihUser.portofolio.type === 'file' 
                                                                ? '📄 ' + pilihUser.portofolio.file_path 
                                                                : '🔗 ' + pilihUser.portofolio.link_url">
                            </span>
                        </div>

                        <a :href="pilihUser.portofolio.type === 'file' 
                                                                ? '/storage/' + pilihUser.portofolio.file_path 
                                                                : pilihUser.portofolio.link_url"
                            target="_blank"
                            class="text-xs px-3 py-1 border border-gray-200 rounded-lg hover:bg-gray-100">
                            Buka
                        </a>
                    </div>
                </template>
            </div>
        </div>

        <!-- RIWAYAT -->
        <div>
            <h3 class="text-brand-500 mb-4 flex items-center gap-2 font-bold uppercase">
                <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                    <path fill="currentColor" d="M320 128C426 128 512 214 512 320C512 426 426 512 320 512C254.8 512 197.1 479.5 162.4 429.7C152.3 415.2 132.3 411.7 117.8 421.8C103.3 431.9 99.8 451.9 109.9 466.4C156.1 532.6 233 576 320 576C461.4 576 576 461.4 576 320C576 178.6 461.4 64 320 64C234.3 64 158.5 106.1 112 170.7L112 144C112 126.3 97.7 112 80 112C62.3 112 48 126.3 48 144L48 256C48 273.7 62.3 288 80 288L104.6 288C105.1 288 105.6 288 106.1 288L192.1 288C209.8 288 224.1 273.7 224.1 256C224.1 238.3 209.8 224 192.1 224L153.8 224C186.9 166.6 249 128 320 128zM344 216C344 202.7 333.3 192 320 192C306.7 192 296 202.7 296 216L296 320C296 326.4 298.5 332.5 303 337L375 409C384.4 418.4 399.6 418.4 408.9 409C418.2 399.6 418.3 384.4 408.9 375.1L343.9 310.1L343.9 216z" />
                </svg>
                Riwayat Pekerjaan
            </h3>


            <div class="space-y-3">
                <div class="border border-gray-200 rounded-2xl p-3 shadow">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="uppercase text-xs">
                                    <th class="px-4 py-2 text-left text-brand-500">Nama Proyek</th>
                                    <th class="px-4 py-2 text-left text-brand-500">Rentang Halaman</th>
                                    <th class="px-4 py-2 text-left text-brand-500">Total Upah</th>
                                    <th class="px-4 py-2 text-center text-brand-500">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <template x-if="riwayatPembayaran.length">
                                    <template x-for="item in riwayatPembayaran" :key="item.id">
                                        <tr class="bg-white">
                                            <td class="px-4 py-3">
                                                <p class="text-gray-900 text-sm" x-text="item.nama_proyek"></p>
                                                <p class="text-gray-500 text-xs" x-text="item.nama_sub_proyek"></p>
                                            </td>
                                            <td class="px-4 py-3 text-gray-500 text-sm">
                                                <span x-text="item.pengambilan.dari_halaman"></span> - <span x-text="item.pengambilan.sampai_halaman"></span>
                                            </td>
                                            <td class="px-4 py-3 text-gray-900 text-sm" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(item.total_pembayaran)"></td>
                                            <td class="text-center px-4 py-3">
                                                <x-status-alpine status="item.status"></x-status-alpine>
                                            </td>
                                        </tr>
                                    </template>
                                </template>
                                <template x-if="!riwayatPembayaran.length">
                                    <tr>
                                        <td colspan="4" class="px-4 py-6 text-center text-gray-500 text-sm">
                                            Belum ada riwayat pekerjaan
                                        </td>
                                    </tr>
                                </template>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-modal-header>