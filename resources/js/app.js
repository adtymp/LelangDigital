import './bootstrap';
import Alpine from 'alpinejs'

window.Alpine = Alpine

Alpine.start()

// console.log('APP JS ACTIVE');


function startEchoListener() {
    const authId = document
        .querySelector('meta[name="auth-id"]')
        ?.getAttribute('content');

    if (!authId || !window.Echo) return;

    window.Echo
        .private(`user.${authId}`)
        .listen('.pesan.kirim', (e) => {
            window.dispatchEvent(new CustomEvent('notify', {
                detail: {
                    title: e.nama_pengirim || 'Pesan Baru',
                    message: e.teks
                }
            }));
        })

        .listen('.proyek.aktif', (e) => {
            window.dispatchEvent(new CustomEvent('notify', {
                detail: {
                    title: 'Proyek Baru Aktif 🚀',
                    message: e.judul
                }
            }));
            console.log('PROYEK AKTIF', e);
        })
        
        .listen('.nilai.tugas', (e) => {
            window.dispatchEvent(new CustomEvent('notify', {
                detail: {
                    title: 'Tugas Selesai Dinilai ⭐',
                    message: `Tugas Anda pada "${e.nama_sub_proyek}" telah dinilai dengan skor ${e.total_skor}`
                }
            }));
        })
        
        .listen('.pembayaran.diunggah', (e) => {
            window.dispatchEvent(new CustomEvent('notify', {
                detail: {
                    title: 'Pembayaran Ditransfer 💸',
                    message: `Pembayaran proyek "${e.nama_proyek}" sebesar Rp ${new Intl.NumberFormat('id-ID').format(e.total_pembayaran)} telah dikirim!`
                }
            }));
        });
}

document.addEventListener('DOMContentLoaded', () => {
    setTimeout(startEchoListener, 500);
});