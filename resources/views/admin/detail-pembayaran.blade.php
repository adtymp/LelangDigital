@extends('layouts.body', ['title' => 'Detail Pembayaran'])

@section('content')

<div class="mb-6">
    <x-header
        :judul="'Detail Pembayaran'"
        :subjudul="'Informasi lengkap upah dan rincian hasil kerja freelancer'" />
</div>

<div class="space-y-6" x-data="{ paymentProof: null }">
    
    <div class="flex justify-between items-center bg-white border border-gray-200 rounded-3xl p-5 shadow-sm">
        <span class="text-sm text-gray-500 font-medium">ID Pembayaran: <strong class="text-gray-900">#{{ $pembayaran->id }}</strong></span>
        <a href="{{ route('pembayaran.lihat') }}" class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
            ← Kembali ke Daftar Pembayaran
        </a>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <div class="rounded-2xl border border-blue-100 bg-blue-50 p-5 shadow-sm">
            <p class="text-sm font-medium text-brand-500">Total Pembayaran</p>
            <p class="mt-2 text-2xl font-bold text-brand-500">
                Rp {{ number_format($pembayaran->total_pembayaran, 0, ',', '.') }}
            </p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Status Pembayaran</p>
            <div class="mt-3">
                <x-status :value="$pembayaran->status"></x-status>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="flex items-center text-base font-bold text-brand-500 mb-5 uppercase">
            <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 640 640">
                <path fill="currentColor" d="M88 289.6L64.4 360.2L64.4 160C64.4 124.7 93.1 96 128.4 96L267.1 96C280.9 96 294.4 100.5 305.5 108.8L343.9 137.6C349.4 141.8 356.2 144 363.1 144L480.4 144C515.7 144 544.4 172.7 544.4 208L544.4 224L179 224C137.7 224 101 250.4 87.9 289.6zM509.8 512L131 512C98.2 512 75.1 479.9 85.5 448.8L133.5 304.8C140 285.2 158.4 272 179 272L557.8 272C590.6 272 613.7 304.1 603.3 335.2L555.3 479.2C548.8 498.8 530.4 512 509.8 512z" />
            </svg>
            <h3 class="ml-2">Informasi Proyek</h3>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 mb-4">
            <div>
                <p class="text-sm font-medium text-gray-500">Nama Proyek</p>
                <p class="mt-1 text-sm text-gray-900 font-medium">{{ $proyek->nama_proyek ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Sub Proyek</p>
                <p class="mt-1 text-sm text-gray-900 font-medium">{{ $sub->nama_sub_proyek ?? '-' }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm font-medium text-gray-500">Sub Sub Proyek</p>
                <div class="mt-1 text-sm text-gray-900 flex items-center gap-1 font-medium">
                    <p>{{ $subsub->nama_halaman ?? '-' }}</p>
                    <span class="text-gray-500">
                        ({{ $pengambilan->dari_halaman ?? '-' }} - {{ $pengambilan->sampai_halaman ?? '-' }}) Halaman
                    </span>
                </div>
            </div>
        </div>
        
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between rounded-xl border border-dashed border-gray-300 bg-gray-50 p-4">
            <div>
                <p class="text-sm font-medium text-gray-800">File tugas freelancer</p>
                <p class="text-sm text-gray-500">Unduh file hasil pekerjaan untuk verifikasi berkas.</p>
            </div>
            @if(isset($pengambilan->id))
                <a href="{{ url('/pembayaran/' . $pengambilan->id . '/download/hasil') }}"
                    target="_blank"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-100">
                    Download File Hasil Tugas
                </a>
            @else
                <button disabled class="pointer-events-none opacity-50 inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700">
                    File Tidak Tersedia
                </button>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">

        <div class="xl:col-span-2 space-y-6">
            
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex items-center text-base font-bold text-brand-500 mb-5 uppercase">
                    <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 640 640">
                        <path fill="currentColor" d="M88 289.6L64.4 360.2L64.4 160C64.4 124.7 93.1 96 128.4 96L267.1 96C280.9 96 294.4 100.5 305.5 108.8L343.9 137.6C349.4 141.8 356.2 144 363.1 144L480.4 144C515.7 144 544.4 172.7 544.4 208L544.4 224L179 224C137.7 224 101 250.4 87.9 289.6zM509.8 512L131 512C98.2 512 75.1 479.9 85.5 448.8L133.5 304.8C140 285.2 158.4 272 179 272L557.8 272C590.6 272 613.7 304.1 603.3 335.2L555.3 479.2C548.8 498.8 530.4 512 509.8 512z" />
                    </svg>
                    <h3 class="ml-2">Rincian Nilai Skor per Aspek</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @if(isset($penilaian->skor) && is_array($penilaian->skor))
                        @foreach($penilaian->skor as $aspek => $nilai)
                            <div class="p-4 bg-slate-50/50 rounded-xl border border-slate-200/60 flex flex-col justify-between">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                        {{ str_replace('_', ' ', $aspek) }}
                                    </span>
                                    <span class="text-sm font-extrabold {{ $nilai >= 8 ? 'text-emerald-600' : ($nilai >= 6 ? 'text-amber-500' : 'text-red-500') }}">
                                        {{ $nilai }}/10
                                    </span>
                                </div>
                                
                                @if(isset($penilaian->catatan[$aspek]) && !empty($penilaian->catatan[$aspek]))
                                    <p class="text-[11px] text-slate-600 mt-3 bg-white p-2 rounded-lg border border-slate-150 leading-relaxed font-medium">
                                        <strong class="text-gray-400 block mb-0.5 text-[10px]">CATATAN:</strong>
                                        {{ $penilaian->catatan[$aspek] }}
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <p class="text-sm text-gray-500 col-span-full italic">Data rincian nilai tidak tersedia.</p>
                    @endif
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex items-center text-base font-bold text-brand-500 mb-5 uppercase">
                    <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 640 640">
                        <path fill="currentColor" d="M320 312C386.3 312 440 258.3 440 192C440 125.7 386.3 72 320 72C253.7 72 200 125.7 200 192C200 258.3 253.7 312 320 312zM290.3 368C191.8 368 112 447.8 112 546.3C112 562.7 125.3 576 141.7 576L498.3 576C514.7 576 528 562.7 528 546.3C528 447.8 448.2 368 349.7 368L290.3 368z" />
                    </svg>
                    <h3 class="ml-2">Informasi Freelancer</h3>
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Nama Freelancer</p>
                        <p class="mt-1 text-sm text-gray-900 font-semibold">{{ $user->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Level Freelancer</p>
                        <p class="mt-1 text-sm text-brand-600 font-semibold">{{ $level->nama_level ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex items-center text-base font-bold text-brand-500 mb-5 uppercase">
                    <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 640 640">
                        <path fill="currentColor" d="M512 176C520.8 176 528 183.2 528 192L528 224L112 224L112 192C112 183.2 119.2 176 128 176L512 176zM528 288L528 448C528 456.8 520.8 464 512 464L128 464C119.2 464 112 456.8 112 448L112 288L528 288zM128 128C92.7 128 64 156.7 64 192L64 448C64 483.3 92.7 512 128 512L512 512C547.3 512 576 483.3 576 448L576 192C576 156.7 547.3 128 512 128L128 128zM144 408C144 421.3 154.7 432 168 432L216 432C229.3 432 240 421.3 240 408C240 394.7 229.3 384 216 384L168 384C154.7 384 144 394.7 144 408zM288 408C288 421.3 298.7 432 312 432L376 432C389.3 432 400 421.3 400 408C400 394.7 389.3 384 376 384L312 384C298.7 384 288 394.7 288 408z" />
                    </svg>
                    <h3 class="ml-2">Informasi Rekening Tujuan</h3>
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Nama Bank</p>
                        <p class="mt-1 text-blue-600 font-bold text-sm uppercase">{{ $rekening->nama_bank ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Nomor Rekening</p>
                        <p class="mt-1 text-sm text-gray-900 font-bold tracking-wider break-all">{{ $rekening->no_akun ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Nama Pemilik Rekening</p>
                        <p class="mt-1 text-sm text-gray-900 font-medium">{{ $rekening->nama_akun ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Bukti Transfer</h3>

                @if(!$pembayaran->bukti_transfer)
                    <form action="{{ route('pembayaran.update') }}" method="post" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <input type="hidden" name="id" value="{{ $pembayaran->id }}">

                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Upload bukti transfer bank
                            </label>
                            <input
                                type="file"
                                name="bukti_transfer"
                                accept="image/*,application/pdf"
                                @change="
                                    let file = $event.target.files[0];
                                    if (file) {
                                        paymentProof = URL.createObjectURL(file);
                                    }
                                "
                                class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 file:mr-4 file:rounded-md file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-brand-500 hover:file:bg-blue-100">
                        </div>

                        <template x-if="paymentProof">
                            <div class="rounded-xl border border-gray-200 bg-gray-50 p-3">
                                <p class="mb-2 text-sm font-medium text-gray-700">Preview</p>
                                <img :src="paymentProof" class="max-h-72 w-full rounded-lg border object-contain bg-white">
                            </div>
                        </template>

                        <button
                            type="submit"
                            class="inline-flex w-full items-center justify-center rounded-lg bg-brand-500 px-4 py-3 text-sm font-semibold text-white transition hover:bg-brand-600">
                            Konfirmasi Pembayaran
                        </button>
                    </form>
                @else
                    <div class="space-y-4">
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-3">
                            <p class="mb-2 text-sm font-medium text-gray-700">Preview Bukti Transfer</p>
                            @if(Str::endsWith($pembayaran->bukti_transfer, '.pdf'))
                                <a href="{{ asset('storage/' . $pembayaran->bukti_transfer) }}" target="_blank" class="block text-center p-6 bg-white border rounded-lg text-brand-500 font-semibold underline text-sm">
                                    Lihat Dokumen PDF Bukti Transfer
                                </a>
                            @else
                                <img
                                    src="{{ asset('storage/' . $pembayaran->bukti_transfer) }}"
                                    class="max-h-80 w-full rounded-lg border object-contain bg-white">
                            @endif
                        </div>

                        <div class="rounded-xl border border-green-200 bg-green-50 p-4">
                            <p class="text-sm font-semibold text-green-700">Pembayaran telah dikonfirmasi</p>
                            <p class="mt-1 text-sm text-green-600">
                                Bukti transfer sudah tersedia dan tersimpan aman pada sistem database.
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 shadow-sm">
                <h4 class="text-sm font-semibold text-amber-800">Catatan Verifikasi Keuangan</h4>
                <p class="mt-2 text-sm leading-6 text-amber-700">
                    Pastikan nominal pembayaran sebesar <strong class="underline">Rp {{ number_format($pembayaran->total_pembayaran, 0, ',', '.') }}</strong> dikirim ke rekening bank tujuan yang tertera di sebelah kiri sebelum Anda mengunggah berkas bukti transaksi ini.
                </p>
            </div>
        </div>
    </div>
</div>

@endsection