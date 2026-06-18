@extends('layouts.body', ['title' => 'Pengaturan Poin'])

@section('content')
<x-header
    :judul="'Pengaturan Poin'"
    :subjudul="'Kelola aspek penilaian dan bobotnya'" />

<div x-data="poinTable()">
    <div x-show="totalBobot < 1">
        <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200">
            <div class="flex items-center gap-3">
                <div class="text-red-600">
                    <svg xmlns="http://www.w3.org/2000/svg" height="25" width="25" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                        <path fill="currentColor" d="M320 576C178.6 576 64 461.4 64 320C64 178.6 178.6 64 320 64C461.4 64 576 178.6 576 320C576 461.4 461.4 576 320 576zM320 384C302.3 384 288 398.3 288 416C288 433.7 302.3 448 320 448C337.7 448 352 433.7 352 416C352 398.3 337.7 384 320 384zM320 192C301.8 192 287.3 207.5 288.6 225.7L296 329.7C296.9 342.3 307.4 352 319.9 352C332.5 352 342.9 342.3 343.8 329.7L351.2 225.7C352.5 207.5 338.1 192 319.8 192z" />
                    </svg>
                </div>
                <div>
                    <p class="font-semibold">
                        Total Bobot <span x-text="totalBobot.toFixed(2)"></span>
                    </p>
                    <p class="text-sm text-red-600">
                        Total bobot harus (1.00) untuk bisa melakukan penilaian.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div x-show="totalBobot == 1">
        <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200">
            <div class="flex items-center gap-3">
                <div class="text-green-600">
                    <svg xmlns="http://www.w3.org/2000/svg" height="25" width="25" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                        <path fill="currentColor" d="M320 576C178.6 576 64 461.4 64 320C64 178.6 178.6 64 320 64C461.4 64 576 178.6 576 320C576 461.4 461.4 576 320 576zM438 209.7C427.3 201.9 412.3 204.3 404.5 215L285.1 379.2L233 327.1C223.6 317.7 208.4 317.7 199.1 327.1C189.8 336.5 189.7 351.7 199.1 361L271.1 433C276.1 438 282.9 440.5 289.9 440C296.9 439.5 303.3 435.9 307.4 430.2L443.3 243.2C451.1 232.5 448.7 217.5 438 209.7z" />
                    </svg>
                </div>
                <div>
                    <p class="font-semibold">
                        Total Bobot <span x-text="totalBobot.toFixed(2)"></span>
                    </p>
                    <p class="text-sm text-green-600 ">Total bobot valid (1.00)</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-8">
        <h2 class="text-gray-900 mb-4 font-semibold">Tambah Aspek Baru</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-input namaLabel="Nama Aspek" type="text" slang="Contoh: ketepatan" x-model="aspekBaru.aspek"></x-input>

            <x-input namaLabel="Bobot" type="number" slang="0.00" x-model.number="aspekBaru.bobot" step="0.01" min="0" max="1"></x-input>

            <div class="flex items-end">
                <x-primary-button @click="tambahAspek" full>+ Tambah Aspek</x-primary-button>
            </div>
        </div>
        <div x-show="errorMsg"
            x-text="errorMsg"
            class="mt-3 text-sm text-red-600">
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-linear-to-r from-brand-500 to-brand-700 rounded-t-2xl">
            <h2 class="text-white text-lg font-semibold">Daftar Aspek Penilaian</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 text-brand-500 text-xs uppercase">
                    <tr class="border-b border-gray-200">
                        <th class="px-6 py-3 text-left">Aspek</th>
                        <th class="px-6 py-3 text-left">Bobot</th>
                        <th class="px-6 py-3 text-center">Status</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody
                    x-init="poins = @js($poins)"
                    class="divide-y divide-gray-200">

                    <template x-if="poins.length === 0">
                        <tr>
                            <td colspan="4">
                                <x-list-empty title="Tidak Ada Aspek Penilaian" subtitle="Tambahkan aspek pertama">
                                    <x-slot:icon>
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M9 8h6m2 12H7a2 2 0 01-2-2V6a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V18a2 2 0 01-2 2z" />
                                        </svg>
                                    </x-slot:icon>
                                </x-list-empty>
                            </td>
                        </tr>
                    </template>

                    <template x-for="poin in poins" :key="poin.id">

                        <tr class="hover:bg-gray-50">

                            <!-- ASPEK -->
                            <td class="px-6 py-4">

                                <span x-show="!editing || editing.id !== poin.id"
                                    x-text="poin.aspek"></span>

                                <template x-if="editing && editing.id === poin.id">
                                    <x-input type="text" x-model="editing.aspek"></x-input>
                                </template>
                            </td>

                            <!-- BOBOT -->

                            <td class="px-6 py-4">
                                <span x-show="!editing || editing.id !== poin.id"
                                    x-text="poin.bobot"></span>

                                <template x-if="editing && editing.id === poin.id">
                                    <x-input type="number" x-model.number="editing.bobot" step="0.01" min="0" max="1"></x-input>
                                </template>
                            </td>

                            <!-- STATUS -->
                            <td class="px-6 py-4 text-center">
                                <span
                                    x-show="!editing || editing.id !== poin.id"
                                    class="inline-flex px-3 py-1 rounded-full text-xs font-medium"
                                    :class="poin.status === 'aktif'
                                                ? 'bg-green-100 text-green-800 border-green-200'
                                                : 'bg-red-100 text-red-800 border-red-200'"
                                    x-text="poin.status.replaceAll('_', ' ').replace(/\b\w/g, char => char.toUpperCase())">
                                </span>


                                <template x-if="editing && editing.id === poin.id">
                                    <select
                                        x-model="editing.status"
                                        class="border border-gray-200 focus:ring focus:ring-brand-500 rounded px-2 py-1">
                                        <option value="aktif">Aktif</option>
                                        <option value="nonaktif">Nonaktif</option>
                                    </select>
                                </template>

                            </td>

                            <!-- ACTION -->
                            <td class="px-6 py-4 flex gap-3 justify-center">

                                <!-- MODE NORMAL -->
                                <div x-show="editing?.id !== poin.id" class="flex gap-3">

                                    <button
                                        @click="startEdit(poin)"
                                        class="text-blue-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="18" width="18" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                                            <path fill="currentColor" d="M505 122.9L517.1 135C526.5 144.4 526.5 159.6 517.1 168.9L488 198.1L441.9 152L471 122.9C480.4 113.5 495.6 113.5 504.9 122.9zM273.8 320.2L408 185.9L454.1 232L319.8 366.2C316.9 369.1 313.3 371.2 309.4 372.3L250.9 389L267.6 330.5C268.7 326.6 270.8 323 273.7 320.1zM437.1 89L239.8 286.2C231.1 294.9 224.8 305.6 221.5 317.3L192.9 417.3C190.5 425.7 192.8 434.7 199 440.9C205.2 447.1 214.2 449.4 222.6 447L322.6 418.4C334.4 415 345.1 408.7 353.7 400.1L551 202.9C579.1 174.8 579.1 129.2 551 101.1L538.9 89C510.8 60.9 465.2 60.9 437.1 89zM152 128C103.4 128 64 167.4 64 216L64 488C64 536.6 103.4 576 152 576L424 576C472.6 576 512 536.6 512 488L512 376C512 362.7 501.3 352 488 352C474.7 352 464 362.7 464 376L464 488C464 510.1 446.1 528 424 528L152 528C129.9 528 112 510.1 112 488L112 216C112 193.9 129.9 176 152 176L264 176C277.3 176 288 165.3 288 152C288 138.7 277.3 128 264 128L152 128z" />
                                        </svg>
                                    </button>

                                    <button
                                        @click="
                                            hapusModal = true
                                            hapusId = poin.id" class="text-red-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="18" width="18" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
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
                                                        Hapus Aspek Poin
                                                    </h2>

                                                    <p class="mt-1 text-sm text-slate-500">
                                                        Apakah kamu yakin ingin menghapus aspek poin ini?
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
                                                    @click="deletePoin(hapusId)"
                                                    class="rounded-xl bg-red-600 px-4 py-2 font-medium text-white hover:bg-red-700 transition">

                                                    Ya, Hapus
                                                </button>

                                            </div>

                                        </div>
                                    </div>

                                </div>

                                <!-- MODE EDIT -->
                                <div x-show="editing?.id === poin.id" class="flex gap-2">
                                    <x-primary-button @click="updatePoin()">Simpan</x-primary-button>

                                    <x-secondary-button @click="editing = null">Batal</x-secondary-button>
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
    function poinTable() {
        return {

            poins: @json($poins),

            editing: null,

            aspekBaru: {
                aspek: '',
                bobot: 0
            },

            errorMsg: null,

            hapusId: null,

            hapusModal: false,

            get totalBobot() {
                return this.poins
                    .filter(p => p.status === 'aktif')
                    .reduce((t, p) => t + parseFloat(p.bobot), 0)
            },

            init() {},

            startEdit(poin) {
                this.editing = JSON.parse(JSON.stringify(poin))
            },

            cancelEdit() {
                this.editing = null
            },

            async tambahAspek() {

                let response = await fetch('/pengaturan', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify(this.aspekBaru)
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

                this.poins = result.data_all

                this.aspekBaru.aspek = ''
                this.aspekBaru.bobot = ''

                window.dispatchEvent(new CustomEvent('toast', {
                    detail: {
                        type: 'success',
                        message: 'Aspek poin berhasil ditambahkan'
                    }
                }))
            },

            get totalBobotEdit() {

                if (!this.editing) return this.totalBobot

                let old = this.poins.find(p => p.id === this.editing.id)

                let totalTanpaLama = this.totalBobot - parseFloat(old.bobot)

                return totalTanpaLama + parseFloat(this.editing.bobot)
            },

            async updatePoin() {



                let response = await fetch(`/pengaturan/${this.editing.id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify(this.editing)
                })

                let result = await response.json()

                console.log(result)

                if (!response.ok) {

                    window.dispatchEvent(new CustomEvent('toast', {
                        detail: {
                            type: 'error',
                            message: result.message
                        }
                    }))

                    return
                }

                let index = this.poins.findIndex(p => p.id === this.editing.id)

                this.poins[index] = result.data

                this.editing = null

                window.dispatchEvent(new CustomEvent('toast', {
                    detail: {
                        type: 'success',
                        message: 'Poin berhasil diperbarui'
                    }
                }))

            },

            async deletePoin(id) {

                let response = await fetch(`/pengaturan/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    }
                })

                let result = await response.json()

                if (result.success) {

                    this.poins = this.poins.filter(p => p.id !== id)

                    this.hapusModal = false
                    this.hapusId = null

                    window.dispatchEvent(new CustomEvent('toast', {
                        detail: {
                            type: 'success',
                            message: 'Level berhasil dihapus'
                        }
                    }))

                }

            }

        }
    }
</script>
@endsection