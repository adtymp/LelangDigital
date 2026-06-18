<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Form Portofolio</title>
    <link rel="icon" type="image/png" href="{{ asset('image/logo-pranala.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body>
    <x-alert></x-alert>
    <div class="min-h-screen bg-linear-to-br from-brand-500 via-brand-700 to-brand-500 py-10 px-4 flex items-center justify-center">

        <!-- CARD -->
        <div>
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden w-full max-w-md">

                <!-- HEADER -->
                <div class="px-6 md:px-10 pt-10 pb-8 text-center">

                    <!-- AVATAR -->
                    <div class="flex justify-center mb-5">

                        @if ($user->avatar)
                        <img
                            src="{{ $user->avatar }}"
                            alt="{{ $user->name }}"
                            class="w-24 h-24 rounded-full border-4 border-white shadow-lg object-cover">
                        @else
                        <div class="w-24 h-24 rounded-full bg-brand-100 flex items-center justify-center text-3xl font-bold text-brand-700 shadow-lg">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        @endif

                    </div>

                    <!-- TITLE -->
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                        Lengkapi Data Freelancer
                    </h1>

                    <p class="mt-3 text-gray-500 text-sm md:text-base max-w-lg mx-auto leading-relaxed">
                        Akun Google berhasil terhubung. Lengkapi data dan portofolio Anda
                        untuk melanjutkan proses verifikasi admin.
                    </p>

                </div>

                <!-- FORM -->
                <div class="px-6 md:px-10 pb-10">

                    <form
                        action="{{ route('google.lengkapi.store') }}"
                        method="POST"
                        enctype="multipart/form-data"
                        class="space-y-6">

                        @csrf

                        <!-- NAMA -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Lengkap
                            </label>

                            <input
                                type="text"
                                value="{{ $user->name }}"
                                disabled
                                class="w-full rounded-2xl border border-gray-200 bg-gray-100 px-4 py-3 text-gray-500">
                        </div>

                        <!-- EMAIL -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Email
                            </label>

                            <input
                                type="email"
                                value="{{ $user->email }}"
                                disabled
                                class="w-full rounded-2xl border border-gray-200 bg-gray-100 px-4 py-3 text-gray-500">
                        </div>

                        <!-- NO HP -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nomor WhatsApp
                            </label>

                            <input
                                type="text"
                                name="no_telp"
                                value="{{ old('no_telp') }}"
                                placeholder="08xxxxxxxxxx"
                                class="w-full rounded-2xl border border-gray-200 px-4 py-3 focus:ring-4 focus:ring-brand-100 focus:border-brand-500 transition">

                            @error('no_telp')
                            <p class="mt-2 text-sm text-red-500">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- PORTOFOLIO -->
                        <div
                            x-data="{ type: 'file' }"
                            class="space-y-5">

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Jenis Portofolio
                                </label>

                                <select
                                    name="type"
                                    x-model="type"
                                    class="w-full rounded-2xl border border-gray-200 px-4 py-3 focus:ring-4 focus:ring-brand-100 focus:border-brand-500 transition">

                                    <option value="file">Upload File</option>
                                    <option value="link">Link Portofolio</option>

                                </select>
                            </div>

                            <!-- FILE -->
                            <div
                                x-show="type === 'file'"
                                x-transition
                                class="space-y-3">

                                <div class="border-2 border-dashed border-gray-300 rounded-3xl p-6 text-center hover:border-brand-400 transition">

                                    <div class="flex justify-center mb-4">

                                        <div class="w-14 h-14 rounded-2xl bg-brand-100 flex items-center justify-center">

                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="w-7 h-7 text-brand-600"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor">

                                                <path stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 0115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />

                                            </svg>

                                        </div>

                                    </div>

                                    <input
                                        type="file"
                                        name="file_path"
                                        accept=".jpg,.jpeg,.png,.pdf"
                                        class="block w-full text-sm text-gray-700
                    file:mr-4
                    file:rounded-xl
                    file:border-0
                    file:bg-brand-500
                    file:px-4
                    file:py-2.5
                    file:text-white
                    hover:file:bg-brand-700">

                                    <p class="mt-3 text-xs text-gray-500">
                                        JPG, PNG, PDF • Maksimal 5MB
                                    </p>

                                </div>

                                @error('file_path')
                                <p class="text-sm text-red-500">
                                    {{ $message }}
                                </p>
                                @enderror

                            </div>

                            <!-- LINK -->
                            <div
                                x-show="type === 'link'"
                                x-transition
                                class="space-y-4">

                                <div>
                                    <input
                                        type="url"
                                        name="link_url"
                                        value="{{ old('link_url') }}"
                                        placeholder="https://behance.net/username"
                                        class="w-full rounded-2xl border border-gray-200 px-4 py-3 focus:ring-4 focus:ring-brand-100 focus:border-brand-500 transition">

                                    @error('link_url')
                                    <p class="mt-2 text-sm text-red-500">
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>

                                <!-- DOMAIN -->
                                <div>

                                    <p class="text-sm font-medium text-gray-600 mb-3">
                                        Platform yang didukung
                                    </p>

                                    <div class="flex flex-wrap gap-2">

                                        @foreach ($terimaDomain as $domain)

                                        <span class="px-3 py-1.5 rounded-full bg-brand-50 text-brand-700 text-xs font-medium border border-brand-100">
                                            {{ $domain }}
                                        </span>

                                        @endforeach

                                    </div>

                                </div>

                            </div>

                        </div>

                        <x-primary-button type="submit" full>Kirim Pendaftaran</x-primary-button>

                    </form>

                </div>

            </div>

            <!-- FOOTER -->
            <p class="text-center text-white/80 text-sm mt-6 px-4 leading-relaxed">
                Setelah data dikirim, akun Anda akan diverifikasi oleh admin terlebih dahulu.
            </p>
        </div>

    </div>
</body>

</html>