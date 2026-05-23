<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyek Aktif</title>
</head>

<body class="bg-gray-100 py-10">
    <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-indigo-600 px-6 py-5">
            <h1 class="text-2xl font-bold text-white"> 🚀 Proyek Baru Tersedia </h1>
        </div>
        <div class="p-8 text-gray-700">
            <p class="text-lg font-semibold mb-4"> Halo, {{ $user->name }} </p>
            <p class="mb-6"> Terdapat proyek baru yang saat ini telah tersedia untuk Anda lihat pada sistem. </p>
            <div class="border rounded-2xl overflow-hidden mb-6">
                <div class="grid grid-cols-2 bg-gray-50 border-b">
                    <div class="p-4 font-semibold">Nama Proyek</div>
                    <div class="p-4">{{ $proyek->nama_proyek }}</div>
                </div>
                <div class="grid grid-cols-2 border-b">
                    <div class="p-4 font-semibold">Tanggal Mulai</div>
                    <div class="p-4"> {{ $proyek->tanggal_mulai->format('d M Y H:i') }} WIB </div>
                </div>
                <div class="grid grid-cols-2 bg-gray-50 border-b">
                    <div class="p-4 font-semibold">Tanggal Selesai</div>
                    <div class="p-4"> {{ $proyek->tanggal_selesai->format('d M Y H:i') }} WIB </div>
                </div>
                <div class="grid grid-cols-2">
                    <div class="p-4 font-semibold">Status</div>
                    <div class="p-4"> <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-medium"> Aktif </span> </div>
                </div>
            </div>
            <p class="mb-6"> Silakan login ke sistem untuk melihat detail proyek dan mengajukan penawaran. </p> <a href="{{ url('/login') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-6 py-3 rounded-xl transition duration-300"> Login ke Sistem </a>
        </div>
        <div class="bg-gray-50 px-6 py-4 text-center text-sm text-gray-500"> Sistem Lelang Digital • {{ date('Y') }} </div>
    </div>
</body>

</html>