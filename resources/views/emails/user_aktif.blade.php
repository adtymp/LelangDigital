<x-mail::message>

# Akun Anda Diaktifkan Kembali

Halo **{{ $user->name }}**,  

Mohon maaf atas kendala yang terjadi pada akun Anda.

Kami ingin memberitahukan bahwa akun Anda telah berhasil **diaktifkan kembali** dan sekarang sudah dapat digunakan seperti biasa.

<x-mail::panel>
✅ Akun Anda sudah aktif kembali dan siap digunakan untuk beraktivitas pada sistem.
</x-mail::panel>

Silakan login ke sistem untuk mulai menggunakan aplikasi kembali.

<x-mail::button :url="url('/login')" color="success">
Login ke Sistem
</x-mail::button>

Terima kasih atas kesabaran dan pengertiannya.

</x-mail::message>