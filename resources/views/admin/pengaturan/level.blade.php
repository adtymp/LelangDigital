@extends('layouts.body', ['title' => 'Pengaturan Level'])

@section('content')
<x-header
    :judul="'Pengaturan Level'"
    :subjudul="'Kelola batas minimum poin untuk setiap level'" />
<div x-data="tabelLevel()">

    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6 space-y-4">

        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-gray-900 font-semibold">
                    Pengaturan Reset Level
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Atur jadwal reset level otomatis
                </p>
            </div>

            <x-primary-button x-show="!editReset" @click="mulaiEditReset">Edit Pengaturan</x-primary-button>

        </div>

        <div class="mt-4 bg-gray-50 border border-gray-200 rounded-lg p-4">

            <p class="text-sm text-gray-500 mb-1">
                Reset level berikutnya
            </p>

            <p
                x-text="countdown"
                class="text-xl font-semibold text-brand-500">
            </p>

        </div>

        <form @submit.prevent="simpanResetLevel">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">

                <!-- STATUS -->
                <div>
                    <label class="block text-sm text-gray-500 mb-2">
                        Status Reset
                    </label>

                    <select
                        x-model="resetLevel.status"
                        :disabled="!editReset"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg
                                focus:outline-none focus:ring-2 focus:ring-brand-500
                                disabled:bg-gray-100 disabled:text-gray-500">

                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>

                    </select>
                </div>

                <!-- INTERVAL -->
                <div>
                    <label class="block text-sm text-gray-500 mb-2">
                        Interval Hari
                    </label>

                    <input
                        type="number"
                        min="1"
                        x-model="resetLevel.lama_hari"
                        :disabled="!editReset"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg
                                focus:outline-none focus:ring-2 focus:ring-brand-500
                                disabled:bg-gray-100 disabled:text-gray-500">
                </div>

                <!-- JAM -->
                <div>
                    <label class="block text-sm text-gray-500 mb-2">
                        Jam Reset
                    </label>

                    <input
                        type="time"
                        x-model="resetLevel.jam_reset"
                        :disabled="!editReset"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg
                                focus:outline-none focus:ring-2 focus:ring-brand-500
                                disabled:bg-gray-100 disabled:text-gray-500">
                </div>

            </div>

            <!-- BUTTON ACTION -->
            <div
                x-show="editReset"
                x-transition
                class="flex gap-3">

                <button
                    type="submit"
                    :disabled="loadingReset"
                    class="bg-brand-500 text-white px-4 py-2 rounded-lg hover:bg-brand-700">

                    <span x-show="!loadingReset">
                        Simpan
                    </span>

                    <span x-show="loadingReset">
                        Menyimpan...
                    </span>

                </button>

                <button
                    type="button"
                    @click="batalEditReset"
                    class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">

                    Batal

                </button>

            </div>

        </form>

    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-8">
        <h2 class="text-gray-900  font-semibold mb-4">Tambah Threshold Level Baru</h2>
        <form action="{{ route('level.tambah') }}" method="post">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <x-input namaLabel="Level" type="number" namaInput="nama_level" value="1" min="1"></x-input>
                </div>
                <div>
                    <x-input namaLabel="Minimum Poin" type="number" namaInput="min_poin" value="0" min="0"></x-input>
                </div>
                <div>
                    <x-input namaLabel="Delay Notifikasi (Menit)" type="number" namaInput="delay_notifikasi" value="0" min="0"></x-input>
                </div>
                <div class="flex items-end">
                    <x-primary-button type="submit" full>Tambah Level</x-primary-button>
                </div>
            </div>
        </form>
        <div x-show="errorMsg"
            x-text="errorMsg"
            class="mt-3 text-sm text-red-600">
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-linear-to-r from-brand-500 to-brand-700 rounded-t-2xl">
            <h2 class="text-white text-lg font-semibold">Daftar Threshold Level</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 text-brand-500 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-3 text-left">Level</th>
                        <th class="px-6 py-3 text-left">Minimum Poin</th>
                        <th class="px-6 py-3 text-left">Delay Notifikasi</th>
                        <th class="px-6 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody x-init="levels = @js($levels)" class="divide-y divide-gray-200">
                    <template x-if="levels.length === 0">
                        <tr>
                            <td colspan="4">
                                <x-list-empty title="Tidak Ada Threshold Level" subtitle="Tambahkan threshold pertama.">
                                    <x-slot:icon>
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M9 8h6m2 12H7a2 2 0 01-2-2V6a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V18a2 2 0 01-2 2z" />
                                        </svg>
                                    </x-slot:icon>
                                </x-list-empty>
                            </td>
                        </tr>
                    </template>
                    <template x-for="level in levels" :key="level.id">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <span x-show="!editLevel || editLevel.id !== level.id" x-text="level.nama_level"></span>
                            </td>
                            <td class="px-6 py-4">
                                <span x-show="!editLevel || editLevel.id !== level.id" x-text="level.min_poin"></span>

                                <template x-if="editLevel && editLevel.id === level.id">
                                    <input type="number" x-model="editLevel.min_poin" class="px-3 py-2 border border-gray-200 rounded focus:outline-none focus:ring-2 focus:ring-brand-500" />
                                </template>
                            </td>
                            <td class="px-6 py-4">
                                <span x-show="!editLevel || editLevel.id !== level.id" x-text="level.delay_notifikasi"></span>

                                <template x-if="editLevel && editLevel.id === level.id">
                                    <input type="number" x-model="editLevel.delay_notifikasi" class="px-3 py-2 border border-gray-200 rounded focus:outline-none focus:ring-2 focus:ring-brand-500" />
                                </template>
                                Menit
                            </td>
                            <td>
                                <div x-show="editLevel?.id !== level.id" class="flex gap-3">
                                    <button @click="klikEdit(level)"
                                        class="text-blue-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="18" width="18" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                                            <path fill="currentColor" d="M505 122.9L517.1 135C526.5 144.4 526.5 159.6 517.1 168.9L488 198.1L441.9 152L471 122.9C480.4 113.5 495.6 113.5 504.9 122.9zM273.8 320.2L408 185.9L454.1 232L319.8 366.2C316.9 369.1 313.3 371.2 309.4 372.3L250.9 389L267.6 330.5C268.7 326.6 270.8 323 273.7 320.1zM437.1 89L239.8 286.2C231.1 294.9 224.8 305.6 221.5 317.3L192.9 417.3C190.5 425.7 192.8 434.7 199 440.9C205.2 447.1 214.2 449.4 222.6 447L322.6 418.4C334.4 415 345.1 408.7 353.7 400.1L551 202.9C579.1 174.8 579.1 129.2 551 101.1L538.9 89C510.8 60.9 465.2 60.9 437.1 89zM152 128C103.4 128 64 167.4 64 216L64 488C64 536.6 103.4 576 152 576L424 576C472.6 576 512 536.6 512 488L512 376C512 362.7 501.3 352 488 352C474.7 352 464 362.7 464 376L464 488C464 510.1 446.1 528 424 528L152 528C129.9 528 112 510.1 112 488L112 216C112 193.9 129.9 176 152 176L264 176C277.3 176 288 165.3 288 152C288 138.7 277.3 128 264 128L152 128z" />
                                        </svg>
                                    </button>
                                    <button
                                        @click="
                                            hapusModal = true
                                            hapusId = level.id
                                        "
                                        class="text-red-600">

                                        <svg xmlns="http://www.w3.org/2000/svg" height="18" width="18" viewBox="0 0 640 640">
                                            <path fill="currentColor" d="M262.2 48C248.9 48 236.9 56.3 232.2 68.8L216 112L120 112C106.7 112 96 122.7 96 136C96 149.3 106.7 160 120 160L520 160C533.3 160 544 149.3 544 136C544 122.7 533.3 112 520 112L424 112L407.8 68.8C403.1 56.3 391.2 48 377.8 48L262.2 48zM128 208L128 512C128 547.3 156.7 576 192 576L448 576C483.3 576 512 547.3 512 512L512 208L464 208L464 512C464 520.8 456.8 528 448 528L192 528C183.2 528 176 520.8 176 512L176 208L128 208zM288 280C288 266.7 277.3 256 264 256C250.7 256 240 266.7 240 280L240 456C240 469.3 250.7 480 264 480C277.3 480 288 469.3 288 456L288 280zM400 280C400 266.7 389.3 256 376 256C362.7 256 352 266.7 352 280L352 456C352 469.3 362.7 480 376 480C389.3 480 400 469.3 400 456L400 280z" />
                                        </svg>

                                    </button>
                                    <div x-show="hapusModal" x-transition.opacity
                                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4">

                                        <div x-transition class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl">

                                            <div class="flex items-start gap-4">

                                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-100 text-red-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        width="24"
                                                        height="24"
                                                        fill="currentColor"
                                                        viewBox="0 0 640 640">
                                                        <path d="M262.2 48C248.9 48 236.9 56.3 232.2 68.8L216 112L120 112C106.7 112 96 122.7 96 136C96 149.3 106.7 160 120 160L520 160C533.3 160 544 149.3 544 136C544 122.7 533.3 112 520 112L424 112L407.8 68.8C403.1 56.3 391.2 48 377.8 48L262.2 48z" />
                                                    </svg>
                                                </div>

                                                <div class="flex-1">
                                                    <h2 class="text-lg font-semibold text-slate-800">
                                                        Hapus Level
                                                    </h2>

                                                    <p class="mt-1 text-sm text-slate-500">
                                                        Apakah kamu yakin ingin menghapus level ini?
                                                        Tindakan ini tidak dapat dibatalkan.
                                                    </p>
                                                </div>

                                            </div>

                                            <div class="mt-6 flex justify-end gap-3">

                                                <button
                                                    @click="
                                                    hapusModal = false
                                                    hapusId = null"
                                                    class="rounded-xl border border-slate-200 px-4 py-2 text-slate-600 hover:bg-slate-50 transition">

                                                    Batal
                                                </button>

                                                <button
                                                    @click="hapusLevel(hapusId)"
                                                    class="rounded-xl bg-red-600 px-4 py-2 font-medium text-white hover:bg-red-700 transition">

                                                    Ya, Hapus
                                                </button>

                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div x-show="editLevel?.id === level.id" class="flex gap-2">
                                    <x-primary-button @click="updateLevel()">Simpan</x-primary-button>

                                    <x-secondary-button @click="editLevel = null">Batal</x-secondary-button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    function tabelLevel() {
        return {

            resetLevel: {
                status: @js($resetLevel?->status ?? 'nonaktif'),
                lama_hari: @js($resetLevel?->lama_hari ?? 1),
                jam_reset: @js($resetLevel?->jam_reset ?? '00:00'),
                last_reset_at: @js($resetLevel?->last_reset_at)
            },

            countdown: '',

            loadingReset: false,

            editReset: false,

            backupReset: null,

            sedangSinkron: false,

            countdownInterval: null,

            successMsg: null,

            resetError: null,

            hapusModal: false,

            hapusId: null,

            levels: @json($levels),

            editLevel: null,

            levelBaru: {
                nama_level: 0,
                min_poin: 0,
                delay_notifikasi: 0,
            },

            errorMsg: null,

            klikEdit(level) {
                this.editLevel = JSON.parse(JSON.stringify(level))
            },

            cancelEdit() {
                this.editLevel = null
            },

            init() {
                this.startCountdown()
            },

            mulaiEditReset() {

                this.backupReset = JSON.parse(
                    JSON.stringify(this.resetLevel)
                )

                this.editReset = true
            },

            batalEditReset() {

                this.resetLevel = JSON.parse(
                    JSON.stringify(this.backupReset)
                )

                this.editReset = false
            },

            async updateLevel() {

                let response = await fetch(`/pengaturan-level/${this.editLevel.id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify(this.editLevel)
                })

                let result = await response.json()

                if (!response.ok) {

                    window.dispatchEvent(new CustomEvent('toast', {
                        detail: {
                            type: 'error',
                            message: result.message
                        }
                    }))

                    return
                }

                let index = this.levels.findIndex(l => l.id === this.editLevel.id)

                this.levels[index] = result.data

                this.editLevel = null

                window.dispatchEvent(new CustomEvent('toast', {
                    detail: {
                        type: 'success',
                        message: 'Level berhasil diperbarui'
                    }
                }))
            },

            async hapusLevel(id) {

                let response = await fetch(`/pengaturan-level/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    }
                })

                let result = await response.json()

                if (!response.ok) {

                    window.dispatchEvent(new CustomEvent('toast', {
                        detail: {
                            type: 'error',
                            message: result.message
                        }
                    }))

                    return
                }

                this.levels = this.levels.filter(l => l.id !== id)

                this.hapusModal = false
                this.hapusId = null

                window.dispatchEvent(new CustomEvent('toast', {
                    detail: {
                        type: 'success',
                        message: 'Level berhasil dihapus'
                    }
                }))
            },

            async simpanResetLevel() {

                this.loadingReset = true
                this.successMsg = null
                this.resetError = null

                try {

                    let response = await fetch(`/pengaturan-level/update-reset`, {

                        method: 'POST',

                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                        },

                        body: JSON.stringify(this.resetLevel)
                    })

                    let result = await response.json()

                    if (!response.ok) {

                        this.resetError = result.message

                        return
                    }

                    // UPDATE STATE ALPINE
                    this.resetLevel = result.data

                    this.successMsg = result.message

                    this.editReset = false

                } catch (e) {

                    this.resetError = 'Terjadi kesalahan server'

                } finally {

                    this.loadingReset = false
                }
            },

            async startCountdown() {
                //Hindari interval dobel

                if (this.countdownInterval) {
                    clearInterval(this.countdownInterval)
                }

                this.countdownInterval = setInterval(async () => {

                    //Belum pernah reset

                    if (!this.resetLevel.last_reset_at) {

                        this.countdown = 'Belum ada reset';

                        return;
                    }

                    //Hitung reset berikutnya

                    let lastReset = new Date(this.resetLevel.last_reset_at);

                    let nextReset = new Date(lastReset);

                    nextReset.setDate(
                        nextReset.getDate() +
                        parseInt(this.resetLevel.lama_hari)
                    );

                    let jam = this.resetLevel.jam_reset.split(':');

                    nextReset.setHours(parseInt(jam[0]));
                    nextReset.setMinutes(parseInt(jam[1]));
                    nextReset.setSeconds(0);

                    let sekarang = new Date();

                    let diff = nextReset - sekarang;

                    //waktu reset

                    if (diff <= 0) {

                        this.countdown = 'Sedang sinkronisasi reset...';

                        if (!this.sedangSinkron) {

                            this.sedangSinkron = true;

                            try {

                                await this.ambilResetTerbaru();

                            } finally {

                                this.sedangSinkron = false;
                            }
                        }

                        return;
                    }

                    let hari = Math.floor(
                        diff / (1000 * 60 * 60 * 24)
                    );

                    let jamSisa = Math.floor(
                        (diff / (1000 * 60 * 60)) % 24
                    );

                    let menit = Math.floor(
                        (diff / (1000 * 60)) % 60
                    );

                    let detik = Math.floor(
                        (diff / 1000) % 60
                    );

                    this.countdown =
                        `${hari} hari ${jamSisa} jam ${menit} menit ${detik} detik`;

                }, 1000);
            },

            async ambilResetTerbaru() {

                try {

                    let response = await fetch(
                        '/pengaturan-level/reset-status'
                    );

                    let result = await response.json();

                    this.resetLevel = result.data;

                } catch (e) {

                    console.log(e);
                }
            },
        }
    }
</script>
@endsection