<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login & Register</title>
    <link rel="icon" type="image/png" href="{{ asset('image/logo-pranala.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" media="print" onload="this.media='all'" />
    <noscript>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </noscript>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="antialiased font-sans">
    <x-alert></x-alert>

    <div x-data="authPage()" class="min-h-screen bg-linear-to-br from-brand-600 via-brand-700 to-brand-900 flex items-center justify-center p-4 sm:p-6 md:p-10">

        <div class="w-full bg-white rounded-3xl shadow-2xl overflow-hidden transition-all duration-500 ease-in-out grid grid-cols-1 md:grid-cols-12"
            :class="isLogin ? 'max-w-4xl' : 'max-w-5xl'">

            <!-- KIRI: BRAND SHOWCASE PANEL (Hanya tampil di tablet & desktop) -->
            <div class="hidden md:flex md:col-span-5 bg-linear-to-tr from-brand-800 to-brand-600 p-10 text-white flex-col justify-between relative overflow-hidden">
                <div class="absolute -top-10 -left-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                <div class="absolute -bottom-20 -right-20 w-60 h-60 bg-brand-500/30 rounded-full blur-3xl"></div>
                <div class="flex items-center space-x-3 z-10">
                    <div class="p-2.5 bg-white backdrop-blur-md rounded-2xl border border-white/20">
                        <img src="{{ asset('image/logo-pranala.png') }}" alt="Logo" width="32" height="32" fetchpriority="high" class="w-8 h-8 object-contain">
                    </div>
                    <div>
                        <h2 class="font-bold text-lg leading-tight tracking-wide">Pranala GigPortal</h2>
                        <p class="text-xs text-brand-200">PT. Pranala Digital Transmaritim</p>
                    </div>
                </div>
                <div class="my-auto py-8 z-10 space-y-6">
                    <h1 class="text-2xl lg:text-3xl font-extrabold leading-tight tracking-tight">
                        Kelola Proyek Dokumen Lebih Cepat di
                        <span class="bg-linear-to-r from-brand-300 to-white bg-clip-text text-transparent">Pranala GigPortal</span>
                    </h1>
                    <p class="text-sm text-brand-100/90 leading-relaxed">
                        Platform modern yang mempertemukan kebutuhan proyek perusahaan dengan keahlian para freelancer profesional secara transparan, aman, dan efisien.
                    </p>
                    <!-- Fitur Unggulan (USP) -->
                    <div class="space-y-4 pt-4">
                        <div class="flex items-start space-x-3">
                            <div class="shrink-0 w-6 h-6 rounded-full bg-white/15 flex items-center justify-center text-xs">
                                <i class="fas fa-laptop-house text-brand-200"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-white">Fleksibilitas & Kemudahan</h4>
                                <p class="text-xs text-brand-200/80">Akses portal dan kerjakan tugas dari mana saja dan kapan saja sesuai dengan kenyamanan serta jadwal Anda.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="shrink-0 w-6 h-6 rounded-full bg-white/15 flex items-center justify-center text-xs">
                                <i class="fas fa-chart-line text-brand-200"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-white">Sistem Poin & Leveling</h4>
                                <p class="text-xs text-brand-200/80">Dapatkan poin dari kualitas kerja untuk menaikkan reputasi level.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="shrink-0 w-6 h-6 rounded-full bg-white/15 flex items-center justify-center text-xs">
                                <i class="fas fa-shield-alt text-brand-200"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-white">Verifikasi & Transparansi</h4>
                                <p class="text-xs text-brand-200/80">Validasi ketat dan transparansi penilaian menjamin keadilan penilaian kualitas dan kepastian upah pekerjaan.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Footer Hak Cipta -->
                <div class="text-xs text-brand-300/60 z-10">
                    &copy; {{ date('Y') }} PT. Digital Pranala Transmaritim.
                </div>
            </div>

            <!-- KANAN: FORM PANEL (LOGIN & REGISTER) -->
            <div class="col-span-1 md:col-span-7 p-6 sm:p-8 lg:p-10 flex flex-col justify-center">

                <!-- Mobile Header (Hanya tampil di mobile) -->
                <div class="flex flex-col items-center text-center mb-6 space-y-2 md:hidden">
                    <img src="{{ asset('image/logo-pranala.png') }}" alt="Logo" width="48" height="48" fetchpriority="high" class="w-12 h-12 object-contain">
                    <div>
                        <h2 class="text-2xl font-bold bg-linear-to-r from-brand-500 to-brand-700 bg-clip-text text-transparent">Pranala GigPortal</h2>
                        <p class="text-xs text-gray-500 font-medium">PT. Digital Pranala Transmaritim</p>
                    </div>
                </div>

                <!-- Judul & Sub-judul Form Dinamis -->
                <div class="mb-6">
                    <h2 class="hidden md:block text-2xl lg:text-3xl font-bold text-slate-800 tracking-tight"
                        x-text="isLogin ? 'Selamat Datang Kembali' : 'Pendaftaran Akun Baru'"></h2>
                    <p class="text-sm text-slate-500 mt-2">
                        <span x-text="isLogin ? 'Silakan masuk untuk mengakses dasbor lelang Anda.' : 'Lengkapi formulir di bawah ini untuk memulai registrasi mitra.'"></span>
                    </p>
                </div>

                <!-- FORM LOGIN -->
                <form x-show="isLogin" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" method="POST" action="{{ route('login.proses') }}" class="space-y-4">
                    @csrf

                    <x-input
                        namaLabel="Email"
                        type="email"
                        namaInput="email"
                        slang="contoh@domain.com"
                        required
                        x-model="email" />

                    <div class="relative">
                        <x-input
                            namaLabel="Password"
                            namaInput="password"
                            slang="••••••••"
                            required
                            x-model="password"
                            ::type="showPassword ? 'text' : 'password'" />
                        <button type="button" class="absolute right-4 bottom-3 text-slate-400 hover:text-slate-600 focus:outline-none" @click="showPassword = !showPassword">
                            <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                        </button>
                    </div>

                    <div class="pt-2">
                        <x-primary-button type="submit" full>Masuk ke Akun</x-primary-button>
                    </div>
                </form>

                <!-- FORM REGISTER (Responsif 2 Kolom pada md:) -->
                <form x-show="!isLogin" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" method="POST" action="{{ route('register.proses') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nama Lengkap -->
                        <div class="col-span-1">
                            <x-input
                                namaLabel="Nama Lengkap"
                                type="text"
                                namaInput="name"
                                slang="Nama Lengkap Anda"
                                required
                                x-model="name" />
                            @error('name')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="col-span-1">
                            <x-input
                                namaLabel="Email"
                                type="email"
                                namaInput="email"
                                slang="contoh@domain.com"
                                required
                                x-model="email" />
                            @error('email')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="col-span-1 relative">
                            <x-input
                                namaLabel="Password"
                                namaInput="password"
                                slang="••••••••"
                                required
                                x-model="password"
                                ::type="showPassword ? 'text' : 'password'" />
                            <button type="button" class="absolute right-4 bottom-3 text-slate-400 hover:text-slate-600 focus:outline-none" @click="showPassword = !showPassword">
                                <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </button>
                            @error('password')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Konfirmasi Password -->
                        <div class="col-span-1 relative">
                            <x-input
                                namaLabel="Konfirmasi Password"
                                namaInput="password_confirmation"
                                slang="••••••••"
                                required
                                x-model="confirmPassword"
                                ::type="showConfirmPassword ? 'text' : 'password'" />
                            <button type="button" class="absolute right-4 bottom-3 text-slate-400 hover:text-slate-600 focus:outline-none" @click="showConfirmPassword = !showConfirmPassword">
                                <i :class="showConfirmPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </button>
                        </div>

                        <!-- No HP -->
                        <div class="col-span-1 md:col-span-2">
                            <x-input
                                namaLabel="No HP (Telepon)"
                                type="text"
                                namaInput="no_telp"
                                slang="08812345678"
                                required
                                x-model="no_telp" />
                            @error('no_telp')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Dropdown Portofolio & Inputs -->
                        <div class="col-span-1 md:col-span-2" x-data="{ type: 'file' }">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-slate-700">Jenis Portofolio</label>
                                <div class="relative">
                                    <select name="type" x-model="type"
                                        class="w-full bg-slate-50 border border-slate-200 text-slate-900 appearance-none rounded-xl py-3 pr-10 pl-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 transition-all">
                                        <option value="file">File (Dokumen/Gambar)</option>
                                        <option value="link">Link Tautan</option>
                                    </select>
                                    <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500"
                                        viewBox="0 0 16 16" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>

                                <!-- INPUT FILE -->
                                <div x-show="type === 'file'" x-transition x-cloak class="space-y-2 pt-1">
                                    <x-input
                                        type="file"
                                        namaInput="file_path"
                                        accept=".jpg,.jpeg,.png,.pdf" />
                                    <p class="text-[11px] text-slate-500">
                                        Format yang diterima: JPG, JPEG, PNG, PDF • Maksimal: 5 MB
                                    </p>
                                    @error('file_path')
                                    <p class="text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- INPUT LINK -->
                                <div x-show="type === 'link'" x-transition x-cloak class="space-y-3 pt-1">
                                    <x-input
                                        type="url"
                                        namaInput="link_url"
                                        value="{{ old('link_url') }}"
                                        slang="https://domain.com/portofolio" />
                                    @error('link_url')
                                    <p class="text-xs text-red-500">{{ $message }}</p>
                                    @enderror

                                    <div>
                                        <p class="text-xs font-semibold text-slate-500 mb-2">Tautan yang diizinkan:</p>
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach ($terimaDomain as $domain)
                                            <span class="px-2.5 py-0.5 text-xs bg-brand-50 text-brand-700 rounded-full font-medium border border-brand-100">
                                                {{ $domain }}
                                            </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4 p-3 bg-yellow-100 border border-yellow-200 rounded-xl flex items-start gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-yellow-600 shrink-0 mt-0.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 1 1 1.063 1.06l-.041.02a.75.75 0 0 1-1.063-1.06Zm0 4.5.041-.02a.75.75 0 1 1 1.063 1.06l-.041.02a.75.75 0 0 1-1.063-1.06ZM12 22.5c5.799 0 10.5-4.701 10.5-10.5S17.799 1.5 12 1.5 1.5 6.201 1.5 12 6.201 22.5 12 22.5Z" />
                        </svg>
                        <p class="text-[11px] text-brand-800 leading-normal">
                            Setelah anda melakukan pendaftaran, akun akan diajukan kepada admin untuk disetujui. Anda akan mendapatkan notifikasi dari email untuk informasi pengajuan pendaftaran akun ini. Pastikan email anda adalah email asli untuk mendapatkan informasi lebih lanjut.
                        </p>
                    </div>

                    <div class="pt-3">
                        <x-primary-button type="submit" full>Daftar Sekarang</x-primary-button>
                    </div>
                </form>

                <!-- LOGIN OAUTH DENGAN GOOGLE -->
                <div class="mt-4">
                    <a href="{{ route('google.login') }}"
                        class="w-full inline-flex items-center justify-center gap-3 border border-slate-300 rounded-xl px-4 py-3 hover:bg-slate-50 active:bg-slate-100 transition-colors shadow-sm">
                        <img src="https://www.svgrepo.com/show/475656/google-color.svg" width="20" height="20" loading="lazy" class="w-5 h-5" alt="Google Logo">
                        <span class="font-semibold text-sm text-slate-700">
                            Lanjut dengan Google
                        </span>
                    </a>
                </div>

                <!-- TOMBOL SWITCH FORM -->
                <p class="mt-6 text-center text-sm text-slate-600">
                    <span x-text="isLogin ? 'Belum punya akun?' : 'Sudah memiliki akun?'"></span>
                    <button type="button" class="ml-1 text-brand-600 hover:text-brand-800 font-bold hover:underline focus:outline-none transition-colors" @click="isLogin = !isLogin">
                        <span x-text="isLogin ? 'Daftar Mitra' : 'Masuk'"></span>
                    </button>
                </p>
            </div>
        </div>
    </div>

    <!-- Script Alpine JS Initialization -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('authPage', () => ({
                // Secara otomatis memuat form registrasi jika URL memiliki hash #register
                isLogin: window.location.hash !== '#register',
                showPassword: false,
                showConfirmPassword: false,
                email: '',
                password: '',
                confirmPassword: '',
                name: '',
                no_telp: ''
            }));
        });
    </script>
</body>

</html>