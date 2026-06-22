<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\BadgeController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\ManajemenFreelancerController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PengambilanController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\PoinController;
use App\Http\Controllers\ProyekController;
use App\Http\Controllers\RekeningController;
use App\Http\Controllers\ResetLevelController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/test-email', function () {
    Mail::raw('Ini adalah email test dari Laravel.', function ($message) {
        $message->to('adimira012@gmail.com')
            ->subject('Test SMTP Laravel');
    });

    return 'Email test berhasil dikirim';
});

use App\Mail\AktifUserMail;
use App\Mail\ProyekAktifMail;
use App\Mail\TerimaUserMail;
use App\Mail\TolakUserMail;
use App\Mail\UploadPembayaranMail;
use App\Models\Pembayaran;
use App\Models\Proyek;
use App\Models\User;

Route::get('/preview-email', function () {

    $user = User::first();
    $proyek = Proyek::first();
    $pembayaran = Pembayaran::first();

    return new UploadPembayaranMail($user, $pembayaran);
});


Route::get('', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'index'])->name('login');

Route::post('login/login-proses', [LoginController::class, 'login'])->name('login.proses');

Route::post('login/register-proses', [LoginController::class, 'register'])->name('register.proses');

Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.login');

Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

Route::middleware(['auth'])->group(function () {

    Route::get('/api/badges', [BadgeController::class, 'index'])->name('api.badges');

    Route::get('/api/notifications', [NotifikasiController::class, 'index'])->name('notifications.index');
    
    Route::post('/api/notifications/read', [NotifikasiController::class, 'markRead'])->name('notifications.read');

    Route::get('/lengkapi-google', [GoogleController::class, 'formLengkapi'])->name('google.lengkapi');

    Route::post('/lengkapi-google', [GoogleController::class, 'simpanLengkapi'])->name('google.lengkapi.store');

    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');

    Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');

    Route::get('/chat/unread', [ChatController::class, 'unread'])->name('chat.unread');

    Route::post('/chat/read/{userId}', [ChatController::class, 'markRead'])->name('chat.read');
});

Route::middleware(['role:admin'])->group(function () {

    Route::get('admin-dashboard', [ProyekController::class, 'adminDashboard'])->name('dashboard.admin');

    Route::post('/ajax/pdf-info', [ProyekController::class, 'getPdfInfo'])->name('ajax.pdf.info');

    Route::get('tambah-proyek', [ProyekController::class, 'halamanFormProyek'])->name('proyek.halaman');

    Route::post('tambah-proyek/tambah', [ProyekController::class, 'tambahProyek'])->name('proyek.add');

    Route::get('tambah-proyek/{proyek}', [ProyekController::class, 'editProyek'])->name('proyek.edit');

    Route::post('tambah-proyek/{proyek}/update', [ProyekController::class, 'updateProyek'])->name('proyek.update');

    Route::post('admin-dashboard/{proyek}', [ProyekController::class, 'hapusProyek'])->name('proyek.hapus');

    Route::get('/monitoring/{id}/pengambilan', [PengambilanController::class, 'halamanMonitoring'])->name('monitoring');

    Route::get('riwayat-proyek', [ProyekController::class, 'riwayatProyek'])->name('riwayat.proyek');

    Route::get('manajemen-freelancer', [ManajemenFreelancerController::class, 'halamanFreelancer'])->name('freelancer.halaman');

    Route::get('manajemen-freelancer/{user}/download-portofolio', [ManajemenFreelancerController::class, 'filePortofolio'])->name('portofolio.download');

    Route::post('manajemen-freelancer/{user}/status-verifikasi', [ManajemenFreelancerController::class, 'updateStatusVerifikasi'])->name('freelancer.update-verifikasi');

    Route::post('manajemen-freelancer/{user}/status-akun', [ManajemenFreelancerController::class, 'updateStatusAkun'])->name('freelancer.update-akun');

    Route::get('penilaian', [PenilaianController::class, 'halamanPenilaian'])->name('penilaian.view');

    Route::get('penilaian/{id}/download/hasil', [PenilaianController::class, 'downloadHasilTugas'])->name('penilaian.downloadHasilTugas');

    Route::get('penilaian/{proyek}', [PenilaianController::class, 'detail'])->name('penilaian.detail');

    Route::post('penilaian/nilai', [PenilaianController::class, 'nilaiTugas'])->name('penilaian.tambah');

    Route::get('pengaturan', [PoinController::class, 'halamanPoin'])->name('poin.view');

    Route::post('pengaturan', [PoinController::class, 'tambahAspek'])->name('poin.tambah');

    Route::post('pengaturan/{poin}', [PoinController::class, 'updateAspek']);

    Route::delete('pengaturan/{poin}', [PoinController::class, 'hapusAspek']);

    Route::get('pengaturan-level', [LevelController::class, 'halamanLevel'])->name('level.lihat');

    Route::post('pengaturan-level/update-reset', [ResetLevelController::class, 'updateReset'])->name('reset.update');

    Route::post('pengaturan-level/proses-tambah', [LevelController::class, 'tambahLevel'])->name('level.tambah');

    Route::delete('pengaturan-level/{level}', [LevelController::class, 'hapusLevel']);

    Route::post('pengaturan-level/{level}', [LevelController::class, 'updateLevel']);

    Route::get('/pengaturan-level/reset-status', [ResetLevelController::class, 'statusReset']);

    Route::get('simulasi', [PoinController::class, 'halamanSimulasi'])->name('simulasi.lihat');

    Route::get('pembayaran/{id}', [PembayaranController::class, 'detailPembayaran'])->name('pembayaran.detail');

    Route::get('pembayaran', [PembayaranController::class, 'halamanPembayaran'])->name('pembayaran.lihat');

    Route::get('pembayaran/{id}/download/hasil', [PembayaranController::class, 'downloadHasilTugas'])
        ->name('pembayaran.downloadHasilTugas');

    Route::post('pembayaran/update', [PembayaranController::class, 'uploadPembayaran'])->name('pembayaran.update');
});

