@props(['status'])

<span
    class="inline-flex px-3 py-1 rounded-full text-xs font-medium border"
    :class="{
        'bg-yellow-100 text-yellow-800 border-yellow-200':
            ['permintaan','menunggu'].includes({{ $status }}),

        'bg-green-100 text-green-800 border-green-200':
            ['aktif','diterima','sudah_dibayar','diambil'].includes({{ $status }}),

        'bg-blue-100 text-blue-800 border-blue-200':
            {{ $status }} === 'selesai',

        'bg-red-100 text-red-800 border-red-200':
            ['dibatalkan','ditolak','belum_dibayar','nonaktif'].includes({{ $status }})
    }">

    <span
        x-text="{{ $status }}
            ?.replaceAll('_',' ')
            ?.replace(/\b\w/g, c => c.toUpperCase())">
    </span>

</span>