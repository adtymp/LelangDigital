<x-mail::message>

# Proyek Baru Tersedia

Halo, **{{ $user->name }}**

Terdapat proyek baru yang saat ini telah tersedia untuk Anda lihat pada sistem.

---

## Detail Proyek

<x-mail::table>
| Informasi | Detail |
|:-----------|:--------|
| Nama | {{ $proyek->nama_proyek }} |
| Email | {{ $proyek->tanggal_mulai->format('d M Y H:i') }} WIB |
| No Telepon | {{ $proyek->tanggal_selesai->format('d M Y H:i') }} WIB |
| Status | {{ $proyek->status }} |
</x-mail::table>

<x-mail::panel>

**Nama Proyek:**  
{{ $proyek->nama_proyek }}

**Tanggal Mulai:**  
{{ $proyek->tanggal_mulai->format('d M Y H:i') }} WIB

**Tanggal Selesai:**  
{{ $proyek->tanggal_selesai->format('d M Y H:i') }} WIB

**Status:**  
🟢 Aktif

</x-mail::panel>

Silakan login ke sistem untuk melihat detail proyek dan mengajukan pekerjaan.

<x-mail::button :url="url('/login')" color="primary">
Login ke Sistem
</x-mail::button>

Terima kasih,<br>

</x-mail::message>
