<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Rekening</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="min-h-screen bg-linear-to-br from-brand-500 via-brand-700 to-brand-500 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-green-500 rounded-full mb-4 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" height="50" height="50" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                        <path fill="currentColor" d="M320 576C178.6 576 64 461.4 64 320C64 178.6 178.6 64 320 64C461.4 64 576 178.6 576 320C576 461.4 461.4 576 320 576zM320 112C205.1 112 112 205.1 112 320C112 434.9 205.1 528 320 528C434.9 528 528 434.9 528 320C528 205.1 434.9 112 320 112zM390.7 233.9C398.5 223.2 413.5 220.8 424.2 228.6C434.9 236.4 437.3 251.4 429.5 262.1L307.4 430.1C303.3 435.8 296.9 439.4 289.9 439.9C282.9 440.4 276 437.9 271.1 433L215.2 377.1C205.8 367.7 205.8 352.5 215.2 343.2C224.6 333.9 239.8 333.8 249.1 343.2L285.1 379.2L390.7 234z" />
                    </svg>
                </div>
                <h1 class="text-brand-500 font-medium mb-2">Selamat Datang, {{$user->name}}!</h1>
                <p class="text-gray-500 mb-4">
                    Akun Anda telah disetujui oleh admin.
                </p>
                <div class="bg-blue-50 border-l-4 border-brand-500 p-4 text-left">
                    <div class="flex items-center gap-3">
                        <div class="text-brand-500">
                            <svg xmlns="http://www.w3.org/2000/svg" height="20" height="20" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                                <path fill="currentColor" d="M512 176C520.8 176 528 183.2 528 192L528 224L112 224L112 192C112 183.2 119.2 176 128 176L512 176zM528 288L528 448C528 456.8 520.8 464 512 464L128 464C119.2 464 112 456.8 112 448L112 288L528 288zM128 128C92.7 128 64 156.7 64 192L64 448C64 483.3 92.7 512 128 512L512 512C547.3 512 576 483.3 576 448L576 192C576 156.7 547.3 128 512 128L128 128zM144 408C144 421.3 154.7 432 168 432L216 432C229.3 432 240 421.3 240 408C240 394.7 229.3 384 216 384L168 384C154.7 384 144 394.7 144 408zM288 408C288 421.3 298.7 432 312 432L376 432C389.3 432 400 421.3 400 408C400 394.7 389.3 384 376 384L312 384C298.7 384 288 394.7 288 408z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-900 mb-1">Lengkapi Data Pembayaran</p>
                            <p class="text-sm text-gray-500">
                                Silakan lengkapi informasi bank Anda untuk menerima pembayaran proyek.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('rekening.tambah') }}" class="space-y-4">
                @csrf
                <div>

                    <label class="block text-gray-500 mb-2">Nama Bank *</label>
                    <select name="nama_bank"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                        required>
                        <option value="">Pilih Bank</option>
                        <option value="bni">BNI</option>
                        <option value="bca">BCA</option>
                        <option value="bri">BRI</option>
                        <option value="mandiri">Mandiri</option>
                        <option value="seabank">Seabank</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-500 mb-2">Nomor Rekening *</label>
                    <input
                        type="text" name="no_akun"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                        placeholder="1234567890"
                        required />
                </div>

                <div>
                    <label class="block text-gray-500 mb-2">Nama Pemilik Rekening *</label>
                    <input
                        type="text" name="nama_akun"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                        placeholder="Nama sesuai rekening bank"
                        required />
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-sm text-yellow-800">
                        ⚠️ Pastikan data yang Anda masukkan sudah benar. Data ini akan digunakan untuk transfer pembayaran proyek yang Anda kerjakan.
                    </p>
                </div>

                <x-primary-button type="submit" full>
                    <svg xmlns="http://www.w3.org/2000/svg" height="20" height="20" class="mr-2" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                        <path fill="currentColor" d="M512 176C520.8 176 528 183.2 528 192L528 224L112 224L112 192C112 183.2 119.2 176 128 176L512 176zM528 288L528 448C528 456.8 520.8 464 512 464L128 464C119.2 464 112 456.8 112 448L112 288L528 288zM128 128C92.7 128 64 156.7 64 192L64 448C64 483.3 92.7 512 128 512L512 512C547.3 512 576 483.3 576 448L576 192C576 156.7 547.3 128 512 128L128 128zM144 408C144 421.3 154.7 432 168 432L216 432C229.3 432 240 421.3 240 408C240 394.7 229.3 384 216 384L168 384C154.7 384 144 394.7 144 408zM288 408C288 421.3 298.7 432 312 432L376 432C389.3 432 400 421.3 400 408C400 394.7 389.3 384 376 384L312 384C298.7 384 288 394.7 288 408z" />
                    </svg>
                    Simpan & Lanjutkan
                </x-primary-button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-xs text-gray-500">
                    Halaman ini wajib diisi sebelum Anda dapat mengakses dashboard dan mengambil proyek.
                </p>
            </div>
        </div>
    </div>
</body>

</html>