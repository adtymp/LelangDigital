<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran User Baru</title>
</head>

<body class="bg-gray-100 py-10">
    <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-blue-600 px-6 py-5">
            <h1 class="text-2xl font-bold text-white"> Pendaftaran User Baru </h1>
        </div>
        <div class="p-8 text-gray-700">
            <p class="mb-6"> Ada user baru yang mendaftar pada sistem produksi. </p>
            <div class="border rounded-xl overflow-hidden mb-6">
                <div class="grid grid-cols-2 bg-gray-50 border-b">
                    <div class="p-4 font-semibold">Nama</div>
                    <div class="p-4">{{ $user->name }}</div>
                </div>
                <div class="grid grid-cols-2 border-b">
                    <div class="p-4 font-semibold">Email</div>
                    <div class="p-4">{{ $user->email }}</div>
                </div>
                <div class="grid grid-cols-2 bg-gray-50 border-b">
                    <div class="p-4 font-semibold">No Telp</div>
                    <div class="p-4">{{ $user->no_telp }}</div>
                </div>
                <div class="grid grid-cols-2">
                    <div class="p-4 font-semibold">Status</div>
                    <div class="p-4"> <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm"> {{ $user->status_verifikasi }} </span> </div>
                </div>
            </div>
            <p class="mb-6"> Silakan login ke dashboard admin untuk memverifikasi user tersebut. </p> <a href="{{ url('/login') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-3 rounded-xl transition"> Login Dashboard </a>
        </div>
        <div class="bg-gray-50 px-6 py-4 text-center text-sm text-gray-500"> Sistem Produksi • {{ date('Y') }} </div>
    </div>
</body>

</html>