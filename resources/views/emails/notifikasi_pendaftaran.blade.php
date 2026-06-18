<x-mail::message>
# Pendaftaran User Baru

Ada user baru yang mendaftar pada sistem produksi dan memerlukan verifikasi admin.

<x-mail::table>
| Informasi | Detail |
|:-----------|:--------|
| Nama | {{ $user->name }} |
| Email | {{ $user->email }} |
| No Telepon | {{ $user->no_telp }} |
| Status | {{ $user->status_verifikasi }} |
</x-mail::table>


Silakan login ke dashboard admin untuk memverifikasi user tersebut.
<x-mail::button :url="url('/login')" color="success">
Login ke Sistem
</x-mail::button>

Terima Kasih,<br>
</x-mail::message>