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

                        <button
                            x-show="!editReset"
                            @click="mulaiEditReset"
                            class="bg-brand-500 text-white px-4 py-2 rounded-lg hover:bg-brand-700 transition-colors">

                            Edit Pengaturan

                        </button>
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
                    <h2 class="text-gray-900 mb-4">Tambah Threshold Level Baru</h2>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm text-gray-500 mb-2">Level</label>
                            <input
                                type="number"
                                min="1"
                                x-model="levelBaru.nama_level"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-500 mb-2">Minimum Poin</label>
                            <input
                                type="number"
                                min="0"
                                placeholder=""
                                x-model="levelBaru.min_poin"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-500 mb-2">Delay Notifikasi</label>
                            <input
                                type="number"
                                min="0"
                                x-model="levelBaru.delay_notifikasi"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500" />
                        </div>
                        <div class="flex items-end">
                            <button
                                @click="tambahLevel"
                                class="w-full bg-brand-500 text-white px-4 py-2 rounded-lg hover:bg-brand-700 transition-colors">
                                Tambah Level
                            </button>
                        </div>
                    </div>
                    <div x-show="errorMsg"
                        x-text="errorMsg"
                        class="mt-3 text-sm text-red-600">
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-gray-900 font-semibold">Daftar Threshold Level</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-gray-500">Level</th>
                                    <th class="px-6 py-3 text-left text-gray-500">Minimum Poin</th>
                                    <th class="px-6 py-3 text-left text-gray-500">Delay Notifikasi</th>
                                    <th class="px-6 py-3 text-left text-gray-500">Aksi</th>
                                </tr>
                            </thead>
                            <tbody x-init="levels = @js($levels)" class="divide-y divide-gray-200">
                                <template x-if="levels.length === 0">
                                    <tr>
                                        <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                            Belum ada threshold level. Tambahkan threshold pertama.
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
                                                    class="text-brand-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="18" width="18" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                                                        <path fill="currentColor" d="M505 122.9L517.1 135C526.5 144.4 526.5 159.6 517.1 168.9L488 198.1L441.9 152L471 122.9C480.4 113.5 495.6 113.5 504.9 122.9zM273.8 320.2L408 185.9L454.1 232L319.8 366.2C316.9 369.1 313.3 371.2 309.4 372.3L250.9 389L267.6 330.5C268.7 326.6 270.8 323 273.7 320.1zM437.1 89L239.8 286.2C231.1 294.9 224.8 305.6 221.5 317.3L192.9 417.3C190.5 425.7 192.8 434.7 199 440.9C205.2 447.1 214.2 449.4 222.6 447L322.6 418.4C334.4 415 345.1 408.7 353.7 400.1L551 202.9C579.1 174.8 579.1 129.2 551 101.1L538.9 89C510.8 60.9 465.2 60.9 437.1 89zM152 128C103.4 128 64 167.4 64 216L64 488C64 536.6 103.4 576 152 576L424 576C472.6 576 512 536.6 512 488L512 376C512 362.7 501.3 352 488 352C474.7 352 464 362.7 464 376L464 488C464 510.1 446.1 528 424 528L152 528C129.9 528 112 510.1 112 488L112 216C112 193.9 129.9 176 152 176L264 176C277.3 176 288 165.3 288 152C288 138.7 277.3 128 264 128L152 128z" />
                                                    </svg>
                                                </button>
                                                <button
                                                    @click="hapusLevel(level.id)" class="text-red-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="18" width="18" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                                                        <path fill="currentColor" d="M262.2 48C248.9 48 236.9 56.3 232.2 68.8L216 112L120 112C106.7 112 96 122.7 96 136C96 149.3 106.7 160 120 160L520 160C533.3 160 544 149.3 544 136C544 122.7 533.3 112 520 112L424 112L407.8 68.8C403.1 56.3 391.2 48 377.8 48L262.2 48zM128 208L128 512C128 547.3 156.7 576 192 576L448 576C483.3 576 512 547.3 512 512L512 208L464 208L464 512C464 520.8 456.8 528 448 528L192 528C183.2 528 176 520.8 176 512L176 208L128 208zM288 280C288 266.7 277.3 256 264 256C250.7 256 240 266.7 240 280L240 456C240 469.3 250.7 480 264 480C277.3 480 288 469.3 288 456L288 280zM400 280C400 266.7 389.3 256 376 256C362.7 256 352 266.7 352 280L352 456C352 469.3 362.7 480 376 480C389.3 480 400 469.3 400 456L400 280z" />
                                                    </svg>
                                                </button>
                                            </div>

                                            <div x-show="editLevel?.id === level.id" class="flex gap-2">

                                                <button
                                                    @click="updateLevel()"
                                                    class="text-green-600">
                                                    Save
                                                </button>

                                                <button
                                                    @click="editLevel = null"
                                                    class="text-gray-600">
                                                    Cancel
                                                </button>

                                            </div>
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

                async tambahLevel() {
                    this.errorMsg = null

                    if (this.levels.some(l => l.nama_level == this.levelBaru.nama_level)) {
                        this.errorMsg = "Level " + this.levelBaru.nama_level + " sudah ada"

                        return
                    }

                    let maxLevel = Math.max(...this.levels.map(l => l.nama_level), 0)

                    if (this.levelBaru.nama_level != maxLevel + 1) {
                        this.errorMsg = "Level harus berurutan. Level berikutnya adalah " + (maxLevel + 1)

                        return
                    }

                    let lastLevel = this.levels.find(l => l.nama_level === maxLevel)

                    if (lastLevel && this.levelBaru.min_poin <= lastLevel.min_poin) {
                        this.errorMsg = "Minimum poin harus lebih besar dari level sebelumnya"

                        return
                    }

                    let response = await fetch('/pengaturan-level', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                        },
                        body: JSON.stringify(this.levelBaru)
                    })

                    let result = await response.json()

                    if (!response.ok) {
                        this.errorMsg = result.message
                        return
                    }

                    this.levels.push(result.data)

                    this.levelBaru.nama_level = 0
                    this.levelBaru.min_poin = 0
                    this.levelBaru.delay_notifikasi = 0
                },

                async updateLevel() {
                    this.errorMsg = null

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

                        alert(result.message)

                        return
                    }

                    let index = this.levels.findIndex(l => l.id === this.editLevel.id)

                    this.levels[index] = result.data

                    this.editLevel = null
                },

                async hapusLevel(id) {

                    if (!confirm('Yakin ingin menghapus?')) return

                    let response = await fetch(`/pengaturan-level/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                        }
                    })

                    let result = await response.json()

                    if (!response.ok) {

                        alert(result.message)

                        return
                    }

                    this.levels = this.levels.filter(l => l.id !== id)
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