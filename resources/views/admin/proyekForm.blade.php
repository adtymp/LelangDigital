@extends('layouts.body', ['title' => 'Tambah Proyek'])

@section('content')

<x-header
    :judul="$proyek ? 'Edit Proyek' : 'Tambah Proyek Baru'"
    :subjudul="'Lengkapi informasi proyek dan sub-proyek'" />

<form action="{{ $proyek ? route('proyek.update', $proyek->id) : route('proyek.add') }}" method="POST" enctype="multipart/form-data">
    @csrf

    @if($proyek)
    @method('POST')
    @endif

    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <h2 class="mb-4">Informasi Proyek</h2>
        <div class="mb-4">
            <x-input namaLabel="Nama Proyek" type="text"
                namaInput="nama_proyek" :value="old('nama_proyek', $proyek->nama_proyek ?? '')"
                slang="Contoh: Input Data Pelanggan Bulan November"></x-input>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <x-input namaLabel="Tanggal Mulai" type="datetime-local"
                    namaInput="tanggal_mulai" :value="old('tanggal_mulai', isset($proyek) ? \Carbon\Carbon::parse($proyek->tanggal_mulai)->format('Y-m-d\TH:i') : '')"
                    slang=""></x-input>
            </div>
            <div>
                <x-input namaLabel="Tanggal Selesai" type="datetime-local"
                namaInput="tanggal_selesai" :value="old('tanggal_selesai', isset($proyek) ? \Carbon\Carbon::parse($proyek->tanggal_selesai)->format('Y-m-d\TH:i') : '')"
                slang=""></x-input>
            </div>
        </div>
    </div>

    <div id="subProyekContainer"></div>

    <x-primary-button onclick="addSubProyek()">+ Tambah Sub Proyek</x-primary-button>

    <div class="flex justify-between border border-gray-200 bg-white rounded-xl mt-6 items-center p-6">
        <div>
            <p class="text-gray-700">
                Total Halaman Seluruh Proyek
            </p>
            <p class="text-brand-500 text-2xl font-semibold"><span id="totalProyekHalaman">0</span> Halaman</p>
        </div>

        <x-primary-button type="submit" >{{ $proyek ? 'Edit Proyek' : 'Tambah Proyek' }}</x-primary-button>
    </div>
</form>
</div>
</div>

