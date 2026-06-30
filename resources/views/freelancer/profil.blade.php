@extends('layouts.body', ['title' => 'Dashboard'])

@section('content')
<x-header
    :judul="'Profil Saya'"
    :subjudul="'Informasi akun, progres level, statistik performa, dan riwayat poin Anda'" />

{{-- Hero Profile --}}
<div class="relative overflow-visible rounded-3xl bg-linear-to-r from-brand-500 via-brand-700 to-brand-500 p-6 sm:p-8 text-white shadow-lg mb-8">

    <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
        <div class="flex items-center gap-5">
            <div class="h-24 w-24 rounded-2xl bg-white/10 backdrop-blur flex items-center justify-center ring-1 ring-white/20 shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 text-white" viewBox="0 0 640 640" fill="currentColor">
                    <path d="M463 448.2C440.9 409.8 399.4 384 352 384L288 384C240.6 384 199.1 409.8 177 448.2C212.2 487.4 263.2 512 320 512C376.8 512 427.8 487.3 463 448.2zM64 320C64 178.6 178.6 64 320 64C461.4 64 576 178.6 576 320C576 461.4 461.4 576 320 576C178.6 576 64 461.4 64 320zM320 336C359.8 336 392 303.8 392 264C392 224.2 359.8 192 320 192C280.2 192 248 224.2 248 264C248 303.8 280.2 336 320 336z" />
                </svg>
            </div>

            <div>
                <h2 class="text-2xl font-bold">{{ $user->name }}</h2>
                <p class="mt-1 text-blue-100 text-sm">{{ $user->email }}</p>

                <div class="mt-3 inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-sm font-medium backdrop-blur ring-1 ring-white/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-300" viewBox="0 0 640 640" fill="currentColor">
                        <path d="M320.3 192L235.7 51.1C229.2 40.3 215.6 36.4 204.4 42L117.8 85.3C105.9 91.2 101.1 105.6 107 117.5L176.6 256.6C146.5 290.5 128.3 335.1 128.3 384C128.3 490 214.3 576 320.3 576C426.3 576 512.3 490 512.3 384C512.3 335.1 494 290.5 464 256.6L533.6 117.5C539.5 105.6 534.7 91.2 522.9 85.3L436.2 41.9C425 36.3 411.3 40.3 404.9 51L320.3 192zM351.1 334.5C352.5 337.3 355.1 339.2 358.1 339.6L408.2 346.9C415.9 348 418.9 357.4 413.4 362.9L377.1 398.3C374.9 400.5 373.9 403.5 374.4 406.6L383 456.5C384.3 464.1 376.3 470 369.4 466.4L324.6 442.8C321.9 441.4 318.6 441.4 315.9 442.8L271.1 466.4C264.2 470 256.2 464.2 257.5 456.5L266.1 406.6C266.6 403.6 265.6 400.5 263.4 398.3L227.1 362.9C221.5 357.5 224.6 348.1 232.3 346.9L282.4 339.6C285.4 339.2 288.1 337.2 289.4 334.5L311.8 289.1C315.2 282.1 325.1 282.1 328.6 289.1L351 334.5z" />
                    </svg>
                    <span>Level {{ $user->level->nama_level ?? '-' }}</span>
                </div>
            </div>
        </div>

        {{-- Summary Stats + Dropdown Update Profil --}}
        <div>
            <div x-data="{
                profil: false,
                modal: false,
                pilihOpsi: null,
                lihatPasswordLama : false,
                lihatPasswordBaru : false,
                lihatKonfirmasiPassword : false,
                passwordLama : '',
                passwordBaru : '',
                konfirmasiPassword : '',
                portoTipe: '{{ old('type', $user->portofolio?->type ?? 'link') }}'
            }" class="relative flex justify-end p-2">

                <!-- BUTTON -->
                <button
                    @click="profil = !profil"
                    class="text-white hover:bg-white/20 p-2 rounded-lg transition"
                    type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" height="25" width="25" viewBox="0 0 640 640">
                        <path fill="currentColor" d="M320 208C289.1 208 264 182.9 264 152C264 121.1 289.1 96 320 96C350.9 96 376 121.1 376 152C376 182.9 350.9 208 320 208zM320 432C350.9 432 376 457.1 376 488C376 518.9 350.9 544 320 544C289.1 544 264 518.9 264 488C264 457.1 289.1 432 320 432zM376 320C376 350.9 350.9 376 320 376C289.1 376 264 350.9 264 320C264 289.1 289.1 264 320 264C350.9 264 376 289.1 376 320z" />
                    </svg>
                </button>

                <!-- DROPDOWN -->
                <div
                    x-show="profil"
                    @click.outside="profil = false"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 top-full mt-2 w-48 bg-white text-gray-700 rounded-xl shadow-xl overflow-hidden z-50">

                    <p class="block px-4 py-2 border-b text-center text-white bg-brand-500 font-medium">Update Profil</p>
                    <button @click="modal=true; pilihOpsi='telepon';" type="button" class="block px-4 py-2 hover:bg-brand-100 w-full text-start">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 640 640"><!--!Font Awesome Free v7.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                                <path fill="currentColor" d="M144 128C144 92.7 172.7 64 208 64L432 64C467.3 64 496 92.7 496 128L496 512C496 547.3 467.3 576 432 576L208 576C172.7 576 144 547.3 144 512L144 128zM208 128L208 432L432 432L432 128L208 128zM320 536C337.7 536 352 521.7 352 504C352 486.3 337.7 472 320 472C302.3 472 288 486.3 288 504C288 521.7 302.3 536 320 536z" />
                            </svg>
                            Telepon
                        </div>
                    </button>
                    <button @click="modal=true; pilihOpsi='password';" type="button" class="block px-4 py-2 hover:bg-brand-100 w-full text-start">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 640 640"><!--!Font Awesome Free v7.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                                <path fill="currentColor" d="M400 416C497.2 416 576 337.2 576 240C576 142.8 497.2 64 400 64C302.8 64 224 142.8 224 240C224 258.7 226.9 276.8 232.3 293.7L71 455C66.5 459.5 64 465.6 64 472L64 552C64 565.3 74.7 576 88 576L168 576C181.3 576 192 565.3 192 552L192 512L232 512C245.3 512 256 501.3 256 488L256 448L296 448C302.4 448 308.5 445.5 313 441L346.3 407.7C363.2 413.1 381.3 416 400 416zM440 160C462.1 160 480 177.9 480 200C480 222.1 462.1 240 440 240C417.9 240 400 222.1 400 200C400 177.9 417.9 160 440 160z" />
                            </svg>
                            Password
                        </div>
                    </button>
                    <button @click="modal=true; pilihOpsi='rekening';" type="button" class="block px-4 py-2 hover:bg-brand-100 w-full text-start">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 640 640"><!--!Font Awesome Free v7.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                                <path fill="currentColor" d="M335.9 84.2C326.1 78.6 314 78.6 304.1 84.2L80.1 212.2C67.5 219.4 61.3 234.2 65 248.2C68.7 262.2 81.5 272 96 272L128 272L128 480L128 480L76.8 518.4C68.7 524.4 64 533.9 64 544C64 561.7 78.3 576 96 576L544 576C561.7 576 576 561.7 576 544C576 533.9 571.3 524.4 563.2 518.4L512 480L512 272L544 272C558.5 272 571.2 262.2 574.9 248.2C578.6 234.2 572.4 219.4 559.8 212.2L335.8 84.2zM464 272L464 480L400 480L400 272L464 272zM352 272L352 480L288 480L288 272L352 272zM240 272L240 480L176 480L176 272L240 272zM320 160C337.7 160 352 174.3 352 192C352 209.7 337.7 224 320 224C302.3 224 288 209.7 288 192C288 174.3 302.3 160 320 160z" />
                            </svg>
                            Rekening
                        </div>
                    </button>
                    <button @click="modal=true; pilihOpsi='portofolio';" type="button" class="block px-4 py-2 hover:bg-brand-100 w-full text-start">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 640 640"><!--!Font Awesome Free v7.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                                <path fill="currentColor" d="M192 64C156.7 64 128 92.7 128 128L128 512C128 547.3 156.7 576 192 576L448 576C483.3 576 512 547.3 512 512L512 128C512 92.7 483.3 64 448 64L192 64zM288 416L352 416C396.2 416 432 451.8 432 496C432 504.8 424.8 512 416 512L224 512C215.2 512 208 504.8 208 496C208 451.8 243.8 416 288 416zM264 320C264 289.1 289.1 264 320 264C350.9 264 376 289.1 376 320C376 350.9 350.9 376 320 376C289.1 376 264 350.9 264 320zM280 128L360 128C373.3 128 384 138.7 384 152C384 165.3 373.3 176 360 176L280 176C266.7 176 256 165.3 256 152C256 138.7 266.7 128 280 128z" />
                            </svg>
                            Portofolio
                        </div>
                    </button>

                    <!-- MODAL -->
                    <div
                        x-show="modal"
                        x-transition
                        class="fixed inset-0 z-50 flex items-center justify-center p-4">

                        <!-- BACKDROP -->
                        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="modal = false"></div>

                        <!-- CONTENT -->
                        <div class="relative bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden">

                            <!-- HEADER -->
                            <div class="bg-linear-to-r from-brand-500 to-brand-700 px-6 py-5 text-white flex items-center justify-between">
                                <div>
                                    <h2 class="text-xl font-bold">Pengaturan Profil</h2>
                                    <p class="text-sm text-blue-100 mt-1">Kelola informasi akun anda</p>
                                </div>
                                <button @click="modal = false" class="h-10 w-10 rounded-full hover:bg-white/20 flex items-center justify-center transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <!-- TABS -->
                            <div class="flex border-b bg-gray-50 overflow-x-auto">
                                <button type="button" @click="pilihOpsi='telepon'"
                                    :class="pilihOpsi === 'telepon' ? 'bg-white text-brand-500 border-b-2 border-brand-500' : 'text-gray-500 hover:text-brand-500'"
                                    class="flex-1 py-3 text-sm font-medium whitespace-nowrap px-3">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 640 640"><!--!Font Awesome Free v7.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                                            <path fill="currentColor" d="M144 128C144 92.7 172.7 64 208 64L432 64C467.3 64 496 92.7 496 128L496 512C496 547.3 467.3 576 432 576L208 576C172.7 576 144 547.3 144 512L144 128zM208 128L208 432L432 432L432 128L208 128zM320 536C337.7 536 352 521.7 352 504C352 486.3 337.7 472 320 472C302.3 472 288 486.3 288 504C288 521.7 302.3 536 320 536z" />
                                        </svg>
                                        Telepon
                                    </div>
                                </button>
                                <button type="button" @click="pilihOpsi='password'"
                                    :class="pilihOpsi === 'password' ? 'bg-white text-brand-500 border-b-2 border-brand-500' : 'text-gray-500 hover:text-brand-500'"
                                    class="flex-1 py-3 text-sm font-medium whitespace-nowrap px-3">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 640 640"><!--!Font Awesome Free v7.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                                            <path fill="currentColor" d="M400 416C497.2 416 576 337.2 576 240C576 142.8 497.2 64 400 64C302.8 64 224 142.8 224 240C224 258.7 226.9 276.8 232.3 293.7L71 455C66.5 459.5 64 465.6 64 472L64 552C64 565.3 74.7 576 88 576L168 576C181.3 576 192 565.3 192 552L192 512L232 512C245.3 512 256 501.3 256 488L256 448L296 448C302.4 448 308.5 445.5 313 441L346.3 407.7C363.2 413.1 381.3 416 400 416zM440 160C462.1 160 480 177.9 480 200C480 222.1 462.1 240 440 240C417.9 240 400 222.1 400 200C400 177.9 417.9 160 440 160z" />
                                        </svg>
                                        Password
                                    </div>
                                </button>
                                <button type="button" @click="pilihOpsi='rekening'"
                                    :class="pilihOpsi === 'rekening' ? 'bg-white text-brand-500 border-b-2 border-brand-500' : 'text-gray-500 hover:text-brand-500'"
                                    class="flex-1 py-3 text-sm font-medium whitespace-nowrap px-3">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 640 640"><!--!Font Awesome Free v7.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                                            <path fill="currentColor" d="M335.9 84.2C326.1 78.6 314 78.6 304.1 84.2L80.1 212.2C67.5 219.4 61.3 234.2 65 248.2C68.7 262.2 81.5 272 96 272L128 272L128 480L128 480L76.8 518.4C68.7 524.4 64 533.9 64 544C64 561.7 78.3 576 96 576L544 576C561.7 576 576 561.7 576 544C576 533.9 571.3 524.4 563.2 518.4L512 480L512 272L544 272C558.5 272 571.2 262.2 574.9 248.2C578.6 234.2 572.4 219.4 559.8 212.2L335.8 84.2zM464 272L464 480L400 480L400 272L464 272zM352 272L352 480L288 480L288 272L352 272zM240 272L240 480L176 480L176 272L240 272zM320 160C337.7 160 352 174.3 352 192C352 209.7 337.7 224 320 224C302.3 224 288 209.7 288 192C288 174.3 302.3 160 320 160z" />
                                        </svg>
                                        Rekening
                                    </div>
                                </button>
                                <button type="button" @click="pilihOpsi='portofolio'"
                                    :class="pilihOpsi === 'portofolio' ? 'bg-white text-brand-500 border-b-2 border-brand-500' : 'text-gray-500 hover:text-brand-500'"
                                    class="flex-1 py-3 text-sm font-medium whitespace-nowrap px-3">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 640 640"><!--!Font Awesome Free v7.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                                            <path fill="currentColor" d="M192 64C156.7 64 128 92.7 128 128L128 512C128 547.3 156.7 576 192 576L448 576C483.3 576 512 547.3 512 512L512 128C512 92.7 483.3 64 448 64L192 64zM288 416L352 416C396.2 416 432 451.8 432 496C432 504.8 424.8 512 416 512L224 512C215.2 512 208 504.8 208 496C208 451.8 243.8 416 288 416zM264 320C264 289.1 289.1 264 320 264C350.9 264 376 289.1 376 320C376 350.9 350.9 376 320 376C289.1 376 264 350.9 264 320zM280 128L360 128C373.3 128 384 138.7 384 152C384 165.3 373.3 176 360 176L280 176C266.7 176 256 165.3 256 152C256 138.7 266.7 128 280 128z" />
                                        </svg>
                                        Portofolio
                                    </div>
                                </button>
                            </div>

                            <div class="p-6 max-h-[60vh] overflow-y-auto">

                                <!-- TAB: TELEPON -->
                                <div x-show="pilihOpsi === 'telepon'">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-1">Ubah Nomor Telepon</h3>
                                    <p class="text-sm text-gray-500 mb-5">Pastikan nomor yang dimasukkan aktif.</p>
                                    <form action="{{ route('profil.updateTelepon', $user->id) }}" method="post">
                                        @csrf
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon Baru</label>
                                            <input type="text" name="no_telp"
                                                value="{{ old('no_telp', $user->no_telp) }}"
                                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-700 focus:outline-none"
                                                placeholder="08xxxxxxxxxx">
                                            @error('no_telp')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <x-primary-button type="submit" full>Simpan Nomor</x-primary-button>
                                    </form>
                                </div>

                                <!-- TAB: PASSWORD -->
                                <div x-show="pilihOpsi === 'password'">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-1">Ubah Password</h3>
                                    <p class="text-sm text-gray-500 mb-5">Gunakan password yang kuat dan aman.</p>
                                    <form action="{{ route('profil.updatePassword', $user->id) }}" method="post">
                                        @csrf
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Password Lama</label>
                                                <div class="relative">
                                                    <input :type="lihatPasswordLama ? 'text' : 'password'" name="password_lama" required x-model="passwordLama"
                                                        class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-700 focus:outline-none" placeholder="••••••••" />
                                                    <button type="button" class="absolute right-3 top-3 text-gray-400" @click="lihatPasswordLama = !lihatPasswordLama">
                                                        <i :class="lihatPasswordLama ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                                    </button>
                                                </div>
                                                @error('password_lama')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                                                <div class="relative">
                                                    <input :type="lihatPasswordBaru ? 'text' : 'password'" name="password" required x-model="passwordBaru"
                                                        class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-700 focus:outline-none" placeholder="••••••••" />
                                                    <button type="button" class="absolute right-3 top-3 text-gray-400" @click="lihatPasswordBaru = !lihatPasswordBaru">
                                                        <i :class="lihatPasswordBaru ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                                                <div class="relative">
                                                    <input :type="lihatKonfirmasiPassword ? 'text' : 'password'" name="password_confirmation" required x-model="konfirmasiPassword"
                                                        class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-700 focus:outline-none" placeholder="••••••••" />
                                                    <button type="button" class="absolute right-3 top-3 text-gray-400" @click="lihatKonfirmasiPassword = !lihatKonfirmasiPassword">
                                                        <i :class="lihatKonfirmasiPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                                    </button>
                                                </div>
                                                @error('password')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <x-primary-button type="submit" full>Update Password</x-primary-button>
                                        </div>
                                    </form>
                                </div>

                                <!-- TAB: REKENING -->
                                <div x-show="pilihOpsi === 'rekening'">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-1">Data Rekening Bank</h3>
                                    <p class="text-sm text-gray-500 mb-5">Rekening digunakan untuk pencairan pembayaran.</p>
                                    <form action="{{ route('profil.updateRekening', $user->id) }}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Bank</label>
                                                <input type="text" name="nama_bank"
                                                    value="{{ old('nama_bank', $user->rekening?->nama_bank) }}"
                                                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-700 focus:outline-none"
                                                    placeholder="Contoh: BCA, Mandiri, BRI">
                                                @error('nama_bank')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Rekening</label>
                                                <input type="text" name="no_akun"
                                                    value="{{ old('no_akun', $user->rekening?->no_akun) }}"
                                                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-700 focus:outline-none"
                                                    placeholder="Contoh: 1234567890">
                                                @error('no_akun')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pemilik Rekening</label>
                                                <input type="text" name="nama_akun"
                                                    value="{{ old('nama_akun', $user->rekening?->nama_akun) }}"
                                                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-700 focus:outline-none"
                                                    placeholder="Sesuai nama di buku tabungan">
                                                @error('nama_akun')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <x-primary-button type="submit" full>Simpan Rekening</x-primary-button>
                                        </div>
                                    </form>
                                </div>

                                <!-- TAB: PORTOFOLIO -->
                                <div x-show="pilihOpsi === 'portofolio'">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-1">Update Portofolio</h3>
                                    <p class="text-sm text-gray-500 mb-5">Perbarui file atau tautan portofolio Anda. Data lama akan digantikan.</p>

                                    {{-- Info portofolio saat ini --}}
                                    @if ($user->portofolio)
                                    <div class="mb-5 rounded-xl border border-blue-100 bg-blue-50 p-3 flex items-center gap-3">
                                        @if ($user->portofolio->type === 'link')
                                        <span class="text-xl">🔗</span>
                                        <div class="min-w-0">
                                            <p class="text-xs text-brand-500 font-medium">Portofolio saat ini (Link)</p>
                                            <p class="text-sm text-blue-800 truncate">{{ $user->portofolio->link_url }}</p>
                                        </div>
                                        @else
                                        <span class="text-xl">📄</span>
                                        <div class="min-w-0">
                                            <p class="text-xs text-brand-500 font-medium">Portofolio saat ini (File)</p>
                                            <p class="text-sm text-blue-800 truncate">{{ basename($user->portofolio->file_path) }}</p>
                                        </div>
                                        @endif
                                    </div>
                                    @endif

                                    <form action="{{ route('profil.updatePortofolio', $user->id) }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="space-y-4">
                                            <!-- Pilih tipe -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Portofolio Baru</label>
                                                <div class="flex gap-3">
                                                    <label class="flex-1 cursor-pointer">
                                                        <input type="radio" name="type" value="link" x-model="portoTipe" class="sr-only"
                                                            {{ old('type', $user->portofolio?->type ?? 'link') === 'link' ? 'checked' : '' }}>
                                                        <div :class="portoTipe === 'link' ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-gray-200 text-gray-600'"
                                                            class="border-2 rounded-xl p-3 text-center transition">
                                                            <p class="text-lg">🔗</p>
                                                            <p class="text-sm font-medium mt-1">Link URL</p>
                                                        </div>
                                                    </label>
                                                    <label class="flex-1 cursor-pointer">
                                                        <input type="radio" name="type" value="file" x-model="portoTipe" class="sr-only"
                                                            {{ old('type', $user->portofolio?->type) === 'file' ? 'checked' : '' }}>
                                                        <div :class="portoTipe === 'file' ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-gray-200 text-gray-600'"
                                                            class="border-2 rounded-xl p-3 text-center transition">
                                                            <p class="text-lg">📄</p>
                                                            <p class="text-sm font-medium mt-1">Upload File</p>
                                                        </div>
                                                    </label>
                                                </div>
                                                @error('type')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <!-- Input Link -->
                                            <div x-show="portoTipe === 'link'">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">URL Portofolio</label>
                                                <input type="url" name="link_url"
                                                    value="{{ old('link_url', $user->portofolio?->type === 'link' ? $user->portofolio->link_url : '') }}"
                                                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-700 focus:outline-none"
                                                    placeholder="https://github.com/username/project">
                                                @error('link_url')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <!-- Input File -->
                                            <div x-show="portoTipe === 'file'">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">File Portofolio Baru</label>
                                                <input type="file" name="file_path" accept=".pdf,.jpg,.jpeg,.png"
                                                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-700 focus:outline-none text-sm">
                                                <p class="text-xs text-gray-400 mt-1">Format: PDF, JPG, PNG. Maks 5 MB. File lama akan dihapus.</p>
                                                @error('file_path')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <x-primary-button type="submit" full>Simpan Portofolio</x-primary-button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 w-full lg:w-auto">
                <div class="rounded-2xl bg-white/10 backdrop-blur p-4 ring-1 ring-white/15">
                    <p class="text-sm text-blue-100">Total Proyek</p>
                    <p class="mt-2 text-3xl font-bold">{{ $totalProyek }}</p>
                </div>
                <div class="rounded-2xl bg-white/10 backdrop-blur p-4 ring-1 ring-white/15">
                    <p class="text-sm text-blue-100">Proyek Selesai</p>
                    <p class="mt-2 text-3xl font-bold">{{ $totalProyekSelesai }}</p>
                </div>
                <div class="rounded-2xl bg-white/10 backdrop-blur p-4 ring-1 ring-white/15">
                    <p class="text-sm text-blue-100">Total Pendapatan</p>
                    <p class="mt-2 text-3xl font-bold">
                        Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Main Content --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    {{-- Left Content --}}
    <div class="xl:col-span-2 space-y-6">
        {{-- Progress Level --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-lg font-semibold text-slate-800">Progress Level</h2>
                    <p class="text-sm text-slate-500 mt-1">Pantau perkembangan poin dan level akun Anda.</p>
                </div>
                <div class="rounded-full bg-brand-50 px-3 py-1 text-sm font-medium text-brand-500">
                    {{ $levelSelanjutnya ? 'Menuju Level Berikutnya' : 'Level Maksimal' }}
                </div>
            </div>

            <div class="mb-4 flex items-center justify-between text-sm">
                <span class="font-medium text-slate-700">Level {{ $levelSekarang->nama_level ?? '-' }}</span>
                <span class="font-medium text-slate-500">
                    {{ $levelSelanjutnya ? 'Level ' . $levelSelanjutnya->nama_level : 'MAX' }}
                </span>
            </div>

            <div class="w-full rounded-full bg-slate-200 h-4 overflow-hidden">
                <div class="h-4 rounded-full bg-linear-to-r from-brand-500 to-brand-700 transition-all duration-500"
                    style="width: {{ $progress }}%">
                </div>
            </div>

            <div class="mt-3 text-sm text-slate-500">
                @if ($levelSelanjutnya)
                {{ $user->poin_level }} / {{ $poinSelanjutnya }} poin menuju level berikutnya
                @else
                Level tertinggi telah tercapai
                @endif
            </div>

            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="rounded-2xl bg-blue-50 border border-blue-100 p-5">
                    <p class="text-sm text-blue-700">Total Poin</p>
                    <p class="mt-2 text-3xl font-bold text-blue-700">{{ $user->poin_level }} poin</p>
                </div>
                <div class="rounded-2xl bg-slate-50 border border-slate-200 p-5">
                    <p class="text-sm text-slate-500">Status Level</p>
                    <p class="mt-2 text-xl font-semibold text-slate-800">
                        {{ $levelSelanjutnya ? 'Masih berkembang' : 'Level tertinggi' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Statistik Performa --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-slate-800">Statistik Performa</h2>
                <p class="text-sm text-slate-500 mt-1">Ringkasan performa kerja dan data akun pembayaran Anda.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="rounded-2xl bg-slate-50 border border-slate-200 p-5">
                    <p class="text-sm text-slate-500">Rata-rata Skor</p>
                    <p class="mt-2 text-3xl font-bold text-blue-700">{{ $rataRataSkor }}/30</p>
                </div>

                <div class="rounded-2xl bg-slate-50 border border-slate-200 p-5">
                    <p class="text-sm text-slate-500">Tingkat Keberhasilan</p>
                    <p class="mt-2 text-3xl font-bold text-emerald-600">{{ $tingkatKeberhasilan }}%</p>
                </div>

                <div class="rounded-2xl bg-slate-50 border border-slate-200 p-5">
                    <p class="text-sm text-slate-500">No. Rekening</p>
                    <p class="mt-2 text-lg font-semibold text-slate-800">
                        {{ $user->rekening?->no_akun ?? '-' }}
                    </p>
                    <p class="mt-1 text-sm text-slate-500">
                        {{ $user->rekening?->nama_bank ?? '' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Portofolio --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-semibold text-slate-800">Portofolio</h2>
                    <p class="text-sm text-slate-500 mt-1">Karya atau tautan portofolio Anda.</p>
                </div>
            </div>

            @if ($user->portofolio)
            <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                @if ($user->portofolio->type === 'link')
                <div class="h-12 w-12 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium text-slate-700">Link Portofolio</p>
                    <a href="{{ $user->portofolio->link_url }}" target="_blank"
                        class="text-sm text-brand-500 hover:underline truncate block">
                        {{ $user->portofolio->link_url }}
                    </a>
                    <p class="text-xs text-slate-400 mt-1">Diperbarui {{ $user->portofolio->updated_at->format('d M Y') }}</p>
                </div>
                <a href="{{ $user->portofolio->link_url }}" target="_blank"
                    class="shrink-0 h-9 w-9 rounded-xl bg-blue-50 hover:bg-blue-100 flex items-center justify-center text-brand-500 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
                @else
                <div class="h-12 w-12 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium text-slate-700">File Portofolio</p>
                    <p class="text-sm text-slate-600 truncate">{{ basename($user->portofolio->file_path) }}</p>
                    <p class="text-xs text-slate-400 mt-1">Diperbarui {{ $user->portofolio->updated_at->format('d M Y') }}</p>
                </div>
                <a href="{{ asset('storage/' . $user->portofolio->file_path) }}" target="_blank"
                    class="shrink-0 h-9 w-9 rounded-xl bg-emerald-50 hover:bg-emerald-100 flex items-center justify-center text-emerald-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                </a>
                @endif
            </div>
            @else
            <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
                <p class="text-4xl mb-3">💼</p>
                <p class="text-slate-600 font-medium">Belum ada portofolio</p>
                <p class="text-slate-400 text-sm mt-1">Tambahkan portofolio melalui menu pengaturan di atas.</p>
            </div>
            @endif
        </div>

        {{-- Log Poin --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div x-data="{ info : false}" class="relative mb-6 justify-between flex">
                <div>
                    <h2 class="text-lg font-semibold text-slate-800">Log Poin</h2>
                    <p class="text-sm text-slate-500 mt-1">Riwayat perubahan poin berdasarkan aktivitas Anda.</p>
                </div>

                <button @click="info = true" type="button">
                    <div class="text-yellow-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 640 640"><!--!Font Awesome Free v7.3.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                            <path fill="currentColor" d="M320 576C461.4 576 576 461.4 576 320C576 178.6 461.4 64 320 64C178.6 64 64 178.6 64 320C64 461.4 178.6 576 320 576zM288 224C288 206.3 302.3 192 320 192C337.7 192 352 206.3 352 224C352 241.7 337.7 256 320 256C302.3 256 288 241.7 288 224zM280 288L328 288C341.3 288 352 298.7 352 312L352 400L360 400C373.3 400 384 410.7 384 424C384 437.3 373.3 448 360 448L280 448C266.7 448 256 437.3 256 424C256 410.7 266.7 400 280 400L304 400L304 336L280 336C266.7 336 256 325.3 256 312C256 298.7 266.7 288 280 288z" />
                        </svg>
                    </div>
                </button>
                <div
                    x-show="info" x-transition class="absolute right-0 mt-4 z-50 flex items-center justify-center p-4">

                    <!-- CONTENT -->
                    <div @click.away="info=false" class="bg-yellow-100 border-2 border-yellow-200 w-min-full max-w-lg rounded-xl shadow-2xl overflow-hidden p-4">
                        <div class="text-[11px] leading-normal">
                            <p class="font-semibold">Poin didapatkan saat anda:</p>
                            <p>- Menyelesaikan tugas yang telah diambil <span class="text-green-600">(Bertambah)</span></p>
                            <p>- Membatalkan tugas yang telah diambil <span class="text-red-600">(Berkurang)</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-4 max-h-75 overflow-y-auto pr-2">
                @forelse ($logpoins as $log)
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <p class="w-full border-b border-gray-200 font-semibold {{ $log->jenis === 'tambah' ? 'text-green-600' : 'text-red-600' }} mb-2">
                        {{ $log->jenis === 'tambah' ? 'Penambahan Poin' : 'Pengurangan Poin' }}
                    </p>
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="font-medium text-slate-900">
                                {{ $log->nama_proyek }}
                            </p>
                            <p class="font-medium text-sm text-slate-500">
                                {{ $log->nama_sub_proyek }} • Halaman {{ $log->dari_halaman }} - {{ $log->sampai_halaman }}
                            </p>

                            <p class="mt-1 text-xs text-slate-500">
                                {{ $log->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold {{ $log->jenis === 'tambah' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $log->jenis === 'tambah' ? '+' : '-' }}
                                {{ $log->jumlah_poin ?? $log->poin ?? 0 }} poin
                            </p>
                        </div>
                    </div>
                </div>
                @empty
                <x-list-empty title="Belum Ada Riwayat Log Poin" subtitle="Semua riwayat log poin akan tampil disini">
                    <x-slot:icon>
                        <svg class="w-10 h-10 text-gray-400" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                            <path fill="currentColor" d="M104 112C90.7 112 80 122.7 80 136L80 184C80 197.3 90.7 208 104 208L152 208C165.3 208 176 197.3 176 184L176 136C176 122.7 165.3 112 152 112L104 112zM256 128C238.3 128 224 142.3 224 160C224 177.7 238.3 192 256 192L544 192C561.7 192 576 177.7 576 160C576 142.3 561.7 128 544 128L256 128zM256 288C238.3 288 224 302.3 224 320C224 337.7 238.3 352 256 352L544 352C561.7 352 576 337.7 576 320C576 302.3 561.7 288 544 288L256 288zM256 448C238.3 448 224 462.3 224 480C224 497.7 238.3 512 256 512L544 512C561.7 512 576 497.7 576 480C576 462.3 561.7 448 544 448L256 448zM80 296L80 344C80 357.3 90.7 368 104 368L152 368C165.3 368 176 357.3 176 344L176 296C176 282.7 165.3 272 152 272L104 272C90.7 272 80 282.7 80 296zM104 432C90.7 432 80 442.7 80 456L80 504C80 517.3 90.7 528 104 528L152 528C165.3 528 176 517.3 176 504L176 456C176 442.7 165.3 432 152 432L104 432z" />
                        </svg>
                    </x-slot:icon>
                </x-list-empty>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Right Sidebar --}}
    <div class="space-y-6">
        {{-- Akun Ringkas --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-800 mb-4">Ringkasan Akun</h2>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-500">Nama</span>
                    <span class="text-sm font-medium text-slate-800">{{ $user->name }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-500">Email</span>
                    <span class="text-sm font-medium text-slate-800 truncate max-w-45">{{ $user->email }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-500">No. Telepon</span>
                    <span class="text-sm font-medium text-slate-800 truncate">{{ $user->no_telp ?? '-' }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-500">Level</span>
                    <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-sm font-medium text-blue-700">
                        {{ $user->level->nama_level ?? '-' }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-500">Poin</span>
                    <span class="text-sm font-semibold text-slate-800">{{ $user->poin_level }} poin</span>
                </div>
            </div>
        </div>

        {{-- Info Rekening --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-800 mb-4">Info Rekening</h2>
            @if ($user->rekening)
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-500">Bank</span>
                    <span class="text-sm font-semibold text-slate-800">{{ $user->rekening->nama_bank }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-500">No. Rekening</span>
                    <span class="text-sm font-semibold text-slate-800">{{ $user->rekening->no_akun }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-500">Atas Nama</span>
                    <span class="text-sm font-semibold text-slate-800 truncate max-w-37.5">{{ $user->rekening->nama_akun }}</span>
                </div>
            </div>
            @else
            <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-4 text-center">
                <p class="text-sm text-slate-500">Belum ada data rekening.</p>
                <p class="text-xs text-slate-400 mt-1">Tambahkan melalui menu pengaturan.</p>
            </div>
            @endif
        </div>

        {{-- Keuntungan Level --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-slate-800">Keuntungan Level</h2>
                <p class="text-sm text-slate-500 mt-1">Semakin tinggi level, semakin besar prioritas Anda.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-1 gap-4">
                @forelse ($levels as $level)
                <div class="rounded-2xl border border-slate-200 p-4 transition hover:shadow-sm hover:border-blue-200">

                    <div class="flex items-center gap-4">

                        <div class="h-12 w-12 rounded-2xl flex items-center justify-center text-white font-bold shadow-sm
                {{ $level->delay_notifikasi == 0 ? 'bg-yellow-500' : 'bg-blue-500' }}">
                            {{ $level->nama_level }}
                        </div>

                        <div>
                            <p class="font-semibold text-slate-800">
                                Level {{ $level->nama_level }}
                            </p>

                            <p class="text-sm text-slate-500">
                                @if($level->delay_notifikasi == 0)
                                Akses proyek instan (real-time)
                                @else
                                Notifikasi tertunda {{ $level->delay_notifikasi }} menit
                                @endif
                            </p>

                            <p class="text-xs text-slate-400 mt-1">
                                Minimal {{ number_format($level->min_poin) }} poin
                            </p>
                        </div>

                    </div>

                </div>
                @empty
                <x-list-empty title="Tidak Ada Threshold Level" subtitle="Semua level akan tampil disini">
                    <x-slot:icon>
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M9 8h6m2 12H7a2 2 0 01-2-2V6a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V18a2 2 0 01-2 2z" />
                        </svg>
                    </x-slot:icon>
                </x-list-empty>
                @endforelse
            </div>

            <div class="mt-6 rounded-2xl border border-yellow-200 bg-yellow-50 p-4">
                <p class="text-sm text-yellow-800">
                    <span class="font-medium">Tip:</span> Semakin tinggi level Anda, semakin awal Anda mendapatkan notifikasi proyek baru dan akses prioritas pengambilan tugas.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection