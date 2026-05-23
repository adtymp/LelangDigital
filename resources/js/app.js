import './bootstrap';
import Alpine from 'alpinejs'

window.Alpine = Alpine

Alpine.start()

console.log('APP JS ACTIVE');


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
        });
}

document.addEventListener('DOMContentLoaded', () => {
    setTimeout(startEchoListener, 500);
});