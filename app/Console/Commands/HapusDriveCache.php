<?php

namespace App\Console\Commands;

use App\Models\Subsubproyek;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class HapusDriveCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:hapus-drive-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cachePath = storage_path('app/temp/drive_cache');
        if (!File::exists($cachePath)) {
            $this->info('Folder cache tidak ditemukan.');
            return;
        }
        $files = File::files($cachePath);
        $now = Carbon::now()->startOfDay();
        $deletedCount = 0;
        foreach ($files as $file) {
            // Ambil nama file tanpa ekstensi .pdf (ini adalah Google Drive File ID)
            $fileId = pathinfo($file, PATHINFO_FILENAME);
            // Cari data subsubproyek di database yang memiliki file_pdf ini
            $subsub = Subsubproyek::where('file_pdf', $fileId)->first();
            // Skenario 1: Jika data proyeknya sudah tidak ada di database (data yatim/terhapus)
            if (!$subsub) {
                File::delete($file);
                $deletedCount++;
                continue;
            }
            // Dapatkan tanggal selesai proyek (Relasi: Subsubproyek -> Subproyek -> Proyek)
            $proyek = $subsub->subproyeks->proyeks ?? null;
            if ($proyek) {
                $tanggalSelesai = Carbon::parse($proyek->tanggal_selesai)->startOfDay();
                // Skenario 2: Hapus file jika hari ini sudah melewati tanggal selesai proyek
                if ($now->gt($tanggalSelesai)) {
                    File::delete($file);
                    $deletedCount++;
                }
            } else {
                // Skenario 3: Jika relasi proyek tidak ditemukan, hapus filenya
                File::delete($file);
                $deletedCount++;
            }
        }
        $this->info("Pembersihan selesai. {$deletedCount} file PDF Google Drive yang kadaluwarsa berhasil dihapus.");
    }
}