Route::middleware(['role:freelancer'])->group(function () {

    Route::get('dashboard', [ProyekController::class, 'freelanceDashboard'])->name('dashboard.freelance');

    Route::get('tambah-rekening', [RekeningController::class, 'halamanRekening'])->name('rekening.form');

    Route::post('tambah-rekening/proses', [RekeningController::class, 'tambahRekening'])->name('rekening.tambah');

    Route::get('proyek/ambil/{subsub}', [PengambilanController::class, 'detailProyek'])->name('freelance.ambil');

    Route::post('proyek/ambil/{subsub}/proses', [PengambilanController::class, 'ambilTugas'])->name('freelance.ambil.proses');

    Route::get('proyek/ambil/{subsub}/download/pdf', [PengambilanController::class, 'downloadPdfSubsubproyek'])
        ->name('freelancer.downloadPdfSubsubproyek');

    Route::get('proyek/ambil/{subsub}/download/xls', [PengambilanController::class, 'downloadTemplateSubsubproyek'])
        ->name('freelancer.downloadTemplateSubsubproyek');

    Route::get('/download-zip', [PengambilanController::class, 'downloadZip'])->name('freelance.download.zip');

    Route::get('profil-saya', [ManajemenFreelancerController::class, 'profilSaya'])->name('profil.freelance');

    Route::post('profil-saya/{user}/update-telepon', [ManajemenFreelancerController::class, 'updateTelepon'])->name('profil.updateTelepon');

    Route::post('profil-saya/{user}/update-password', [ManajemenFreelancerController::class, 'updatePassword'])->name('profil.updatePassword');

    Route::put('profil/{user}/update-rekening', [ManajemenFreelancerController::class, 'updateRekening'])->name('profil.updateRekening');

    Route::put('profil/{user}/update-portofolio', [ManajemenFreelancerController::class, 'updatePortofolio'])->name('profil.updatePortofolio');

    Route::get('upload-tugas', [PengambilanController::class, 'halamanUploadTugas'])->name('freelancer.uploadTugas');

    Route::get('upload-tugas/{id}/download/pdf', [PengambilanController::class, 'downloadPdfTugas'])
        ->name('freelancer.downloadPdfTugas');

    Route::get('upload-tugas/{id}/download/template', [PengambilanController::class, 'downloadTemplateTugas'])
        ->name('freelancer.downloadTemplateTugas');

    Route::post('upload-tugas/{id}/proses-update', [PengambilanController::class, 'uploadTugas'])->name('freelancer.uploadHasil');

    Route::post('upload-tugas/{id}/proses-batal', [PengambilanController::class, 'batalTugas'])->name('freelancer.batalTugas');

    Route::get('riwayat', [PembayaranController::class, 'halamanRiwayat'])->name('riwayat.lihat');

    Route::get('sistem-level', [LevelController::class, 'sistemLevel'])->name('level.sistem');
});
