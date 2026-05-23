<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Diterima</title>
</head>

<body class="bg-gray-100 py-10">
    <div class="max-w-xl mx-auto bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-green-600 px-6 py-5">
            <h1 class="text-2xl font-bold text-white"> ✅ Akun Anda Diterima </h1>
        </div>
        <div class="p-8 text-gray-700">
            <p class="text-lg font-semibold mb-4"> Halo, {{ $user->name }} </p>
            <p class="mb-4"> Selamat! Akun Anda telah berhasil disetujui oleh admin. </p>
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                <p class="text-green-700"> Akun Anda sekarang sudah aktif dan dapat digunakan untuk mengakses sistem. </p>
            </div>
            <p class="mb-6"> Silakan login ke aplikasi untuk mulai menggunakan sistem. </p> <a href="{{ url('/login') }}" class="inline-block bg-green-600 hover:bg-green-700 text-white font-medium px-6 py-3 rounded-xl transition duration-300"> Login Sekarang </a>
        </div>
        <div class="bg-gray-50 px-6 py-4 text-center text-sm text-gray-500"> Sistem Lelang Digital • {{ date('Y') }} </div>
    </div>
</body>

</html>