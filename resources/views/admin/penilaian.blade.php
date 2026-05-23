@extends('layouts.body', ['title' => 'Penilaian'])

@section('content')
<x-header
    :judul="'Penilaian Proyek'"
    :subjudul="'Evaluasi hasil pekerjaan freelancer'" />
<div x-data="penilaian">
    {{-- LIST PROYEK --}}
    <template x-if="!selectedProject">
        <div class="bg-white rounded-xl border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-gray-900">Pilih Proyek untuk Dinilai</h2>
            </div>
            <div>
                @forelse ($jumlahPerProyek as $item)
                <button @click="loadDetail({{ $item->id }})"
                    class="w-full px-6 py-4 text-left hover:bg-gray-50 transition-colors flex items-center justify-between">
                    <div>
                        <h3 class="text-gray-900 mb-1">{{ $item->nama_proyek }}</h3>
                        <p class="text-gray-500 text-sm">
                            {{ $item->total }} submission menunggu penilaian
                        </p>
                    </div>
                    <p class="text-sm text-gray-500">Klik untuk Detail</p>
                </button>
                @empty
                <div class="divide-y divide-gray-200">
                    <div class="px-6 py-8 text-center text-gray-500">
                        Belum ada proyek yang perlu dinilai
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </template>
    {{-- DETAIL --}}
    <template x-if="selectedProject">
        <div class="space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 p-6 flex justify-between items-center">
                <div>
                    <h2 class="text-gray-900 text-xl mb-2"
                        x-text="selectedProject.nama_proyek">
                    </h2>
                    <p class="text-gray-500">
                        Detail penilaian per freelancer
                    </p>
                </div>
                <button
                    @click="selectedProject = null"
                    class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Kembali
                </button>
            </div>

            <template x-for="sub in selectedProject.subproyeks" :key="sub.id">
                <div class="bg-white border rounded-xl mb-4">
                    <button
                        @click="toggleSub(sub.id)"
                        class="w-full p-4 flex justify-between">

                        <span x-text="sub.nama_sub_proyek"></span>
                        <span x-text="isOpen(sub.id) ? '▲' : '▼'"></span>
                    </button>

                    <div x-show="isOpen(sub.id)" class="p-4">

                        <template x-for="subsub in sub.subsubproyeks" :key="subsub.id">
                            <div class="mb-4">

                                <template x-for="ambil in subsub.pengambilans" :key="ambil.id">
                                    <div
                                        x-show="ambil.status === 'menunggu'"
                                        class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                            <div>
                                                <p class="text-gray-500 text-sm mb-1">Freelancer</p>
                                                <p class="text-gray-900" x-text="ambil.user.name"></p>
                                            </div>
                                            <div>
                                                <p class="text-gray-500 text-sm mb-1">Rentang Halaman</p>
                                                <p class="text-gray-900">
                                                    <span x-text="ambil.dari_halaman"></span>
                                                    -
                                                    <span x-text="ambil.sampai_halaman"></span>
                                                    (<span x-text="ambil.total_halaman"></span> Halaman)
                                                </p>
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <p class="text-xs text-gray-500 mb-2">File Hasil Tugas</p>
                                            <a :href="`{{ url('penilaian') }}/${ambil.id}/download/hasil`"
                                                target="_blank"
                                                class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 hover:bg-gray-200 rounded-lg text-sm transition">
                                                Download File
                                            </a>
                                        </div>
                                        <div x-data="scoreComponent(ambil.total_halaman, subsub.harga_perlembar, subsub.kualitas, {{ $poins->count() }})">
                                            <form action="{{ route('penilaian.tambah') }}" method="post">
                                                @csrf
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                                    @foreach ($poins as $poin)
                                                    <div>
                                                        <input type="hidden" name="pengambilan_id" :value="ambil.id">
                                                        <label class="block text-gray-500 text-sm mb-2">{{ Str::headline($poin->aspek) }} (1-10)</label>
                                                        <input type="number" min="1" max="10"
                                                            x-model.number="skor['{{ $poin->slug }}']"
                                                            :class="inputColor('{{ $poin->slug }}')"
                                                            name="skor[{{ $poin->slug }}]" required
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent" />
                                                        <textarea x-model="notes['{{ $poin->slug }}']" name="catatan[{{ $poin->slug }}]" x-show="skor['{{ $poin->slug }}'] < 8" id="" placeholder="Catatan untuk aspek {{ $poin->aspek }}..."
                                                            class="w-full mt-2 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent"></textarea>
                                                        <p
                                                            x-show="skor['{{ $poin->slug }}'] < 8 && !notes['{{ $poin->slug }}']" class="text-red-500 text-xs mt-1">
                                                            Catatan wajib diisi jika nilai di bawah 8
                                                        </p>
                                                    </div>
                                                    @endforeach
                                                </div>

                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                                    <div class="p-4 bg-white rounded-lg">
                                                        <p class="text-gray-500 text-sm">Total Score</p>
                                                        <p class="text-2xl text-brand-500" x-text="estimasiScore"></p>
                                                        <input type="hidden" name="total_skor" :value="totalSkor">
                                                    </div>
                                                    <div class="p-4 bg-white rounded-lg">
                                                        <p class="text-gray-500 text-sm">Pembayaran</p>
                                                        <p class="text-2xl text-gray-900">
                                                            Rp <span x-text="paymentFormatted"></span>
                                                            <input type="hidden" name="total_pembayaran" :value="payment">
                                                        </p>
                                                    </div>
                                                    <div class="p-4 bg-white rounded-lg">
                                                        <p class="text-gray-500 text-sm">Estimasi Poin</p>
                                                        <p x-text="estimasiPoin"></p>
                                                        <input type="hidden" name="total_poin" :value="estimasiPoin">
                                                    </div>
                                                </div>

                                                <button
                                                    type="submit"
                                                    :disabled="isInvalid"
                                                    :class="isInvalid ? 'bg-gray-400 cursor-not-allowed' : 'bg-brand-500 hover:bg-brand-700'"
                                                    class="w-full text-white py-3 rounded-lg">
                                                    Simpan Penilaian & Kirim Upah
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </template>

                            </div>
                        </template>

                    </div>
                </div>
            </template>
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
            }
        }))
    })

    function scoreComponent(total_halaman, harga_perlembar, kualitas, jumlahScore) {
        return {

            maxScore: jumlahScore * 10,

            skor: @json($poins->mapWithKeys(fn($p) => [$p->slug => 5])),

            notes: @json($poins->mapWithKeys(fn($p) => [$p->slug => ''])),

            bobot: @json($poins->pluck('bobot', 'slug')),

            inputColor(slug) {

                let nilai = this.skor[slug] || 0

                if (nilai >= 8) {
                    return 'border-green-500 bg-green-50 focus:ring-green-500'
                }

                if (nilai >= 6) {
                    return 'border-yellow-500 bg-yellow-50 focus:ring-yellow-500'
                }

                return 'border-red-500 bg-red-50 focus:ring-red-500'
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
                return Math.round(
                    total_halaman * (kualitas / 10) * (this.weightedScore / 10) * 2
                )
            }
        }
    }
</script>
@endsection