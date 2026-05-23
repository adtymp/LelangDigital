<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login & Register</title>
    <link rel="icon" type="image/png" href="{{ asset('image/logo-pranala.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body>
    <x-alert></x-alert>
    <div x-data="authPage()" class="min-h-screen bg-linear-to-r from-brand-500 via-brand-700 to-brand-500 bg-cover">
        <div class="flex min-h-screen items-center justify-center p-6">
            <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">
                <div class="text-center mb-6 space-y-3">

                    <!-- Logo + Brand -->
                    <div class="flex flex-col items-center space-y-2 mb-4">
                        <img src="{{ asset('image/logo-pranala.png') }}" alt="Logo"
                            class="w-10 h-10 object-contain">

                        <div class="text-lg text-gray-700 leading-tight">
                            <p class="text-2xl font-bold bg-linear-to-r from-brand-500 to-brand-700 bg-clip-text text-transparent">Sistem Lelang Digital</p>
                            <p class="text-gray-500">PT. Digital Pranala Transmaritim</p>
                        </div>
                    </div>


                    <p class="text-gray-600 text-sm">
                        <span x-text="isLogin ? 'Silahkan login untuk melanjutkan' : 'Mulai dengan membuat akun'"></span>
                    </p>

                </div>
                <!-- LOGIN FORM -->
                <form x-show="isLogin" method="POST" action="{{ route('login.proses') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" required x-model="email" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:outline-none" placeholder="you@example.com" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'" name="password" required x-model="password" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:outline-none" placeholder="••••••••" />
                            <button type="button" class="absolute right-3 top-3 text-gray-400" @click="showPassword = !showPassword">
                                <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </button>
                        </div>
                    </div>
                    <x-primary-button type="submit" full>Masuk</x-primary-button>
                </form>

                <!-- REGISTER FORM -->
                <form x-show="!isLogin" method="POST" action="{{ route('register.proses') }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" name="name" required x-model="name" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:outline-none" placeholder="John Doe" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" required x-model="email" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:outline-none" placeholder="you@example.com" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'" name="password" required x-model="password" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:outline-none" placeholder="••••••••" />
                            <button type="button" class="absolute right-3 top-3 text-gray-400" @click="showPassword = !showPassword">
                                <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                        <div class="relative">
                            <input :type="showConfirmPassword ? 'text' : 'password'" name="password_confirmation" required x-model="confirmPassword" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:outline-none" placeholder="••••••••" />
                            <button type="button" class="absolute right-3 top-3 text-gray-400" @click="showConfirmPassword = !showConfirmPassword">
                                <i :class="showConfirmPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">No HP</label>
                        <input type="text" name="no_telp" required x-model="no_telp" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:outline-none" placeholder="088********" />
                    </div>
                    <div x-data="{ type: 'file' }"
                        class="space-y-3">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Portofolio</label>
                        <div class="relative">
                            <select name="type" x-model="type"
                                class="bg-gray-50 border border-gray-200 text-gray-900 col-start-1 row-start-1 w-full appearance-none rounded-md py-1.5 pr-7 pl-3 text-base placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 sm:text-sm/6">
                                <option value="file">File</option>
                                <option value="link">Link</option>
                            </select>
                            <svg class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500"
                                viewBox="0 0 16 16" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div
                            x-show="type === 'file'"
                            x-transition
                            x-cloak
                            class="space-y-2">

                            <input
                                type="file"
                                name="file_path"
                                accept=".jpg,.jpeg,.png,.pdf"
                                class="block w-full text-sm text-gray-900 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-brand-500 file:text-white hover:file:bg-brand-700">

                            <p class="text-xs text-gray-500">
                                Format: JPG, JPEG, PNG, PDF • Maksimal ukuran: 5 MB
                            </p>

                        </div>

                        <!-- LINK INPUT -->
                        <div
                            x-show="type === 'link'"
                            x-transition
                            x-cloak
                            class="space-y-3">
                            <input
                                type="url"
                                name="link_url"
                                value="{{ old('link_url') }}"
                                placeholder="https://contoh.com/portofolio"
                                class="block w-full rounded-md border border-gray-200 py-2 px-3 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">

                            @error('link_url')
                            <p class="text-sm text-red-500">{{ $message }}</p>
                            @enderror

                            <div>
                                <p class="text-sm font-medium text-gray-600 mb-2">Link yang diizinkan:</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($terimaDomain as $domain)
                                    <span class="px-3 py-1 text-xs bg-blue-100 text-brand-500 rounded-full">
                                        {{ $domain }}
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <x-primary-button type="submit" full>Daftar</x-primary-button>
                </form>

                <div class="mt-4">
                    <a href="{{ route('google.login') }}"
                        class="w-full inline-flex items-center justify-center gap-3 border border-gray-400 rounded-xl px-4 py-3 hover:bg-gray-200 transition">

                        <img src="https://www.svgrepo.com/show/475656/google-color.svg"
                            class="w-5 h-5">

                        <span class="font-medium text-gray-700">
                            Lanjut dengan Google
                        </span>
                    </a>
                </div>
                <!-- SWITCH FORM -->
                <p class="mt-6 text-center text-gray-600">
                    <span x-text="isLogin ? 'Belum punya akun?' : 'Sudah punya akun?'"></span>
                    <button type="button" class="ml-1 text-brand-500 hover:text-brand-700 font-semibold" @click="isLogin = !isLogin">
                        <span x-text="isLogin ? 'Daftar' : 'Masuk'"></span>
                    </button>
                </p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('authPage', () => ({
                isLogin: true,
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