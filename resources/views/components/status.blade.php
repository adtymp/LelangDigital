@props([
'value' => ''
])

@if($value === 'permintaan' || $value === 'menunggu')
<span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">{{ ucwords(str_replace('_', ' ', $value))}}</span>
@elseif($value === 'aktif' || $value === 'diterima' || $value === 'sudah_dibayar' || $value === 'diambil')
<span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">{{ ucwords(str_replace('_', ' ', $value))}}</span>
@elseif($value === 'selesai')
<span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">{{ ucwords(str_replace('_', ' ', $value))}}</span>
@elseif($value === 'dibatalkan' || $value === 'ditolak' || $value === 'belum_dibayar' || $value === 'nonaktif')
<span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">{{ ucwords(str_replace('_', ' ', $value))}}</span>
@endif