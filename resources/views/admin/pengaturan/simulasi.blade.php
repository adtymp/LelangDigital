@extends('layouts.body', ['title' => 'Simulasi Poin'])

@section('content')
<x-header
    :judul="'Simulasi Poin'"
    :subjudul="'Simulasi hasil perhitungan poin'" />

            <div x-data="simulasiPoin()">

                <!-- TAB BUTTON -->
                <div class="rounded-t-xl bg-white border border-gray-200 flex items-center max-w-fit">
                    <div class="flex">

                        <button
                            @click="pilihRumus = 'penilaian'"
                            :class="pilihRumus === 'penilaian' ? 'bg-brand-500 text-white rounded-tl-xl' : 'text-gray-500'"
                            class="px-4 py-2 hover:bg-brand-500 hover:text-white hover:rounded-tl-xl">
                            Penilaian
                        </button>

                        <button
                            @click="pilihRumus = 'upah'"
                            :class="pilihRumus === 'upah' ? 'bg-brand-500 text-white' : 'text-gray-500'"
                            class="px-4 py-2 hover:bg-brand-500 hover:text-white">
                            Upah
                        </button>

                        <button
                            @click="pilihRumus = 'penalti'"
                            :class="pilihRumus === 'penalti' ? 'bg-brand-500 text-white rounded-tr-xl' : 'text-gray-500'"
                            class="px-4 py-2 hover:bg-brand-500 hover:text-white hover:rounded-tr-xl">
                            Penalti
                        </button>

                    </div>
                </div>

                <!-- CONTENT -->
                <div class="bg-white rounded-b-xl border border-gray-200 p-6 mb-4">

                    <!-- ================= PENILAIAN ================= -->
                    <div x-show="pilihRumus === 'penilaian'" x-transition>

                        <h2 class="font-semibold text-lg text-brand-500 mb-4">
                            Rumus Poin Penilaian
                        </h2>

                        <div class="grid md:grid-cols-2 gap-6">

                            <!-- INPUT -->
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 space-y-4">

                                <!-- KUALITAS -->
                                <div>
                                    <label class="text-sm text-gray-600">Kualitas</label>
                                    <select x-model.number="kualitas"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500">
                                        <option value="">Pilih</option>
                                        <option value="2">Sangat Kurang (2)</option>
                                        <option value="4">Kurang (4)</option>
                                        <option value="6">Sedang (6)</option>
                                        <option value="8">Baik (8)</option>
                                        <option value="10">Sangat Baik (10)</option>
                                    </select>
                                </div>

                                <!-- HALAMAN -->
                                <div>
                                    <label class="text-sm text-gray-600">Total Halaman</label>
                                    <input type="number" x-model.number="halaman"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500">
                                </div>

                                <!-- SKOR PER ASPEK -->
                                @foreach ($poins as $poin)
                                <div>
                                    <label class="text-sm text-gray-600">
                                        {{ Str::headline($poin->aspek) }} (1-10)
                                    </label>
                                    <input type="number" min="1" max="10"
                                        x-model.number="skor['{{ $poin->slug }}']"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500">
                                </div>
                                @endforeach

                            </div>

                            <!-- HASIL -->
                            <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-4">

                                <h3 class="font-semibold text-gray-700">Hasil Perhitungan</h3>

                                <div class="text-sm text-gray-600 space-y-2">
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-sm text-gray-600">
                                        <p class="text-gray-600"> Faktor Kualitas</p>
                                        <p>= Kualitas / 10</p>
                                        <p>= <span class="font-medium" x-text="kualitas"></span> / 10</p>
                                        <p class="text-brand-500">= <b x-text="faktorKualitas.toFixed(2)"></b></p>
                                    </div>
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-sm text-gray-600">
                                        <p class="text-gray-600"> Faktor Halaman</p>
                                        <p>= (Halaman Akhir - Halaman Awal + 1)</p>
                                        <p class="text-brand-500">= <b x-text="halaman"></b></p>
                                    </div>
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-sm text-gray-600">
                                        <p class="text-gray-600">
                                            Faktor Penilaian
                                        </p>
                                        <p>= (
                                            @foreach ($poins as $poin)
                                            ({{ $poin->aspek }} × {{ number_format($poin->bobot, 2) }})
                                            @if (!$loop->last) + @endif
                                            @endforeach
                                            ) / 10
                                        </p>
                                        <p>= <span class="font-medium">
                                                (
                                                <template x-for="(item, index) in detailPenilaian.items" :key="item.aspek">
                                                    <span>
                                                        (<span x-text="item.nilai"></span> ×
                                                        <span x-text="item.bobot.toFixed(2)"></span>)
                                                        <span x-show="index < detailPenilaian.items.length - 1"> + </span>
                                                    </span>
                                                </template>
                                                ) / 10
                                            </span>
                                        </p>
                                        <p>= <span class="font-medium">
                                                (
                                                <template x-for="(item, index) in detailPenilaian.items" :key="item.aspek">
                                                    <span>
                                                        <span x-text="item.hasil.toFixed(2)"></span>
                                                        <span x-show="index < detailPenilaian.items.length - 1"> + </span>
                                                    </span>
                                                </template>
                                                ) / 10
                                            </span>
                                        </p>

                                        <!-- TOTAL -->
                                        <p class="text-gray-600 mt-1">
                                            = <span class="font-medium" x-text="detailPenilaian.total.toFixed(2)"></span> / 10
                                        </p>
                                        <p>= <b class="text-brand-500" x-text="detailPenilaian.final.toFixed(2)"></b></p>
                                    </div>

                                </div>
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-sm text-gray-600">
                                    <p class="text-gray-600">Poin</p>
                                    <p>= F. Penilaian x F. Halaman x F. Kualitas x 2</p>
                                    <p class="font-medium">= <span x-text="detailPenilaian.final.toFixed(2)"></span> x <span x-text="halaman"></span> x <span x-text="faktorKualitas.toFixed(2)"></span> x 2</p>
                                    <p class="text-brand-500">= <b x-text="totalPoin"></b></p>
                                </div>

                                <div class="border-t border-gray-200 pt-3">
                                    <p class="text-green-600 font-bold text-lg">
                                        Total Poin:
                                        <span x-text="totalPoin"></span>
                                    </p>
                                </div>

                            </div>
                        </div>

                        <!-- RUMUS -->


                    </div>

                    <!-- ================= UPAH ================= -->
                    <div x-show="pilihRumus === 'upah'" x-transition>

                        <h2 class="font-semibold text-lg text-brand-500 mb-4">
                            Rumus Upah
                        </h2>

                        <div class="grid md:grid-cols-2 gap-6">

                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 space-y-3">

                                @foreach ($poins as $poin)
                                <div>
                                    <label class="text-sm text-gray-600">
                                        {{ Str::headline($poin->aspek) }} (1-10)
                                    </label>
                                    <input type="number" min="1" max="10"
                                        x-model.number="skor['{{ $poin->slug }}']"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500">
                                </div>
                                @endforeach

                                <div>
                                    <label class="text-sm text-gray-600">Estimasi Pendapatan</label>
                                    <input type="number" x-model.number="pendapatan"
                                        placeholder="Pendapatan"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500">
                                </div>
                            </div>

                            <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-4">

                                <h3 class="font-semibold text-gray-700">Hasil Perhitungan</h3>

                                <div class="text-sm text-gray-600 space-y-2">
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-sm text-gray-600">
                                        <p class="text-gray-600">
                                            Faktor Penilaian
                                        </p>
                                        <p>= (
                                            @foreach ($poins as $poin)
                                            ({{ $poin->aspek }} × {{ number_format($poin->bobot, 2) }})
                                            @if (!$loop->last) + @endif
                                            @endforeach
                                            ) / 10
                                        </p>
                                        <p>= <span class="font-medium">
                                                (
                                                <template x-for="(item, index) in detailPenilaian.items" :key="item.aspek">
                                                    <span>
                                                        (<span x-text="item.nilai"></span> ×
                                                        <span x-text="item.bobot.toFixed(2)"></span>)
                                                        <span x-show="index < detailPenilaian.items.length - 1"> + </span>
                                                    </span>
                                                </template>
                                                ) / 10
                                            </span>
                                        </p>
                                        <p>= <span class="font-medium">
                                                (
                                                <template x-for="(item, index) in detailPenilaian.items" :key="item.aspek">
                                                    <span>
                                                        <span x-text="item.hasil.toFixed(2)"></span>
                                                        <span x-show="index < detailPenilaian.items.length - 1"> + </span>
                                                    </span>
                                                </template>
                                                ) / 10
                                            </span>
                                        </p>

                                        <!-- TOTAL -->
                                        <p class="text-gray-600 mt-1">
                                            = <span class="font-medium" x-text="detailPenilaian.total.toFixed(2)"></span> / 10
                                        </p>
                                        <p>= <b class="text-brand-500" x-text="detailPenilaian.final.toFixed(2)"></b></p>
                                    </div>

                                </div>
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-sm text-gray-600">
                                    <p class="text-gray-600">Upah</p>
                                    <p>= F. Penilaian x Estimasi Pendapatan</p>
                                    <p class="font-medium">= <span x-text="detailPenilaian.final.toFixed(2)"></span> x <span x-text="formatRupiah(pendapatan)"></span></p>
                                    <p class="text-brand-500">= <b x-text="formatRupiah(upah)"></b></p>
                                </div>

                                <div class="border-t border-gray-200 pt-3">
                                    <p class="text-green-600 font-bold text-lg">
                                        Upah:
                                        <span x-text="formatRupiah(upah)"></span>
                                    </p>
                                </div>

                            </div>
                        </div>


                    </div>

                    <!-- ================= PENALTI ================= -->
                    <div x-show="pilihRumus === 'penalti'" x-transition>

                        <h2 class="font-semibold text-lg text-brand-500 mb-4">
                            Rumus Penalti
                        </h2>

                        <div class="grid md:grid-cols-2 gap-6">

                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 space-y-4">
                                <div>
                                    <label class="text-sm text-gray-600">Kualitas</label>
                                    <select x-model.number="kualitas"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500">
                                        <option value="">Pilih</option>
                                        <option value="2">Sangat Kurang (2)</option>
                                        <option value="4">Kurang (4)</option>
                                        <option value="6">Sedang (6)</option>
                                        <option value="8">Baik (8)</option>
                                        <option value="10">Sangat Baik (10)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="text-sm text-gray-600">Total Halaman</label>
                                    <input type="number" x-model.number="halaman"
                                        placeholder="Halaman"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500">
                                </div>

                                <div>
                                    <label class="text-sm text-gray-600">Durasi Proyek</label>
                                    <input type="number" x-model.number="durasi"
                                        placeholder="Durasi"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500">
                                </div>

                                <div>
                                    <label class="text-sm text-gray-600">Hari Diambil</label>
                                    <input type="number" x-model.number="hari"
                                        placeholder="Hari diambil"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500">
                                </div>
                            </div>

                            <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-4">

                                <h3 class="font-semibold text-gray-700">Hasil Perhitungan</h3>

                                <div class="text-sm text-gray-600 space-y-2">
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-sm text-gray-600">
                                        <p class="text-gray-600"> Faktor Kualitas</p>
                                        <p>= Kualitas / 10</p>
                                        <p>= <span class="font-medium" x-text="kualitas"></span> / 10</p>
                                        <p class="text-brand-500">= <b x-text="faktorKualitas.toFixed(2)"></b></p>
                                    </div>

                                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-sm text-gray-600">
                                        <p class="text-gray-600"> Faktor Halaman</p>
                                        <p>= (Halaman Akhir - Halaman Awal + 1)</p>
                                        <p class="text-brand-500">= <b x-text="halaman"></b></p>
                                    </div>

                                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-sm text-gray-600">
                                        <p class="text-gray-600"> Durasi Proyek</p>
                                        <p>= Tanggal Selesai - Tanggal Mulai</p>
                                        <p class="text-brand-500">= <b x-text="durasi"></b></p>
                                    </div>

                                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-sm text-gray-600">
                                        <p class="text-gray-600"> Faktor Rasio</p>
                                        <p>= Hari Diambil / Durasi Proyek</p>
                                        <p>= <span class="font-medium" x-text="hari"></span> / <span class="font-medium" x-text="durasi"></span></p>
                                        <p class="text-brand-500">= <b x-text="faktorRasio.toFixed(2)"></b></p>
                                    </div>

                                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-sm text-gray-600">
                                        <p class="text-gray-600">Penalti</p>
                                        <p>= F. Kualitas x F. Halaman x F. Rasio x 2</p>
                                        <p class="font-medium">= <span x-text="faktorKualitas.toFixed(2)"></span> x <span x-text="halaman"></span> x <span x-text="faktorRasio.toFixed(2)"></span> x 2</p>
                                        <p class="text-brand-500">= <b x-text="penalti"></b></p>
                                    </div>
                                </div>

                                <div class="border-t border-gray-200 pt-3">
                                    <p class="text-red-600 font-bold text-lg">
                                        Penalti:
                                        <span x-text="penalti"></span>
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

            </div>

            <!-- Simulasi Skenario -->
            <div class="bg-white p-5 rounded-xl shadow" x-data="simulasiPoin()">

                <h2 class="font-semibold text-lg text-brand-500 mb-4">
                    Simulasi Skenario
                </h2>

                <table class="w-full border border-gray-200 text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 border border-gray-200">Skenario</th>

                            @foreach($poins as $p)
                            <th class="p-2 border border-gray-200">{{ $p->aspek }}</th>
                            @endforeach

                            <th class="p-2 border border-gray-200">Kualitas</th>
                            <th class="p-2 border border-gray-200">Rasio</th>
                            <th class="p-2 border border-gray-200">Poin</th>
                            <th class="p-2 border border-gray-200">Upah</th>
                            <th class="p-2 border border-gray-200">Penalti</th>
                        </tr>
                    </thead>

                    <tbody>
                        <template x-for="row in skenarioList" :key="row.nama">
                            <tr class="text-center">

                                <!-- NAMA -->
                                <td class="p-2 border border-gray-200 font-semibold" x-text="row.nama"></td>

                                <!-- SKOR PER ASPEK -->
                                <template x-for="(val, key) in row.skor" :key="key">
                                    <td class="p-2 border border-gray-200" x-text="val"></td>
                                </template>

                                <!-- KUALITAS -->
                                <td class="p-2 border border-gray-200" x-text="row.kualitas"></td>

                                <!-- RASIO -->
                                <td class="p-2 border border-gray-200" x-text="row.rasio"></td>

                                <!-- POIN -->
                                <td class="p-2 border border-gray-200 font-semibold text-brand-500"
                                    x-text="hitungPoin(row)">
                                </td>

                                <!-- UPAH -->
                                <td class="p-2 border border-gray-200 text-green-600 font-semibold"
                                    x-text="formatRupiah(hitungUpah(row))">
                                </td>

                                <!-- PENALTI -->
                                <td class="p-2 border border-gray-200 text-red-600 font-semibold"
                                    x-text="hitungPenalti(row)">
                                </td>

                            </tr>
                        </template>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    </div>
    <script>
        function simulasiPoin() {
            return {

                pilihRumus: 'penilaian',

                kualitas: 10,
                halaman: 50,
                durasi: 14,
                hari: 1,
                pendapatan: 100000,

                skor: @json($poins->mapWithKeys(fn($p)=>[$p->slug=>8])),
                bobot: @json($poins->pluck('bobot', 'slug')),

                skenarioList: [{
                        nama: 'Tinggi',
                        skor: @json($poins->mapWithKeys(fn($p)=>[$p->slug=> 0])),
                        kualitas: 10,
                        rasio: 0.2
                    },

                    {
                        nama: 'Sedang',
                        skor: @json($poins->mapWithKeys(fn($p)=>[$p->slug=>8])),
                        kualitas: 8,
                        rasio: 0.5
                    },

                    {
                        nama: 'Rendah',
                        skor: @json($poins->mapWithKeys(fn($p)=>[$p->slug=>6])),
                        kualitas: 6,
                        rasio: 0.8
                    }
                ],

                get faktorKualitas() {
                    return (this.kualitas || 0) / 10
                },

                get faktorPenilaian() {
                    let total = 0
                    for (let key in this.skor) {
                        total += (this.skor[key] || 0) * (this.bobot[key] || 0)
                    }
                    return total / 10
                },

                get faktorRasio() {
                    return this.hari / this.durasi
                },

                get totalPoin() {
                    return Math.round(
                        this.faktorKualitas * this.halaman * this.faktorPenilaian * 2
                    )
                },

                get detailPenilaian() {
                    let detail = []
                    let total = 0

                    for (let key in this.skor) {
                        let nilai = this.skor[key] || 0
                        let bobot = parseFloat(this.bobot[key]) || 0
                        let hasil = nilai * bobot

                        detail.push({
                            aspek: key,
                            nilai: nilai,
                            bobot: bobot,
                            hasil: hasil
                        })

                        total += hasil
                    }

                    return {
                        items: detail,
                        total: total,
                        final: total / 10
                    }
                },

                get upah() {
                    return this.pendapatan * this.faktorPenilaian
                },

                get penalti() {
                    return Math.round(
                        this.faktorKualitas * this.halaman * this.faktorRasio * 2
                    )
                },

                formatRupiah(angka) {
                    return new Intl.NumberFormat('id-ID').format(angka)
                },

                hitungFaktor(row) {
                    let total = 0
                    for (let key in row.skor) {
                        total += (row.skor[key] || 0) * (parseFloat(this.bobot[key]) || 0)
                    }
                    return total / 10
                },

                // 🔥 HITUNG POIN
                hitungPoin(row) {
                    return Math.round(
                        (row.kualitas / 10) *
                        this.halaman *
                        this.hitungFaktor(row) *
                        2
                    )
                },

                // 🔥 HITUNG UPAH
                hitungUpah(row) {
                    return this.pendapatan * this.hitungFaktor(row)
                },

                // 🔥 HITUNG PENALTI
                hitungPenalti(row) {
                    return Math.round(
                        (row.kualitas / 10) *
                        this.halaman *
                        row.rasio *
                        2
                    )
                },
            }
        }
    </script>
@endsection