<script>
    let subIndex = 0;

    function addSubProyek() {
        let html = `
<div class="border border-gray-200 p-6 mt-4 rounded-xl bg-white sub-proyek">

    <h3 class=" mb-2">Sub Proyek</h3>

    <input type="hidden" name="sub_proyek[${subIndex}][id]" class="subproyek-id">

    <x-input namaLabel="Nama Sub Proyek" type="text" namaInput="sub_proyek[${subIndex}][nama]" slang="Nama Sub Proyek"></x-input>

    <!-- TOTAL SUB PROYEK -->
    <div class="grid grid-cols-2 gap-4 text-sm mb-3">
        <div>
            Total Halaman Sub Proyek:
            <span class="font-semibold total-sub-halaman">0</span>
            <input type="hidden"
                name="sub_proyek[${subIndex}][total_halaman]"
                class="input-total-sub-halaman">
        </div>

        <div>
            Total Harga Sub Proyek:
            Rp <span class="font-semibold total-sub-harga">0</span>
        </div>
    </div>

    <p class="text-sm font-semibold mb-1">Sub Sub Proyek</p>
    <div id="subsub_${subIndex}" class="subsub-container"></div>

    <x-primary-button type="button" onclick="addSubSubProyek(${subIndex})">+ Tambah Subsub Proyek</x-primary-button>

    <button type="button"
        onclick="hapusSubProyek(this)"
        class="bg-red-700 hover:bg-red-800 text-white px-3 py-2 rounded-lg ml-2">
        Hapus
    </button>
</div>

`;


        document.getElementById('subProyekContainer').insertAdjacentHTML('beforeend', html);
        subIndex++;
    }


    function addSubSubProyek(i) {
        let container = document.getElementById('subsub_' + i);
        let index = container.children.length;


        let html = `
<div class="border border-gray-200 p-6 my-2 bg-white rounded-lg sub-sub-proyek">
    <div>
        <x-input namaLabel="Nama" type="text" namaInput="sub_proyek[${i}][sub_sub][${index}][nama_halaman]" slang="Nama : (ex:  Total Halaman 1-100)"></x-input>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="text-gray-500">File Tugas PDF</label>
            <div class="pdf-area text-sm mb-2"></div>
            <input type="hidden" name="sub_proyek[${i}][sub_sub][${index}][id]" class="subsub-id">
            <input type="file"
                name="sub_proyek[${i}][sub_sub][${index}][file_pdf]"
                accept="application/pdf"
                onchange="handlePdf(this)"
                class="input-pdf w-full px-4 py-3 border border-gray-200 rounded-lg file:py-1 file:px-2 file:rounded-md file:border-0 file:bg-brand-500 file:text-white hover:file:bg-brand-700">

            <input type="hidden"
                name="sub_proyek[${i}][sub_sub][${index}][total_halaman]"
                class="total-halaman">

            <div class="text-sm text-gray-600 mb-2">
                Total Halaman: <span class="halaman-text">-</span>
            </div>
        </div>

        <!-- XLS -->
        <div classs="mb-4">
            <label class="text-gray-500">File Template XLS</label>
            <div class="xls-area text-sm mb-2"></div>

            <input type="file"
                name="sub_proyek[${i}][sub_sub][${index}][file_xls]" accept=".xls,.xlsx"
                class="input-xls w-full px-4 py-3 border border-gray-200 rounded-lg file:py-1 file:px-2 file:rounded-md file:border-0 file:bg-brand-500 file:text-white hover:file:bg-brand-700">
        </div>
        
        <div class="mb-4">
            <x-input namaLabel="Harga Per Lembar" type="text"
                namaInput="sub_proyek[${i}][sub_sub][${index}][harga]" slang="Harga Perlembar"
                oninput="hitungSubtotal(this)"></x-input>
        </div>
        <div class="mb-4">
            <label class="text-gray-500">Kualitas</label>
            <select type="number" name="sub_proyek[${i}][sub_sub][${index}][kualitas]" placeholder="Kualitas : (1-10)"
            class="w-full px-4 py-3 border border-gray-200 rounded-lg mb-2 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent">
                <option value="">Pilih Kualitas</option>
                <option value="2">Sangat Kurang (2)</option>
                <option value="4">Kurang (4)</option>
                <option value="6">Sedang (6)</option>
                <option value="8">Baik (8)</option>
                <option value="10">Sangat Baik (10)</option>
            </select>
        </div>
    </div>
    <div class="text-sm font-semibold">
        Subtotal: Rp <span class="subtotal-text">0</span>
    </div>
    <button type="button" onclick="hapusSubSub(this)" class="bg-red-700 hover:bg-red-800 text-white px-3 py-2 rounded-lg">Hapus</button>
</div>`;


        container.insertAdjacentHTML('beforeend', html);

        let wrapper = container.lastElementChild

        renderFileUI(wrapper, null, 'pdf', i, index)
        renderFileUI(wrapper, null, 'xls', i, index)
    }

    function hitungTotalSubProyek(subProyekEl) {
        let totalHalaman = 0;
        let totalHarga = 0;

        subProyekEl.querySelectorAll('.total-halaman').forEach(el => {
            totalHalaman += parseInt(el.value || 0);
        });

        subProyekEl.querySelectorAll('.subtotal-text').forEach(el => {
            totalHarga += parseInt(el.innerText.replace(/\./g, '') || 0);
        });

        subProyekEl.querySelector('.total-sub-halaman').innerText = totalHalaman;
        subProyekEl.querySelector('.input-total-sub-halaman').value = totalHalaman;

        subProyekEl.querySelector('.total-sub-harga').innerText =
            totalHarga.toLocaleString('id-ID');

        hitungTotalProyek();
    }

    async function handlePdf(input) {

        let file = input.files[0];
        if (!file) return;

        let wrapper = input.closest('.sub-sub-proyek');

        if (!wrapper) {
            console.error('Wrapper .sub-sub-proyek tidak ditemukan');
            return;
        }

        let formData = new FormData();
        formData.append('file', file);
        formData.append('_token', '{{ csrf_token() }}');

        let response = await fetch('{{ route("ajax.pdf.info") }}', {
            method: 'POST',
            body: formData
        });

        let data = await response.json();

        let halamanText = wrapper.querySelector('.halaman-text');
        let totalHalaman = wrapper.querySelector('.total-halaman');

        if (halamanText) halamanText.innerText = data.total_halaman;
        if (totalHalaman) totalHalaman.value = data.total_halaman;

        let subProyek = wrapper.closest('.sub-proyek');

        if (subProyek) {
            hitungTotalSubProyek(subProyek);
        }

        let hargaInput = wrapper.querySelector('[name$="[harga]"]');
        if (hargaInput && hargaInput.value) {
            hitungSubtotal(hargaInput);
        }
    }

    function hitungSubtotal(input) {
        let wrapper = input.closest('.sub-sub-proyek');
        let harga = parseInt(input.value || 0);
        let halaman = parseInt(wrapper.querySelector('.total-halaman').value || 0);
        let subtotal = harga * halaman;

        wrapper.querySelector('.subtotal-text').innerText =
            subtotal.toLocaleString('id-ID');

        let subProyek = input.closest('.sub-proyek');
        hitungTotalSubProyek(subProyek);
    }

    function hitungTotalProyek() {
        let total = 0;

        document.querySelectorAll('.input-total-sub-halaman').forEach(el => {
            total += parseInt(el.value || 0);
        });

        document.getElementById('totalProyekHalaman').innerText = total;
    }

    function hapusSubProyek(btn) {
        btn.closest('.sub-proyek').remove();
        hitungTotalProyek();
    }

    function hapusSubSub(btn) {
        let subProyek = btn.closest('.sub-proyek');

        btn.parentElement.remove();

        hitungTotalSubProyek(subProyek);
    }

    function renderFileUI(wrapper, file, type) {

        let area = type === 'pdf' ?
            wrapper.querySelector('.pdf-area') :
            wrapper.querySelector('.xls-area');

        if (!file) {
            area.innerHTML = '';
            return;
        }

        let name = file.split('/').pop();

        if (type === 'pdf') {

            area.innerHTML = `
            <div class="flex items-center gap-2 text-sm">
                📄 <a href="/storage/${file}" target="_blank"
                class="text-brand-500 underline">${name}</a>

                <button type="button"
                onclick="this.closest('.sub-sub-proyek').querySelector('.input-pdf').click()"
                class="text-xs bg-gray-200 px-2 py-1 rounded">
                Ganti File
                </button>
            </div>
            `;

        }

        if (type === 'xls') {

            area.innerHTML = `
            <div class="flex items-center gap-2 text-sm">
                📊 <span>${name}</span>

                <button type="button"
                onclick="this.closest('.sub-sub-proyek').querySelector('.input-xls').click()"
                class="text-xs bg-gray-200 px-2 py-1 rounded">
                Ganti File
                </button>
            </div>
            `;

        }
    }
