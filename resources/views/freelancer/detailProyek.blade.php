@extends('layouts.body', ['title' => 'Dashboard'])

@section('content')
<div class="mb-8 flex items-start gap-4"> <a href="{{ route('dashboard.freelance') }}" class="shrink-0 w-11 h-11 rounded-2xl border border-slate-200 bg-white flex items-center justify-center hover:bg-brand-50 hover:border-brand-300 transition-all duration-200 shadow-sm"> <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 640 640"> <path fill="currentColor" d="M169.4 297.4C156.9 309.9 156.9 330.2 169.4 342.7L361.4 534.7C373.9 547.2 394.2 547.2 406.7 534.7C419.2 522.2 419.2 501.9 406.7 489.4L237.3 320L406.6 150.6C419.1 138.1 419.1 117.8 406.6 105.3C394.1 92.8 373.8 92.8 361.3 105.3L169.3 297.3z" /> </svg> </a> <div> <h1 class="text-2xl font-bold text-slate-900"> Lihat & Ambil Proyek </h1> <p class="text-slate-500 mt-1"> {{ $subsub->subproyeks->proyeks->nama_proyek }} </p> </div> </div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="space-y-6">
        <div class="bg-white/90 backdrop-blur-sm rounded-3xl border border-slate-200/70 shadow-sm hover:shadow-xl transition-all duration-300 p-6">
            <h2 class="text-gray-900 mb-4">Informasi Proyek</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-gray-500 text-sm">Nama Proyek</p>
                    <p class="text-gray-900">{{ $subsub->subproyeks->proyeks->nama_proyek }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Periode Lelang</p>
                    <p class="text-gray-900">
                        {{ $subsub->subproyeks->proyeks->tanggal_mulai->format('d M Y H:i') }} - {{ $subsub->subproyeks->proyeks->tanggal_selesai->format('d M Y H:i') }}
                    </p>
                </div>
            </div>
        </div>
        <div class="bg-white/90 backdrop-blur-sm rounded-3xl border border-slate-200/70 shadow-sm hover:shadow-xl transition-all duration-300 p-6">
            <div class="space-y-3">
                <div>
                    <p class="text-gray-500 text-sm">Nama Sub Proyek</p>
                    <p class="text-gray-900">{{ $subsub->subproyeks->nama_sub_proyek }}</p>
                </div>
                <div class="p-4 bg-blue-50 rounded-lg mb-4">
                    <p class="text-gray-500 text-sm mb-2">Informasi Sub Sub Proyek</p>
                    <div class="space-y-1 text-sm">
                        <p class="text-gray-900">
                            Rentang: Halaman {{ $subsub->nama_halaman }}
                        </p>
                        <p class="text-gray-900">
                            Harga: Rp {{ $subsub->harga_perlembar }}/lembar
                        </p>
                        <p class="text-gray-900">
                            Kualitas: {{ $subsub->kualitas }}/10
                        </p>
                        <p class="text-gray-900">
                            Tersedia: {{ $sisaHalaman }} halaman
                        </p>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="flex justify-between text-sm text-gray-500 mb-2">
                        <span>Progress Pengambilan</span>
                        <span>{{ $halamanDiambil }} / {{ $totalHalaman }} halaman</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                        <div
                            class="bg-linear-to-r from-brand-700 to-brand-500 h-full transition-all duration-300"
                            style="width: {{ $persentase }}%">
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 mb-2">
                        @if($sisaHalaman > 0)
                        {{ $sisaHalaman }} halaman masih tersedia
                        @else
                        Semua halaman sudah diambil
                        @endif
                    </p>
                    <form method="POST" action="{{ route('freelance.ambil.proses', $subsub->id) }}" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="subsubproyek_id" value="{{ $subsub->id }}">

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input type="number" namaLabel="Dari Halaman" namaInput="dari_halaman" :value="$halamanTersedia" min="1" max="$totalhalaman" id="pageFrom"></x-input>
                            </div>

                            <div>
                                <x-input type="number" namaLabel="Sampai Halaman" namaInput="sampai_halaman" :value="$halamanTersedia" min="1" max="$totalhalaman" id="pageTo"></x-input>
                            </div>
                        </div>
                        <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg flex items-start gap-2">
                            <p class="text-xs text-gray-500">
                                Sistem otomatis mengisi halaman yang masih tersedia. Anda dapat mengubah rentang halaman selama masih tersedia.
                            </p>
                        </div>
                        <div class="p-4 bg-green-50 rounded-lg mb-4">
                            <p class="text-gray-500 text-sm mb-1">Estimasi Pendapatan</p>
                            <p id="estimasiHarga" class="text-2xl font-semibold text-green-600">
                                Rp 0
                            </p>
                        </div>

                        <x-primary-button type="submit" full>Ambil Halaman</x-primary-button>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <div class="space-y-6">
        <div class="bg-white/90 backdrop-blur-sm rounded-3xl border border-slate-200/70 shadow-sm hover:shadow-xl transition-all duration-300 p-6">
            <h2 class="text-gray-900 mb-4">Preview File</h2>
            <div class="space-y-4">
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                    <FileText class="w-16 h-16 text-gray-500 mx-auto mb-4" />
                    <p class="text-gray-900 mb-2">File PDF Tugas</p>
                    <button class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-800 transition-colors mx-auto">
                        Download PDF
                    </button>
                </div>

                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                    <FileText class="w-16 h-16 text-green-600 mx-auto mb-4" />
                    <p class="text-gray-900 mb-2">Template XLS</p>
                    <button class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors mx-auto">
                        Download Template
                    </button>
                </div>

                <div class="mt-6">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-700">
                            Pilih Halaman
                        </h3>

                        <div class="flex items-center gap-2 text-xs">
                            <div class="flex items-center gap-1">
                                <div class="w-3 h-3 rounded bg-green-200"></div>
                                <span>Tersedia</span>
                            </div>

                            <div class="flex items-center gap-1">
                                <div class="w-3 h-3 rounded bg-red-500"></div>
                                <span>Diambil</span>
                            </div>

                            <div class="flex items-center gap-1">
                                <div class="w-3 h-3 rounded bg-brand-600"></div>
                                <span>Dipilih</span>
                            </div>
                        </div>
                    </div>

                    <div
                        id="pagePicker"
                        class="grid grid-cols-10 md:grid-cols-12 lg:grid-cols-10 gap-2">

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

                                    class="page-item h-7 rounded-xl text-sm font-medium transition-all duration-200 border 
                                    {{ $isTaken
                                        ? 'bg-red-500 text-white border-red-500 cursor-not-allowed opacity-80'
                                        : 'bg-white hover:bg-brand-50 hover:border-brand-400 border-gray-200 text-gray-700 hover:scale-105'}}">
                                    {{ $i }}
                                </button>
                        @endfor
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<script>
    const totalHalaman = {{$totalHalaman}};
    const hargaPerLembar = {{$subsub->harga_perlembar}};

    const pageFromInput = document.getElementById('pageFrom');
    const pageToInput = document.getElementById('pageTo');
    const estimasiEl = document.getElementById('estimasiHarga');

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
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

        let to = from + 49;

        if (to > totalHalaman) {
            to = totalHalaman;
        }

        pageToInput.value = to;

        hitungEstimasi(); // 🔥 langsung hitung
    }

    // INIT
    autoSetRange();
    hitungEstimasi();

    // EVENT
    pageFromInput.addEventListener('input', () => {
        autoSetRange();
    });

    pageToInput.addEventListener('input', hitungEstimasi);
