@extends('layouts.body', ['title' => 'Penilaian'])

@section('content')

<x-header
    :judul="'Penilaian Proyek'"
    :subjudul="'Evaluasi hasil pekerjaan freelancer dengan lebih cepat dan nyaman'" />

<div
    x-data="penilaian"
    class="space-y-6">

    <!-- total bobot tidak valid -->
    @if (!$isBobotValid)
    <div class="mb-6 p-5 rounded-3xl bg-red-50 border border-red-200 shadow-xs">
        <div class="gap-4">
            <div class="flex shrink-0 items-center justify-start text-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" height="30" width="30" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                    <path fill="currentColor" d="M320 576C178.6 576 64 461.4 64 320C64 178.6 178.6 64 320 64C461.4 64 576 178.6 576 320C576 461.4 461.4 576 320 576zM320 384C302.3 384 288 398.3 288 416C288 433.7 302.3 448 320 448C337.7 448 352 433.7 352 416C352 398.3 337.7 384 320 384zM320 192C301.8 192 287.3 207.5 288.6 225.7L296 329.7C296.9 342.3 307.4 352 319.9 352C332.5 352 342.9 342.3 343.8 329.7L351.2 225.7C352.5 207.5 338.1 192 319.8 192z" />
                </svg>
                <div class="ml-2">
                    <h3 class="text-lg font-semibold text-red-950">Aksi Diperlukan: Penyesuaian Bobot Poin</h3>
                    <p class="text-sm text-red-700">
                        Total bobot aktif saat ini adalah <strong class="underline">{{ number_format($totalBobot, 2) }}</strong>. Total bobot aspek aktif harus tepat <strong>1.00</strong> agar penilaian dapat diproses.
                    </p>
                </div>
            </div>
            <div class="mt-4 flex gap-3">
                <a href="{{ route('poin.view') }}" class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-xs hover:bg-red-700 transition">
                    Sesuaikan Bobot di Pengaturan Poin
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- statcard -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

        <x-stat-card
            title="Total Proyek"
            :value="count($jumlahPerProyek)"
            color="blue">
        </x-stat-card>

        <x-stat-card
            title="Menunggu Penilaian"
            :value="collect($jumlahPerProyek)->sum('total')"
            color="amber">
        </x-stat-card>

        <div class="relative overflow-hidden bg-linear-to-r from-brand-500 to-brand-700 rounded-3xl p-6 border-2 border-brand-500 hover:border-2 transition hover:-translate-y-1 hover:shadow-2xl">
            <p class="text-white font-medium text-sm mb-2">
                Status
            </p>

            <p class="text-3xl text-white font-semibold">
                Penilaian Aktif
            </p>
        </div>

    </div>

    {{-- PROJECT LIST --}}
    <template x-if="!selectedProject">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

            @forelse ($jumlahPerProyek as $item)

            <button
                @click="loadDetail({{ $item->id }})"
                class="group bg-white border border-gray-200 rounded-3xl p-6 text-left hover:-translate-y-1 hover:shadow-xl transition duration-300">

                <div class="flex items-start justify-between gap-4">

                    <div class="flex-1">

                        <div class="flex items-center gap-3 mb-4">

                            <div class="w-12 h-12 rounded-2xl bg-brand-100 flex items-center justify-center">
                                <svg class="w-6 h-6 text-brand-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>

                            <div>
                                <h2 class="text-lg font-semibold text-gray-900 group-hover:text-brand-600 transition">
                                    {{ $item->nama_proyek }}
                                </h2>

                                <p class="text-sm text-gray-500">
                                    Proyek siap dinilai
                                </p>
                            </div>

                        </div>

                        <div class="flex items-center justify-between">

                            <div>
                                <p class="text-sm text-gray-500">
                                    Submission Pending
                                </p>

                                <h3 class="text-2xl font-bold text-amber-500 mt-1">
                                    {{ $item->total }}
                                </h3>
                            </div>

                            <div class="rounded-2xl font-semibold px-5 py-3 border border-brand-500 text-brand-500 group-hover:text-white transition group-hover:bg-linear-to-r group-hover:from-brand-500 group-hover:to-brand-700">
                                Lihat Detail
                            </div>

                        </div>

                    </div>

                </div>

            </button>

            @empty

            <x-list-empty title="Tidak Ada Penilaian" subtitle="Semua proyek telah selesai dinilai">
                <x-slot:icon>
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M9 8h6m2 12H7a2 2 0 01-2-2V6a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V18a2 2 0 01-2 2z" />
                    </svg>
                </x-slot:icon>
            </x-list-empty>

            @endforelse

        </div>

    </template>

    {{-- DETAIL --}}
    <template x-if="selectedProject">

        <div class="space-y-5">

            {{-- TOPBAR --}}
            <div class="bg-white border border-gray-200 rounded-3xl p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shadow-sm">

                <div>
                    <h2
                        class="text-2xl font-bold text-gray-900"
                        x-text="selectedProject.nama_proyek">
                    </h2>

                    <p class="text-gray-500 mt-1">
                        Review hasil freelancer dan lakukan penilaian
                    </p>
                </div>

                <x-secondary-button @click="selectedProject = null">Kembali</x-secondary-button>

            </div>

            {{-- SUB PROYEK --}}
            <template x-for="sub in selectedProject.subproyeks" :key="sub.id">

                <div class="bg-white border border-gray-200 rounded-3xl overflow-hidden shadow-sm">

                    <button
                        @click="toggleSub(sub.id)"
                        class="w-full px-5 py-5 flex items-center justify-between hover:bg-gray-50 transition">

                        <div class="text-left">
                            <h3
                                class="font-semibold text-gray-900"
                                x-text="sub.nama_sub_proyek">
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                Klik untuk melihat submission
                            </p>
                        </div>

                        <div
                            class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center">
                            <span x-text="isOpen(sub.id) ? '−' : '+'"></span>
                        </div>

                    </button>

                    <div
                        x-show="isOpen(sub.id)"
                        class="border-t border-gray-100 p-5 space-y-4">

                        <template x-for="subsub in sub.subsubproyeks">

                            <template x-for="ambil in subsub.pengambilans">

                                <div
                                    x-show="ambil.status === 'menunggu'"
                                    class="border border-gray-200 rounded-3xl p-5 bg-gray-50/70">

                                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

                                        <div class="space-y-4">

                                            <div>
                                                <p class="text-sm text-gray-500">
                                                    Freelancer
                                                </p>

                                                <h3
                                                    class="text-lg font-semibold text-gray-900"
                                                    x-text="ambil.user.name">
                                                </h3>
                                            </div>

                                            <div class="flex flex-wrap gap-3">

                                                <div class="px-4 py-2 bg-white rounded-2xl border border-gray-200 text-sm">
                                                    Halaman:
                                                    <span class="font-semibold">
                                                        <span x-text="ambil.dari_halaman"></span>
                                                        -
                                                        <span x-text="ambil.sampai_halaman"></span>
                                                    </span>
                                                </div>

                                                <div class="px-4 py-2 bg-white rounded-2xl border border-gray-200 text-sm">
                                                    Total:
                                                    <span
                                                        class="font-semibold"
                                                        x-text="ambil.total_halaman">
                                                    </span>
                                                    halaman
                                                </div>

                                            </div>

                                        </div>

                                        <div class="flex flex-col sm:flex-row gap-3">

                                            <a :href="`{{ url('penilaian') }}/${ambil.id}/download/hasil`"
                                                target="_blank"
                                                class="px-5 py-3 rounded-2xl border border-gray-300 bg-white hover:bg-gray-100 transition text-center">
                                                Download File
                                            </a>

                                            <button
                                                @if (!$isBobotValid)
                                                disabled
                                                class="px-5 py-3 rounded-2xl bg-gray-300 text-gray-500 cursor-not-allowed opacity-75"
                                                @else
                                                @click="openModal(ambil, subsub)"
                                                class="px-5 py-3 rounded-2xl bg-brand-500 hover:bg-brand-700 text-white transition"
                                                @endif>
                                                Beri Penilaian
                                            </button>

                                        </div>

                                    </div>

                                </div>

                            </template>

                        </template>

                    </div>

                </div>

            </template>

        </div>

    </template>

    {{-- MODAL PENILAIAN --}}
    <div
        x-show="showModal"
        x-transition.opacity
        class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-end sm:items-center justify-center p-0 sm:p-6">

        <div
            @click.outside="showModal = false"
            x-transition
            class="bg-white w-full sm:max-w-4xl rounded-t-[32px] sm:rounded-[32px] max-h-[95vh] overflow-y-auto">

            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-5 flex items-center justify-between z-10">

                <div>
                    <h2 class="text-xl font-bold text-gray-900">
                        Penilaian Freelancer
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Berikan evaluasi secara objektif
                    </p>
                </div>

                <button
                    @click="showModal = false"
                    class="w-10 h-10 rounded-xl hover:bg-gray-100">
                    ✕
                </button>

            </div>

            <div class="p-6">

                <template x-if="modalData">

                    <div
                        x-data="scoreComponent(
                            modalData.ambil.total_halaman,
                            modalData.subsub.harga_perlembar,
                            modalData.subsub.kualitas,
                            {{ $poins->count() }}
                        )">

                        <form
                            action="{{ route('penilaian.tambah') }}"
                            method="POST"
                            class="space-y-6">

                            @csrf

                            <input
                                type="hidden"
                                name="pengambilan_id"
                                :value="modalData.ambil.id">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                @foreach ($poins as $poin)

                                <div class="bg-gray-50 rounded-3xl p-5 border border-gray-200">

                                    <div class="flex items-center justify-between mb-4">

                                        <label class="font-semibold text-gray-800">
                                            {{ Str::headline($poin->aspek) }}
                                        </label>

                                        <span class="text-sm text-gray-500">
                                            1 - 10
                                        </span>

                                    </div>

                                    <input
                                        type="number"
                                        min="1"
                                        max="10"
                                        x-model.number="skor['{{ $poin->slug }}']"
                                        :class="inputColor('{{ $poin->slug }}')"
                                        name="skor[{{ $poin->slug }}]"
                                        class="w-full rounded-2xl border px-4 py-3 focus:ring-2 focus:ring-brand-500 outline-none transition" />

                                    <textarea
                                        x-show="skor['{{ $poin->slug }}'] < 8"
                                        x-model="notes['{{ $poin->slug }}']"
                                        name="catatan[{{ $poin->slug }}]"
                                        placeholder="Berikan catatan evaluasi..."
                                        class="w-full mt-3 rounded-2xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-brand-500 outline-none"></textarea>

                                </div>

                                @endforeach

                            </div>

                            {{-- RESULT --}}
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                                <div class="bg-brand-50 rounded-3xl p-5 border border-brand-100">
                                    <p class="text-sm text-brand-700">
                                        Total Score
                                    </p>

                                    <h2
                                        class="text-3xl font-bold text-brand-600 mt-2"
                                        x-text="estimasiScore">
                                    </h2>
                                </div>

                                <div class="bg-green-50 rounded-3xl p-5 border border-green-100">
                                    <p class="text-sm text-green-700">
                                        Pembayaran
                                    </p>

                                    <h2 class="text-3xl font-bold text-green-600 mt-2">
                                        Rp <span x-text="paymentFormatted"></span>
                                    </h2>

                                    <input
                                        type="hidden"
                                        name="total_pembayaran"
                                        :value="payment">
                                </div>

                                <div class="bg-amber-50 rounded-3xl p-5 border border-amber-100">
                                    <p class="text-sm text-amber-700">
                                        Estimasi Poin
                                    </p>

                                    <h2
                                        class="text-3xl font-bold text-amber-600 mt-2"
                                        x-text="estimasiPoin">
                                    </h2>

                                    <input
                                        type="hidden"
                                        name="total_poin"
                                        :value="estimasiPoin">
                                </div>

                            </div>

                            <input
                                type="hidden"
                                name="total_skor"
                                :value="totalSkor">

                            <button
                                type="submit"
                                @if (!$isBobotValid)
                                disabled
                                class="w-full py-4 rounded-2xl bg-gray-400 cursor-not-allowed text-white font-semibold"
                                @else
                                :disabled="isInvalid"
                                :class="isInvalid
                                        ? 'bg-gray-400 cursor-not-allowed'
                                        : 'bg-brand-500 hover:bg-brand-700'"
                                @endif
                                class="w-full py-4 rounded-2xl text-white font-semibold transition">
                                @if (!$isBobotValid)
                                Penilaian Dinonaktifkan (Sesuaikan Bobot Terlebih Dahulu)
                                @else
                                Simpan Penilaian & Kirim Upah
                                @endif
                            </button>

                        </form>

                    </div>

                </template>

            </div>

        </div>

    </div>

