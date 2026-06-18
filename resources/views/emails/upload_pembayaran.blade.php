<x-mail::message>

# Bukti Pembayaran Telah Diunggah

Halo, **{{ $user->name }}**

Kami ingin menginformasikan bahwa pembayaran untuk tugas Anda pada proyek **{{ $proyek->nama_proyek }}** telah berhasil ditransfer.

---

## Detail Transfer

<x-mail::table>
| Informasi | Detail |
|:-----------|:--------|
| Proyek | {{ $proyek->nama_proyek }} |
| Total Pembayaran | **Rp {{ number_format($pembayaran->total_pembayaran, 0, ',', '.') }}** |
| Status | 🟢 Sudah Dibayar |
</x-mail::table>

## Bukti Transfer
@if($pembayaran->bukti_transfer)
@php
$extension = pathinfo($pembayaran->bukti_transfer, PATHINFO_EXTENSION);
$isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
@endphp

@if($isImage)
<img src="{{ rtrim(config('app.url'), '/') . '/storage/' . $pembayaran->bukti_transfer }}"
     alt="Bukti Transfer"
     style="max-width:100%; height:auto;">
@else
<x-mail::button :url="rtrim(config('app.url'), '/').'/storage/'.$pembayaran->bukti_transfer" color="success">
Unduh Bukti Transfer (PDF)
</x-mail::button>
@endif
@endif

Klik di bawah ini untuk masuk ke dalam sistem:

<x-mail::button :url="url('/login')" color="primary">
Login ke Sistem
</x-mail::button>

Silakan periksa saldo rekening Anda. Jika ada kendala, hubungi Admin melalui menu Chat.

Terima kasih atas kerja keras Anda!

</x-mail::message>