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
Silakan lihat lampiran email untuk melihat bukti transfer pembayaran.

Klik di bawah ini untuk masuk ke dalam sistem:

<x-mail::button :url="url('/login')" color="primary">
Login ke Sistem
</x-mail::button>

Silakan periksa saldo rekening Anda. Jika ada kendala, hubungi Admin melalui menu Chat.

Terima kasih atas kerja keras Anda!

</x-mail::message>