</div>

<script>
    document.addEventListener('alpine:init', () => {

        Alpine.data('penilaian', () => ({

            selectedProject: null,
            loading: false,
            openSub: [],

            showModal: false,
            modalData: null,

            async loadDetail(id) {

                this.loading = true

                const response = await fetch(`/penilaian/${id}`)
                const data = await response.json()

                this.selectedProject = data
                this.loading = false
            },

            toggleSub(id) {

                this.openSub.includes(id) ?
                    this.openSub = this.openSub.filter(i => i !== id) :
                    this.openSub.push(id)
            },

            isOpen(id) {
                return this.openSub.includes(id)
            },

            openModal(ambil, subsub) {

                this.modalData = {
                    ambil,
                    subsub
                }

                this.showModal = true
            }

        }))

    })

    function scoreComponent(total_halaman, harga_perlembar, kualitas, jumlahScore) {

        return {

            maxScore: jumlahScore * 10,

            skor: @json($poins->mapWithKeys(fn($p)=>[$p->slug=>5])),

            notes: @json($poins->mapWithKeys(fn($p)=>[$p->slug=>''])),

            bobot: @json($poins->pluck('bobot', 'slug')),

            inputColor(slug) {

                let nilai = this.skor[slug] || 0

                if (nilai >= 8) {
                    return 'border-green-500 bg-green-50'
                }

                if (nilai >= 6) {
                    return 'border-yellow-500 bg-yellow-50'
                }

                return 'border-red-500 bg-red-50'
            },

            get isInvalid() {

                for (let key in this.skor) {

                    if (this.skor[key] < 8 && !this.notes[key]) {
                        return true
                    }
                }

                return false
            },

            get totalSkor() {

                let total = 0

                for (let key in this.skor) {
                    total += this.skor[key]
                }

                return total
            },

            get weightedScore() {

                let total = 0

                for (let key in this.skor) {

                    let nilai = this.skor[key] || 0
                    let bobot = this.bobot[key] || 0

                    total += nilai * bobot
                }

                return total
            },

            get payment() {
                return (total_halaman * harga_perlembar) * (this.weightedScore / 10)
            },

            get paymentFormatted() {
                return this.payment.toLocaleString('id-ID')
            },

            get estimasiScore() {
                return this.totalSkor + '/' + this.maxScore
            },

            get estimasiPoin() {
                return Math.round(total_halaman * (kualitas / 10) * (this.weightedScore / 10) * 2)
            }

        }
    }
</script>

@endsection