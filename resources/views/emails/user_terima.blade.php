<x-mail::message>
# Permintaan Akun Anda Telah Diterima

Halo **{{ $user->name }}**,  

Selamat! Akun Anda telah berhasil disetujui oleh admin.

<x-mail::panel>
✅ Akun Anda sudah aktif dan siap digunakan untuk beraktivitas pada sistem.
</x-mail::panel>

Silakan login ke aplikasi untuk mulai menggunakan sistem.

<x-mail::button :url="url('/login')" color="success">
Login Ke Sistem
</x-mail::button>

Selamat dan semangat dalam beraktivitas<br>
</x-mail::message>
