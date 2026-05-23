<?php

namespace App\Console\Commands;

use App\Events\ProyekAktifRealtime;
use App\Mail\ProyekAktif;
use App\Models\Proyek;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class UpdateStatusProyek extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'proyek:update-status-proyek';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengubah status proyek dan kirim email secara otomatis berdasarkan tanggal mulai dan tanggal selesai';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sekarang = Carbon::now();

        //ubah proyek ke aktif

        $proyekAktif = Proyek::where('status', 'menunggu')
            ->where('tanggal_mulai', '<=', $sekarang)
            ->where('tanggal_selesai', '>', $sekarang)
            ->get();

        $jumlahAktif = 0;

        foreach ($proyekAktif as $proyek) {
            $proyek->update([
                'status' => 'aktif'
            ]);
            $jumlahAktif++;
        }

        //ubah proyek ke selesai

        $proyekSelesai = Proyek::whereIn('status', ['menunggu', 'aktif'])
            ->where('tanggal_selesai', '<=', $sekarang)
            ->get();

        $jumlahSelesai = 0;

        foreach ($proyekSelesai as $proyek) {
            $proyek->update([
                'status' => 'selesai'
            ]);
            $jumlahSelesai++;
        }

        //kirim email proyek aktif

        $freelancers = User::role('freelancer')
            ->where('status_verifikasi', 'diterima')
            ->where('status_akun', 'aktif')
            ->whereNotNull('level_id')
            ->with('level')
            ->get();

        $jumlahEmailAktif = 0;

        $semuaProyekAktif = Proyek::where('status', 'aktif')->get();

        foreach ($semuaProyekAktif as $proyek) {
            foreach ($freelancers as $freelancer) {
                $delayMenit = $freelancer->level?->delay_notifikasi ?? 0;
                $waktuBolehLihat = $proyek->tanggal_mulai->copy()->addMinutes($delayMenit);

                if ($sekarang->lt($waktuBolehLihat)) {
                    continue;
                }

                $cacheKeyAktif = "proyek_aktif_email_{$proyek->id}_{$freelancer->id}";

                if (!Cache::has($cacheKeyAktif)) {
                    Mail::to($freelancer->email)->queue(new ProyekAktif($freelancer, $proyek));

                    broadcast(new ProyekAktifRealtime($proyek, $freelancer));

                    Cache::put($cacheKeyAktif, true, now()->addDays(30));

                    $jumlahEmailAktif++;
                }
            }
        }

        $this->info("Status proyek diperbarui.");
        $this->info("Proyek aktif: {$jumlahAktif}");
        $this->info("Proyek selesai: {$jumlahSelesai}");
        $this->info("Email proyek aktif terkirim: {$jumlahEmailAktif}");

        return Command::SUCCESS;
    }
}
