@extends('layouts.body', ['title' => 'Manajemen Freelancer'])

@section('content')
<x-header
    :judul="'Persetujuan Pendaftaran Freelancer'"
    :subjudul="'Kelola permintaan pendaftaran akun freelancer baru'" />

<div class="mb-8">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <x-stat-card
            title="Permintaan" :value="$statusVerifikasi['permintaan'] ?? 0"
            color="yellow" bg="yellow-50" brdr="yellow" />

        <x-stat-card
            title="Diterima" :value="$statusVerifikasi['diterima'] ?? 0"
            color="green" bg="green-50" brdr="green" />

        <x-stat-card
            title="Ditolak" :value="$statusVerifikasi['ditolak'] ?? 0"
            color="red" bg="red-50" brdr="red" />
    </div>
</div>

<div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
    <x-search-filter
        :action="route('freelancer.halaman')"
        search-name="search"
        search-placeholder="Cari nama atau email freelancer..."
        :filters="[
        [
            'name' => 'status_verifikasi',
            'placeholder' => 'Semua Verifikasi',
            'options' => [
                'permintaan' => 'Permintaan',
                'diterima' => 'Diterima',
                'ditolak' => 'Ditolak',
            ]
        ],

        [
            'name' => 'status_akun',
            'placeholder' => 'Semua Status Akun',
            'options' => [
                'aktif' => 'Aktif',
                'nonaktif' => 'Non Aktif',
            ]
        ]
    ]" />

</div>
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-gray-900 font-semibold">Daftar Freelancer</h2>
    </div>
    <div x-data="{
                    modalDetail: false,
                    lihatDetail: null
                }" class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-gray-500">Nama</th>
                    <th class="px-6 py-3 text-left text-gray-500">Email</th>
                    <th class="px-6 py-3 text-left text-gray-500">No Telp</th>
                    <th class="px-6 py-3 text-left text-gray-500">Status</th>
                    <th class="px-6 py-3 text-left text-gray-500">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
                @forelse ($freelancers as $user)
                <tr class="bg-white">
                    <td class="px-6 py-3 text-gray-500 text-left font-semibold">{{ $user->name }}</td>
                    <td class="px-6 py-3 text-gray-500 text-left">{{ $user->email }}</td>
                    <td class="px-6 py-3 text-gray-500 text-left">{{ $user->no_telp }}</td>
                    <td class="px-6 py-3 text-gray-500 text-left">
                        <x-status :value="$user->status_verifikasi"></x-status>
                    </td>
                    <td class="px-6 py-3 text-gray-500 text-left flex justify-center gap-3">
                        <button @click="
                                        modalDetail = true;
                                        lihatDetail = @js($user)"
                            class="px-4 py-1.5 rounded-lg border hover:bg-gray-100 text-sm font-semibold">
                            Lihat Detail
                        </button>

                        <div x-show="modalDetail" x-transition
                            class="fixed inset-0 bg-black/40  flex items-center justify-center z-50 p-4">

                            <div class="bg-white rounded-2xl shadow-xl w-full max-w-xl overflow-hidden">

                                <div class="flex items-center justify-between px-6 py-4 border-b">
                                    <h2 class="text-lg font-semibold">Detail Freelancer</h2>
                                    <button
                                        @click="modalDetail = false; lihatDetail = null"
                                        class="text-gray-400 hover:text-gray-700 text-xl">
                                        ✕
                                    </button>
                                </div>

                                <div class="p-6 space-y-6">

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
                                                        <a :href="lihatDetail.portofolio.file_path" target="_blank"
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

                                <!-- FOOTER -->
                                <div class="px-6 py-4 border-t flex justify-end">
                                    <button
                                        @click="modalDetail = false; lihatDetail = null"
                                        class="px-4 py-2 text-sm rounded-lg bg-gray-100 hover:bg-gray-200">
                                        Tutup
                                    </button>
                                </div>

                            </div>
                        </div>

                        @if($user->isPending())

                        <form method="POST"
                            action="{{ route('freelancer.update-verifikasi', $user) }}"
                            class="confirm inline-block">
                            @csrf
                            <input type="hidden" name="status_verifikasi" value="diterima">

                            <button type="submit"
                                class="px-4 py-1.5 rounded-lg bg-green-600 hover:bg-green-700 text-white text-sm font-semibold">
                                Terima
                            </button>
                        </form>

                        <form method="POST"
                            action="{{ route('freelancer.update-verifikasi', $user) }}"
                            class="confirm inline-block">
                            @csrf
                            <input type="hidden" name="status_verifikasi" value="ditolak">

                            <button type="submit"
                                class="px-4 py-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-semibold">
                                Tolak
                            </button>
                        </form>
                        @endif

                        @if($user->isAccepted() && $user->isActiveAccount())

                        <form method="POST"
                            action="{{ route('freelancer.update-akun', $user) }}"
                            class="confirm inline-block">
                            @csrf
                            <input type="hidden" name="status_akun" value="nonaktif">

                            <button type="submit"
                                class="px-4 py-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-semibold">
                                Nonaktifkan
                            </button>
                        </form>

                        @endif

                        @if($user->isAccepted() && $user->isInactiveAccount())

                        <form method="POST"
                            action="{{ route('freelancer.update-akun', $user) }}"
                            class="confirm inline-block">
                            @csrf
                            <input type="hidden" name="status_akun" value="aktif">

                            <button type="submit"
                                class="px-4 py-1.5 rounded-lg bg-green-600 hover:bg-green-700 text-white text-sm font-semibold">
                                Aktifkan
                            </button>
                        </form>

                        @endif

                        @if($user->isRejected())

                        <form method="POST"
                            action="{{ route('freelancer.update-verifikasi', $user) }}"
                            class="confirm inline-block">
                            @csrf
                            <input type="hidden" name="status_verifikasi" value="permintaan">

                            <button type="submit"
                                class="px-4 py-1.5 rounded-lg bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold">
                                Ajukan Ulang
                            </button>
                        </form>

                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                        Belum ada freelancer
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection