<x-mail::message>
# Tugas Anda Telah Selesai Dinilai

Halo, **{{ $user->name }}**
Tugas Anda untuk subproyek **{{ $subsubproyek->subproyeks->nama_sub_proyek }}**
(Halaman: {{ $penilaian->pengambilan->dari_halaman }} - {{ $penilaian->pengambilan->sampai_halaman }})
telah selesai dinilai oleh Admin.

## Detail Penilaian

<x-mail::panel>
**Total Skor:** ⭐ {{ $penilaian->total_skor }} / 10  
**Poin Diperoleh:** ➕ {{ $penilaian->total_poin }} Poin  
</x-mail::panel>
### Aspek Skor Detail:
<x-mail::table>
| Aspek Kinerja | Skor | Catatan |
|:--------------|:-----|:--------|
@foreach($penilaian->skor as $slug => $nilai)
| {{ ucwords(str_replace('_', ' ', $slug)) }} | **{{ $nilai }}** | {{ $penilaian->catatan[$slug] ?? '-' }} |
@endforeach
</x-mail::table>

Pembayaran Anda telah dibuat dan masuk dalam antrean pembayaran dengan status **Belum Dibayar**.

<x-mail::button :url="url('/login')" color="primary">
Login ke Sistem
</x-mail::button>

Terima kasih atas kontribusi terbaik Anda!
</x-mail::message>
