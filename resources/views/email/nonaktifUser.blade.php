<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penonaktifan Akun</title>
</head>

<body class="bg-gray-100 py-10">
    <div class="max-w-xl mx-auto bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-red-600 px-6 py-5">
            <h1 class="text-2xl font-bold text-white"> Akun Dinonaktifkan </h1>
        </div>
        <div class="p-8 text-gray-700">
            <p class="text-lg font-semibold mb-4"> Halo, {{ $user->name }} </p>
            <p class="mb-4"> Mohon maaf, akun Anda telah dinonaktifkan oleh admin. </p>
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                <p class="text-red-700"> Jika Anda ingin mengetahui informasi lebih lanjut, silakan hubungi admin terkait. </p>
            </div>
            <p> Terima kasih atas perhatian Anda. </p>
        </div>
        <div class="bg-gray-50 px-6 py-4 text-center text-sm text-gray-500"> Sistem Produksi • {{ date('Y') }} </div>
    </div>
</body>

</html>