</script>
```html
<script>
    const pageButtons = document.querySelectorAll('.page-item');

    let selectedFrom = null;
    let selectedTo = null;

    function clearSelection() {
        pageButtons.forEach(btn => {
            if (!btn.disabled) {
                btn.classList.remove(
                    'bg-brand-600',
                    'text-white',
                    'border-brand-600'
                );

                btn.classList.add(
                    'bg-white',
                    'text-gray-700'
                );
            }
        });
    }

    function highlightRange(from, to) {

        clearSelection();

        pageButtons.forEach(btn => {

            const page = parseInt(btn.dataset.page);

            if (page >= from && page <= to && !btn.disabled) {

                btn.classList.remove(
                    'bg-white',
                    'text-gray-700'
                );

                btn.classList.add(
                    'bg-brand-600',
                    'text-white',
                    'border-brand-600'
                );
            }
        });
    }

    pageButtons.forEach(button => {

        button.addEventListener('click', () => {

            const page = parseInt(button.dataset.page);

            // klik pertama
            if (selectedFrom === null) {

                selectedFrom = page;
                selectedTo = page;

            } else {

                // klik kedua
                selectedTo = page;

                if (selectedTo < selectedFrom) {
                    [selectedFrom, selectedTo] = [selectedTo, selectedFrom];
                }
            }

            // isi input
            pageFromInput.value = selectedFrom;
            pageToInput.value = selectedTo;

            // highlight
            highlightRange(selectedFrom, selectedTo);

            // hitung harga
            hitungEstimasi();
        });
    });
</script>
@endsection