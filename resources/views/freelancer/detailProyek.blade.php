@extends('layouts.body', ['title' => 'Detail Proyek Pekerjaan'])

@section('content')
<div class="mb-6 sm:mb-8 flex items-start gap-4">
    <a href="{{ route('dashboard.freelance') }}" class="shrink-0 w-10 h-10 sm:w-11 sm:h-11 rounded-2xl border border-slate-200 bg-white flex items-center justify-center hover:bg-brand-50 hover:border-brand-300 transition-all duration-200 shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" height="18" width="18" class="sm:w-5 sm:h-5" viewBox="0 0 640 640">
            <path fill="currentColor" d="M169.4 297.4C156.9 309.9 156.9 330.2 169.4 342.7L361.4 534.7C373.9 547.2 394.2 547.2 406.7 534.7C419.2 522.2 419.2 501.9 406.7 489.4L237.3 320L406.6 150.6C419.1 138.1 419.1 117.8 406.6 105.3C394.1 92.8 373.8 92.8 361.3 105.3L169.3 297.3z" />
        </svg>
    </a>
    <div>
        <h1 class="text-xl sm:text-2xl font-bold text-slate-900"> Lihat & Ambil Proyek </h1>
        <p class="text-slate-500 text-sm sm:text-base mt-1"> {{ $subsub->subproyeks->proyeks->nama_proyek }} </p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
    <!-- KOLOM KIRI: Informasi Proyek & Berkas Pendukung (Tampil Pertama di Mobile) -->
    <div class="lg:col-span-5 space-y-6">
        <!-- Card 1: Informasi Proyek -->
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl sm:rounded-3xl border border-slate-200/70 shadow-sm hover:shadow-md transition-all duration-300 p-4 sm:p-6">
            <h2 class="text-base sm:text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-brand-500">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-18.75 0a2.25 2.25 0 0 0-2.25 2.25v3a2.25 2.25 0 0 0 2.25 2.25h15a2.25 2.25 0 0 0 2.25-2.25v-3a2.25 2.25 0 0 0-2.25-2.25m-18.75 0V7.5A2.25 2.25 0 0 1 4.5 5.25h15A2.25 2.25 0 0 1 21.75 7.5v5.25" />
                </svg>
                <span>Informasi Proyek</span>
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-4">
                <div>
                    <p class="text-gray-400 text-xs font-semibold uppercase tracking-wider">Nama Proyek</p>
                    <p class="text-gray-900 font-medium mt-0.5 text-sm sm:text-base">{{ $subsub->subproyeks->proyeks->nama_proyek }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs font-semibold uppercase tracking-wider">Periode Proyek</p>
                    <p class="text-gray-900 font-medium mt-0.5 text-xs sm:text-sm leading-relaxed">
                        <span class="text-blue-600">{{ $subsub->subproyeks->proyeks->tanggal_mulai->format('d M Y H:i') }} WIB</span> s.d. <span class="text-red-600">{{ $subsub->subproyeks->proyeks->tanggal_selesai->format('d M Y H:i') }} WIB</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Card 2: Berkas Pendukung -->
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl sm:rounded-3xl border border-slate-200/70 shadow-sm hover:shadow-md transition-all duration-300 p-4 sm:p-6">
            <h2 class="text-base sm:text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-brand-500">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
                <span>Berkas Pendukung</span>
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-4">
                <!-- Berkas PDF -->
                <div class="border border-slate-200/80 bg-slate-50/50 hover:bg-slate-50 rounded-2xl p-4 flex items-center gap-3 transition-colors">
                    <div class="p-2.5 bg-rose-50 text-rose-600 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-900 truncate">File PDF Tugas</p>
                        <p class="text-xs text-slate-500">Unduh petunjuk kerja</p>
                    </div>
                    <a href="{{ route('freelancer.downloadPdfSubsubproyek', $subsub->id) }}" class="flex items-center justify-center w-9 h-9 bg-brand-50 text-brand-700 hover:bg-brand-600 hover:text-white rounded-xl transition-all shadow-sm shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                    </a>
                </div>
                <!-- Berkas Excel Template -->
                <div class="border border-slate-200/80 bg-slate-50/50 hover:bg-slate-50 rounded-2xl p-4 flex items-center gap-3 transition-colors">
                    <div class="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-900 truncate">Template XLS</p>
                        <p class="text-xs text-slate-500">Unduh kerangka kerja</p>
                    </div>
                    <a href="{{ route('freelancer.downloadTemplateSubsubproyek', $subsub->id) }}" class="flex items-center justify-center w-9 h-9 bg-brand-50 text-brand-700 hover:bg-brand-600 hover:text-white rounded-xl transition-all shadow-sm shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- KOLOM KANAN: Detail Sub Proyek, Progress, Page Picker, & Form Ambil Halaman -->
    <div class="lg:col-span-7 space-y-6">
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl sm:rounded-3xl border border-slate-200/70 shadow-sm hover:shadow-md transition-all duration-300 p-4 sm:p-6">
            <h2 class="text-base sm:text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-brand-500">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.25 9.75L16.5 12l-2.25 2.25m-4.5 0L7.5 12l2.25-2.25M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z" />
                </svg>
                <span>Detail & Ambil Sub Proyek</span>
            </h2>

            <div class="space-y-6">
                <!-- Info Detail Sub Proyek -->
                <div>
                    <p class="text-gray-400 text-xs font-semibold uppercase tracking-wider">Nama Sub Proyek</p>
                    <p class="text-gray-900 font-medium mt-0.5 text-sm sm:text-base mb-4">{{ $subsub->subproyeks->nama_sub_proyek }}</p>

                    <div class="p-4 bg-slate-50 border border-slate-200/50 rounded-2xl">
                        <p class="text-brand-700 text-xs font-bold uppercase tracking-wider mb-3">Informasi Sub Sub Proyek</p>
                        <div class="grid grid-cols-2 gap-y-3 gap-x-4 text-sm">
                            <div>
                                <p class="text-gray-400 text-xs">Rentang Halaman</p>
                                <p class="text-gray-900 font-medium mt-0.5">Halaman {{ $subsub->nama_halaman }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs">Harga / Lembar</p>
                                <p class="text-gray-900 font-bold mt-0.5">Rp {{ number_format($subsub->harga_perlembar, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs">Kualitas Minimal</p>
                                <p class="text-gray-900 font-medium mt-0.5">{{ $subsub->kualitas }}/10</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs">Halaman Tersedia</p>
                                <p class="font-semibold mt-0.5 text-green-600">{{ $sisaHalaman }} Halaman</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div>
                    <div class="flex justify-between text-xs text-gray-500 mb-2">
                        <span class="font-medium">Progress Pengambilan</span>
                        <span class="font-semibold">{{ $halamanDiambil }} / {{ $totalHalaman }} halaman</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                        <div
                            class="bg-linear-to-r from-brand-600 to-brand-400 h-full transition-all duration-300 rounded-full"
                            style="width: {{$persentase}}%">
                        </div>
                    </div>
                </div>

                <hr class="border-slate-100">

                <!-- 1. PAGE PICKER (TAMPIL DI ATAS INPUT MANUAL) -->
                <div>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                        <h3 class="text-sm font-bold text-slate-900 flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4.5 h-4.5 text-brand-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75 2.25 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.03 0 1.9.693 2.166 1.638m-7.377 0A48.536 48.536 0 0 1 12 3c.08 0 .16.002.24.005m-7.24 16.27v-15A2.25 2.25 0 0 1 7.25 2h.08m0 17.25h9.75M6.25 22h11.5" />
                            </svg>
                            <span>Pilih Halaman pada Grid</span>
                        </h3>
                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1.5 text-xs text-slate-500 font-semibold">
                            <div class="flex items-center gap-1.5">
                                <div class="w-3 h-3 rounded-md bg-white border border-slate-200"></div>
                                <span>Tersedia</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <div class="w-3 h-3 rounded bg-red-100 border border-red-200"></div>
                                <span>Diambil</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <div class="w-3 h-3 rounded bg-brand-600"></div>
                                <span>Dipilih</span>
                            </div>
                        </div>
                    </div>
                    <div
                        id="pagePicker"
                        class="grid grid-cols-6 sm:grid-cols-8 md:grid-cols-8 lg:grid-cols-8 xl:grid-cols-10 gap-2 max-h-64 overflow-y-auto p-1.5 border border-slate-100 rounded-2xl bg-slate-50/40">
                        @for($i = 1; $i <= $subsub->total_halaman; $i++)
                            @php
                            $isTaken = false;
                            foreach($akanDiambil as $range){
                            if($i >= $range->dari_halaman && $i <= $range->sampai_halaman){
                                $isTaken = true;
                                break;
                                }
                                }
                                @endphp
                                <button
                                    type="button"
                                    data-page="{{ $i }}"
                                    {{ $isTaken ? 'disabled' : '' }}
                                    class="page-item h-9 rounded-xl text-xs font-semibold transition-all duration-200 border flex items-center justify-center
                                 {{ $isTaken
                                     ? 'bg-red-50 text-red-300 border-red-100 cursor-not-allowed opacity-75'
                                     : 'bg-white hover:bg-brand-50 hover:border-brand-300 border-slate-200 text-slate-700 hover:scale-[1.03] active:scale-95 shadow-sm' }}">
                                    {{ $i }}
                                </button>
                                @endfor
                    </div>
                </div>

                <hr class="border-slate-100">

                <!-- 2. FORM AMBIL HALAMAN (DIBAWAH PAGE PICKER) -->
                <form id="ambilForm" method="POST" action="{{ route('freelance.ambil.proses', $subsub->id) }}" enctype="multipart/form-data" class="pt-2">
                    @csrf
                    <input type="hidden" name="subsubproyek_id" value="{{ $subsub->id }}">

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <x-input type="number" namaLabel="Dari Halaman" namaInput="dari_halaman" :value="$halamanTersedia" min="1" max="{{ $subsub->total_halaman }}" id="pageFrom" class="focus:ring-brand-500"></x-input>
                        <x-input type="number" namaLabel="Sampai Halaman" namaInput="sampai_halaman" :value="$halamanTersedia" min="1" max="{{ $subsub->total_halaman }}" id="pageTo" class="focus:ring-brand-500"></x-input>
                    </div>

                    <div class="mb-4 p-3 bg-brand-50/50 border border-brand-100/50 rounded-xl flex items-start gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-brand-600 shrink-0 mt-0.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 1 1 1.063 1.06l-.041.02a.75.75 0 0 1-1.063-1.06Zm0 4.5.041-.02a.75.75 0 1 1 1.063 1.06l-.041.02a.75.75 0 0 1-1.063-1.06ZM12 22.5c5.799 0 10.5-4.701 10.5-10.5S17.799 1.5 12 1.5 1.5 6.201 1.5 12 6.201 22.5 12 22.5Z" />
                        </svg>
                        <p class="text-[11px] text-brand-800 leading-normal">
                            Sistem otomatis mengisi halaman tersedia. Anda dapat menyesuaikan rentang dengan mengeklik halaman grid di atas atau mengisi input angka manual.
                        </p>
                    </div>

                    <div class="p-4 bg-emerald-50/60 border border-emerald-100/80 rounded-2xl mb-4">
                        <p class="text-emerald-800 text-xs font-semibold uppercase tracking-wider mb-1">Estimasi Pendapatan</p>
                        <p id="estimasiHarga" class="text-2xl font-extrabold text-emerald-600">
                            Rp 0
                        </p>
                    </div>

                    <x-primary-button type="submit" full>Ambil Halaman</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    const totalHalaman = {{$totalHalaman}};
    const hargaPerLembar = {{$subsub->harga_perlembar}};

    // Array range halaman yang sudah diambil
    const takenRanges = @json($akanDiambil);

    const pageFromInput = document.getElementById('pageFrom');
    const pageToInput = document.getElementById('pageTo');
    const estimasiEl = document.getElementById('estimasiHarga');
    const ambilForm = document.getElementById('ambilForm');
    const pageButtons = document.querySelectorAll('.page-item');

    let selectedFrom = null;
    let selectedTo = null;

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }

    // Fungsi menampilkan error lewat komponen x-toast yang mendengarkan event 'notify'
    function showErrorAlert(message) {
        window.dispatchEvent(new CustomEvent('toast', {
            detail: {
                type: 'error',
                title: 'Gagal Mengambil Halaman',
                message: message
            }
        }));
    }

    // Fungsi mengecek apakah range yang diinput menabrak range yang sudah diambil
    function checkOverlap(from, to) {
        if (!from || !to) return {
            overlap: false
        };

        for (let range of takenRanges) {
            let rangeFrom = parseInt(range.dari_halaman);
            let rangeTo = parseInt(range.sampai_halaman);

            // Logika bentrok: overlap jika (from <= rangeTo && to >= rangeFrom)
            if (from <= rangeTo && to >= rangeFrom) {
                return {
                    overlap: true,
                    rangeFrom: rangeFrom,
                    rangeTo: rangeTo
                };
            }
        }
        return {
            overlap: false
        };
    }

    // Cari halaman akhir yang valid (maksimal 50 halaman, berhenti sebelum halaman terambil)
    function getValidEndPage(from) {
        let maxTo = from + 49;
        if (maxTo > totalHalaman) {
            maxTo = totalHalaman;
        }

        let earliestTakenPage = null;

        for (let range of takenRanges) {
            let rangeFrom = parseInt(range.dari_halaman);
            let rangeTo = parseInt(range.sampai_halaman);

            // Jika ada halaman terambil dalam rentang [from, maxTo]
            if (rangeFrom >= from && rangeFrom <= maxTo) {
                if (earliestTakenPage === null || rangeFrom < earliestTakenPage) {
                    earliestTakenPage = rangeFrom;
                }
            }
        }

        if (earliestTakenPage !== null) {
            return earliestTakenPage - 1;
        }

        return maxTo;
    }

    function hitungEstimasi() {
        let from = parseInt(pageFromInput.value);
        let to = parseInt(pageToInput.value);

        if (!from || !to || from > to) {
            estimasiEl.innerText = 'Rp 0';
            return;
        }

        let totalHal = (to - from) + 1;
        let totalHarga = totalHal * hargaPerLembar;

        estimasiEl.innerText = 'Rp ' + formatRupiah(totalHarga);
    }

    function autoSetRange() {
        let from = parseInt(pageFromInput.value);
        if (!from || from < 1) return;

        let to = getValidEndPage(from);
        if (to < from) {
            to = from;
        }

        pageToInput.value = to;
        hitungEstimasi();
    }

    // EVENT MANUAL INPUT (Hanya hitung estimasi & highlight, validasi bentrok dilakukan saat submit)
    pageFromInput.addEventListener('input', () => {
        let from = parseInt(pageFromInput.value);
        let to = parseInt(pageToInput.value);
        if (from && to) {
            selectedFrom = from;
            selectedTo = to;
            clearSelectionHighlight();
            highlightRange(from, to);
        }
        hitungEstimasi();
    });

    pageFromInput.addEventListener('change', () => {
        autoSetRange();
        let from = parseInt(pageFromInput.value);
        let to = parseInt(pageToInput.value);
        if (from && to) {
            selectedFrom = from;
            selectedTo = to;
            clearSelectionHighlight();
            highlightRange(from, to);
        }
    });

    pageToInput.addEventListener('input', () => {
        let from = parseInt(pageFromInput.value);
        let to = parseInt(pageToInput.value);
        if (from && to) {
            selectedFrom = from;
            selectedTo = to;
            clearSelectionHighlight();
            highlightRange(from, to);
        }
        hitungEstimasi();
    });

    pageToInput.addEventListener('change', () => {
        let from = parseInt(pageFromInput.value);
        let to = parseInt(pageToInput.value);

        if (from && to && from > to) {
            showErrorAlert('Halaman awal tidak boleh lebih besar dari halaman akhir.');
            pageToInput.value = from;
        }
        hitungEstimasi();
    });

    // SISTEM HIGHLIGHT PICKER
    function clearSelectionHighlight() {
        pageButtons.forEach(btn => {
            if (!btn.disabled) {
                btn.classList.remove('bg-brand-600', 'text-white', 'border-brand-600');
                btn.classList.add('bg-white', 'text-slate-700', 'border-slate-200');
            }
        });
    }

    function highlightRange(from, to) {
        pageButtons.forEach(btn => {
            const page = parseInt(btn.dataset.page);
            if (page >= from && page <= to && !btn.disabled) {
                btn.classList.remove('bg-white', 'text-slate-700', 'border-slate-200');
                btn.classList.add('bg-brand-600', 'text-white', 'border-brand-600');
            }
        });
    }

    // EVENT PAGE PICKER CLICK (Logika 3-Klik: Mulai, Selesai, Reset)
    pageButtons.forEach(button => {
        button.addEventListener('click', () => {
            const page = parseInt(button.dataset.page);

            if (selectedFrom === null || (selectedFrom !== null && selectedTo !== null)) {
                // Klik pertama / Reset seleksi
                selectedFrom = page;
                selectedTo = null;

                pageFromInput.value = selectedFrom;
                pageToInput.value = selectedFrom;
            } else {
                // Klik kedua
                selectedTo = page;
                if (selectedTo < selectedFrom) {
                    [selectedFrom, selectedTo] = [selectedTo, selectedFrom];
                }

                pageFromInput.value = selectedFrom;
                pageToInput.value = selectedTo;
            }

            // Highlight tombol
            clearSelectionHighlight();
            if (selectedTo === null) {
                highlightRange(selectedFrom, selectedFrom);
            } else {
                highlightRange(selectedFrom, selectedTo);
            }

            // Hitung estimasi harga
            hitungEstimasi();
        });
    });

    // HANDLER SUBMIT FORM (VALIDASI & REFRESH HALAMAN)
    ambilForm.addEventListener('submit', function(e) {
        let from = parseInt(pageFromInput.value);
        let to = parseInt(pageToInput.value);

        if (!from || !to) {
            e.preventDefault();
            showErrorAlert('Silakan pilih rentang halaman terlebih dahulu.');
            return;
        }

        if (from > to) {
            e.preventDefault();
            showErrorAlert('Halaman awal tidak boleh lebih besar dari halaman akhir.');
            return;
        }

        // Validasi overlap / tabrakan halaman saat tombol diklik
        const check = checkOverlap(from, to);
        if (check.overlap) {
            e.preventDefault();
            showErrorAlert(`Rentang halaman ${from} - ${to} menabrak halaman yang sudah diambil. Silakan pilih rentang halaman lain.`);
            return;
        }

        // Lakukan refresh halaman setelah delay 2 detik (saat file ZIP mulai diunduh browser)
        // setTimeout(() => {
        //     window.location.reload();
        // }, 1000);
    });

    // Jalankan kalkulasi estimasi awal
    autoSetRange();
    let initialFrom = parseInt(pageFromInput.value);
    let initialTo = parseInt(pageToInput.value);
    if (initialFrom && initialTo) {
        selectedFrom = initialFrom;
        selectedTo = initialTo;
        clearSelectionHighlight();
        highlightRange(initialFrom, initialTo);
    }
</script>

@if(session('zip_ready'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const file = "{{ session('zip_file') }}";
        const url = "{{ route('freelance.download.zip') }}?file=" + encodeURIComponent(file);

        // Tampilkan banner download yang bisa diklik user
        const banner = document.createElement('div');
        banner.innerHTML = `
            <div style="position:fixed;bottom:20px;right:20px;z-index:9999;background:#16a34a;color:white;padding:16px 20px;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,0.2)">
                Tugas berhasil diambil! 
                <a href="${url}" download style="color:white;font-weight:bold;text-decoration:underline;margin-left:8px;">
                    Klik di sini untuk unduh ZIP
                </a>
            </div>`;
        document.body.appendChild(banner);
        setTimeout(() => banner.remove(), 15000);
    });
</script>
@endif
@endsection