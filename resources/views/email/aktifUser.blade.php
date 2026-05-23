<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Pengaktifan Akun</title>
</head>

<body class="bg-gray-100 py-10">
    <div class="max-w-xl mx-auto bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-green-600 px-6 py-5">
            <h1 class="text-2xl font-bold text-white"> Akun Diaktifkan Kembali </h1>
        </div>
        <div class="p-8 text-gray-700">
            <p class="text-lg font-semibold mb-4"> Halo, {{ $user->name }} </p>
            <p class="mb-4"> Mohon maaf atas kendala yang terjadi pada akun Anda. </p>
            <p class="mb-6"> Akun Anda telah berhasil diaktifkan kembali dan sekarang sudah dapat digunakan seperti biasa. </p>
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                <p class="text-green-700 font-medium"> 🎉 Selamat beraktivitas kembali dan semoga harimu menyenangkan. </p>
            </div>
            <p> Terima kasih. </p>
        </div>
        <div class="bg-gray-50 px-6 py-4 text-center text-sm text-gray-500"> Sistem Produksi • {{ date('Y') }} </div>
    </div>
</body>

</html>