</script>
@if($proyek)
<script>
    let dataSub = @json($proyek->subproyeks);

    window.onload = () => {

        dataSub.forEach((sub, i) => {

            addSubProyek()

            let subEl = document.querySelectorAll('.sub-proyek')[i]

            subEl.querySelector('.subproyek-id').value = sub.id
            subEl.querySelector('[name^="sub_proyek"][name$="[nama]"]').value = sub.nama_sub_proyek

            let container = document.getElementById('subsub_' + i)

            sub.subsubproyeks.forEach((subsub, j) => {

                addSubSubProyek(i)

                let subsubEl = container.children[j]

                subsubEl.querySelector('.subsub-id').value = subsub.id
                subsubEl.querySelector('[name$="[nama_halaman]"]').value = subsub.nama_halaman
                subsubEl.querySelector('[name$="[harga]"]').value = subsub.harga_perlembar
                subsubEl.querySelector('[name$="[kualitas]"]').value = subsub.kualitas
                subsubEl.querySelector('.halaman-text').innerText = subsub.total_halaman
                subsubEl.querySelector('.total-halaman').value = subsub.total_halaman

                hitungSubtotal(subsubEl.querySelector('[name$="[harga]"]'))

                renderFileUI(subsubEl, subsub.file_pdf, 'pdf', i, j)
                renderFileUI(subsubEl, subsub.file_xls, 'xls', i, j)

            })

        })

    }
</script>
@endif
@endsection