<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\LogPoin;
use App\Models\Pembayaran;
use App\Models\Pengambilan;
use App\Models\Subsubproyek;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Tcpdf\Fpdi;
use ZipArchive;

class PengambilanController extends Controller
{
    public function detailProyek(Subsubproyek $subsub)
    {
        $user = Auth::user();
        
        $subsub->load('subproyeks.proyeks');

        $totalHalaman = $subsub->total_halaman;

        $halamanDiambil = Pengambilan::where('subsubproyek_id', $subsub->id)
            ->where('status', 'diambil')->sum('total_halaman');

        $sisaHalaman = $totalHalaman - $halamanDiambil;

        $akanDiambil = Pengambilan::where('subsubproyek_id', $subsub->id)
            ->whereIn('status', ['diambil', 'menunggu', 'selesai'])
            ->orderBy('dari_halaman')
            ->get(['dari_halaman', 'sampai_halaman']);

        $halamanTersedia = 1;

        $role = $user->getRoleNames()->first();
        $menus = config('sidebar')[$role] ?? [];

        $badges = [];

        if ($role === 'freelancer') {
            $badges = [
                'upload' => Pengambilan::where('status', 'diambil')->where('user_id', $user->id)->count()
            ];
        }

        foreach ($akanDiambil as $range) {
            if (
                $range->dari_halaman <= $halamanTersedia &&
                $range->sampai_halaman >= $halamanTersedia
            ) {

                $halamanTersedia = $range->sampai_halaman + 1;
            }
        }

        $persentase = $totalHalaman > 0
            ? ($halamanDiambil / $totalHalaman) * 100
            : 0;

        return view(
            'freelancer.detailProyek',
            compact(
                'subsub',
                'totalHalaman',
                'halamanDiambil',
                'sisaHalaman',
                'persentase',
                'akanDiambil',
                'halamanTersedia',
                'menus',
                'badges'
            )
        );
    }

    public function downloadPdfTugas($id)
    {
        $tugas = Pengambilan::findOrFail($id);

        // Validasi: hanya user pemilik tugas yang boleh download
        if ($tugas->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke file ini.');
        }

        if (!$tugas->pdf_split || !Storage::disk('public')->exists($tugas->pdf_split)) {
            return back()->with('error', 'File PDF tidak ditemukan.');
        }

        $filePath = storage_path('app/public/' . $tugas->pdf_split);

        return response()->download($filePath);
    }

    public function downloadTemplateTugas($id)
    {
        $tugas = Pengambilan::findOrFail($id);

        // Validasi: hanya user pemilik tugas yang boleh download
        if ($tugas->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke file ini.');
        }

        if (!$tugas->xls_awal || !Storage::disk('public')->exists($tugas->xls_awal)) {
            return back()->with('error', 'File template tidak ditemukan.');
        }

        $filePath = storage_path('app/public/' . $tugas->xls_awal);

        return response()->download($filePath);
    }

    private function hitungPenaltiPembatalan($pengambilan)
    {
        $subsubproyeks = $pengambilan->subsubproyeks;
        $subproyeks = $subsubproyeks->subproyeks;
        $proyeks = $subproyeks->proyeks;

        $kualitas = (int) ($subsubproyeks->kualitas ?? 0);
        $faktorKualitas = max($kualitas / 10, 0.1);

        $dariHalaman = (int) $pengambilan->dari_halaman;
        $sampaiHalaman = (int) $pengambilan->sampai_halaman;
        $faktorHalaman = max(($sampaiHalaman - $dariHalaman) + 1, 1);

        $tanggalMulai = \Carbon\Carbon::parse($proyeks->tanggal_mulai);
        $tanggalSelesai = \Carbon\Carbon::parse($proyeks->tanggal_selesai);
        $waktuDiambil = \Carbon\Carbon::parse($pengambilan->created_at);
        $sekarang = now();

        // Total durasi proyek dalam jam (minimal 1 jam)
        $totalDurasiJam = max($tanggalMulai->diffInHours($tanggalSelesai), 1);

        // Lama tugas ditahan user dalam jam
        $jamDiambil = max($waktuDiambil->diffInHours($sekarang), 0);

        // Jika belum 1 jam tapi sudah sempat diambil, anggap minimal 1 jam
        if ($jamDiambil === 0 && $waktuDiambil->lt($sekarang)) {
            $jamDiambil = 1;
        }

        // Rasio waktu pemakaian tugas (0 - 1)
        $faktorRasio = min($jamDiambil / $totalDurasiJam, 1);

        // Penalti dasar: selalu kena meski baru ambil lalu batal
        $penaltiDasar = 1;

        // Penalti progresif
        $penaltiProgresif = $faktorHalaman * $faktorKualitas * $faktorRasio * 2;

        $penalti = max($penaltiDasar, round($penaltiDasar + $penaltiProgresif));

        return [
            'penalti' => $penalti,
            'faktor_kualitas' => round($faktorKualitas, 2),
            'faktor_halaman' => $faktorHalaman,
            'total_durasi_jam' => $totalDurasiJam,
            'jam_diambil' => $jamDiambil,
            'faktor_rasio' => round($faktorRasio, 2),
        ];
    }

    public function halamanMonitoring($id)
    {
        $role = Auth::user()->getRoleNames()->first();
        $menus = config('sidebar')[$role] ?? [];

        $badges = [];

        if ($role === 'admin') {
            $badges = [
                'penilaian' => Pengambilan::where('status', 'menunggu')->count(),

                'pembayaran' => Pembayaran::where('status', 'belum_dibayar')->count(),

                'freelancer' => User::role('freelancer')->where('status_verifikasi', 'permintaan')->count()
            ];
        }

        $subsubproyek = Subsubproyek::with(['subproyeks.proyeks'])->findOrFail($id);

        $pengambilans = Pengambilan::with('user')
            ->where('subsubproyek_id', $id)
            ->orderBy('dari_halaman', 'asc')
            ->get();

        $totalPengambil  = $pengambilans->count();
        $totalHalaman    = $pengambilans->sum('total_halaman');
        $totalPendapatan = $pengambilans->sum('total_harga');

        return view('admin.monitoring', compact(
            'subsubproyek',
            'pengambilans',
            'totalPengambil',
            'totalHalaman',
            'totalPendapatan',
            'menus',
            'badges'
        ));
    }

    private function splitPdf($pdfPath, $subsubId, $userId, $dari, $sampai)
    {
        $source = storage_path('app/public/' . $pdfPath);

        if (!file_exists($source)) {
            throw new \Exception("File PDF tidak ditemukan di: " . $source);
        }

        $folder = 'pdf_split/subsub_' . $subsubId . '/user_' . $userId;

        if (!Storage::disk('public')->exists($folder)) {
            Storage::disk('public')->makeDirectory($folder);
        }

        $pdf = new Fpdi();

        $pageCount = $pdf->setSourceFile($source);

        if ($sampai > $pageCount) {
            throw new \Exception("Halaman melebihi jumlah halaman PDF ($pageCount)");
        }

        for ($i = $dari; $i <= $sampai; $i++) {

            $template = $pdf->importPage($i);

            $size = $pdf->getTemplateSize($template);

            $pdf->AddPage(
                $size['orientation'],
                [$size['width'], $size['height']]
            );

            $pdf->useTemplate($template);
        }

        $fileName = 'split_' . $dari . '_' . $sampai . '_' . time() . '.pdf';

        $savePath = storage_path('app/public/' . $folder . '/' . $fileName);

        $pdf->Output($savePath, 'F');

        return $folder . '/' . $fileName;
    }

    public function ambilTugas(Request $request, $subsub)
    {
        $request->validate([
            'subsubproyek_id' => 'required|exists:subsubproyeks,id',
            'dari_halaman' => 'required|integer|min:1',
            'sampai_halaman' => 'required|integer|gte:dari_halaman',
        ]);

        $user = Auth::user();

        DB::beginTransaction();

        try {

            $subsub = Subsubproyek::lockForUpdate()->findOrFail($request->subsubproyek_id);

            $dari_halaman = $request->dari_halaman;
            $sampai_halaman = $request->sampai_halaman;

            if ($sampai_halaman > $subsub->total_halaman) {
                return back()
                    ->withErrors(['sampai_halaman' => 'Halaman melebihi total PDF'])
                    ->withInput();
            }

            $bentrok = Pengambilan::where('subsubproyek_id', $subsub->id)
                ->whereIn('status', ['diambil', 'menunggu', 'selesai'])
                ->where(function ($q) use ($dari_halaman, $sampai_halaman) {

                    $q->whereBetween('dari_halaman', [$dari_halaman, $sampai_halaman])
                        ->orWhereBetween('sampai_halaman', [$dari_halaman, $sampai_halaman])
                        ->orWhere(function ($q) use ($dari_halaman, $sampai_halaman) {
                            $q->where('dari_halaman', '<=', $dari_halaman)
                                ->where('sampai_halaman', '>=', $sampai_halaman);
                        });
                })->exists();

            if ($bentrok) {
                return back()
                    ->withErrors(['dari_halaman' => 'Range halaman sudah diambil freelancer lain'])
                    ->withInput();
            }

            $totalHalaman = ($sampai_halaman - $dari_halaman) + 1;

            $totalHarga = $totalHalaman * $subsub->harga_perlembar;

            $pdfSplitPath = $this->splitPdf(
                $subsub->file_pdf,
                $subsub->id,
                $user->id,
                $dari_halaman,
                $sampai_halaman
            );

            $pengambilan = Pengambilan::create([

                'subsubproyek_id' => $subsub->id,
                'user_id' => $user->id,

                'dari_halaman' => $dari_halaman,
                'sampai_halaman' => $sampai_halaman,

                'total_halaman' => $totalHalaman,

                'harga_perlembar' => $subsub->harga_perlembar,
                'total_harga' => $totalHarga,

                'pdf_split' => $pdfSplitPath,
                'xls_awal' => $subsub->file_xls,

                'status' => 'diambil'
            ]);

            DB::commit();

            // =========================
            // BUAT ZIP OTOMATIS
            // =========================

            $pdfFullPath = storage_path('app/public/' . $pdfSplitPath);

            $xlsFullPath = storage_path('app/public/' . $subsub->file_xls);

            $tempFolder = storage_path('app/temp');

            if (!File::exists($tempFolder)) {
                File::makeDirectory($tempFolder, 0755, true);
            }

            $zipName = 'tugas_' . $pengambilan->id . '_' . time() . '.zip';

            $zipPath = $tempFolder . '/' . $zipName;

            $zip = new ZipArchive();

            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {

                // PDF split
                if (file_exists($pdfFullPath)) {
                    $zip->addFile(
                        $pdfFullPath,
                        basename($pdfFullPath)
                    );
                }

                // XLS template
                if (file_exists($xlsFullPath)) {
                    $zip->addFile(
                        $xlsFullPath,
                        basename($xlsFullPath)
                    );
                }

                $zip->close();
            } else {
                throw new \Exception('Gagal membuat file ZIP');
            }

            return response()->download($zipPath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    public function halamanUploadTugas()
    {
        $user = Auth::user();
        $role = $user->getRoleNames()->first();
        $menus = config('sidebar')[$role] ?? [];

        $badges = [];

        if ($role === 'freelancer') {
            $badges = [
                'upload' => Pengambilan::where('status', 'diambil')->where('user_id', $user->id)->count()
            ];
        }

        $tugases = Pengambilan::with('subsubproyeks.subproyeks.proyeks')
            ->where('user_id', Auth::id())
            ->whereIn('status', ['diambil', 'menunggu'])
            ->orderByRaw("
                CASE 
                    WHEN status = 'diambil' THEN 1
                    WHEN status = 'menunggu' THEN 2
                    ELSE 3
                END
            ")
            ->get()->map(function ($item) {
                $subsubproyeks = $item->subsubproyeks;
                $subproyeks = $subsubproyeks->subproyeks;
                $proyeks = $subproyeks->proyeks;

                $hasilPenalti = $this->hitungPenaltiPembatalan($item);

                return [
                    'id' => $item->id,
                    'dari_halaman' => $item->dari_halaman,
                    'sampai_halaman' => $item->sampai_halaman,
                    'total_halaman' => $item->total_halaman,
                    'total_harga' => $item->total_harga,
                    'pdf_split' => $item->pdf_split,
                    'xls_awal' => $item->xls_awal,
                    'xls_hasil' => $item->xls_hasil,

                    'nama_proyek' => $proyeks->nama_proyek,
                    'nama_sub_proyek' => $subproyeks->nama_sub_proyek,
                    'tanggal_mulai' => $proyeks->tanggal_mulai,
                    'tanggal_selesai' => $proyeks->tanggal_selesai,
                    'nama_halaman' => $subsubproyeks->nama_halaman,
                    'kualitas' => $subsubproyeks->kualitas,
                    'waktu_diambil' => $item->created_at,
                    'status' => $item->status,

                    'penalti_detail' => [
                        'faktor_kualitas' => $hasilPenalti['faktor_kualitas'],
                        'faktor_halaman' => $hasilPenalti['faktor_halaman'],
                        'total_durasi_jam' => $hasilPenalti['total_durasi_jam'],
                        'jam_diambil' => $hasilPenalti['jam_diambil'],
                        'faktor_rasio' => $hasilPenalti['faktor_rasio'],
                        'penalti' => $hasilPenalti['penalti'],
                    ],
                ];
            });

        return view('freelancer.uploadTugas', compact(
            'tugases',
            'menus',
            'badges'
        ));
    }

    public function batalTugas($id)
    {
        DB::beginTransaction();

        try {

            $pengambilan = Pengambilan::with('subsubproyeks.subproyeks.proyeks', 'user')
                ->findOrFail($id);

            $user = $pengambilan->user;

            if ($pengambilan->user_id !== Auth::id()) {
                abort(403, 'Anda tidak memiliki akses untuk membatalkan tugas ini.');
            }

            if ($pengambilan->status !== 'diambil') {
                return redirect()->back()->withErrors('Tugas ini tidak dapat dibatalkan.');
            }

            if (!empty($pengambilan->xls_hasil)) {
                return redirect()->back()->withErrors('Tugas yang sudah dikirim tidak dapat dibatalkan.');
            }

            if (!$user) {
                throw new \Exception('User pengambil tugas tidak ditemukan.');
            }

            $hasilPenalti = $this->hitungPenaltiPembatalan($pengambilan);
            $penalti = (int) $hasilPenalti['penalti'];

            $pengambilan->update([
                'status' => 'dibatalkan',
            ]);

            $poinSekarang = (int) ($user->poin_level ?? 0);
            $poinBaru = max(0, $poinSekarang - $penalti);

            $levelBaru = Level::where('min_poin', '<=', $poinBaru)
                ->orderByDesc('min_poin')
                ->first();

            $dataUpdate = [
                'poin_level' => $poinBaru,
            ];

            if ($levelBaru && $levelBaru->id !== $user->level_id) {
                $dataUpdate['level_id'] = $levelBaru->id;
            }

            $user->update($dataUpdate);

            LogPoin::create([
                'pengambilan_id' => $pengambilan->id,
                'jenis' => 'kurang',
                'jumlah_poin' => $penalti,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Tugas berhasil dibatalkan. Poin Anda berkurang ' . $penalti . ' poin.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors('Gagal membatalkan tugas');
        }

        return back()->with('success', 'Tugas dibatalkan');
    }

    public function uploadTugas(Request $request, $id)
    {
        $request->validate([
            'xls_hasil' => 'required|file|mimes:xls,xlsx|max:2048',
        ]);

        $pengambilan = Pengambilan::findOrFail($id);

        $file = $request->file('xls_hasil');

        $folder = 'hasil_upload/pengambilan_' . $pengambilan->id;

        $hasilPath = $file->store($folder, 'public');

        $pengambilan->update([
            'xls_hasil' => $hasilPath,
            'status' => 'menunggu',
        ]);

        return redirect()->back()->with('success', 'Tugas Berhasil DiUpload');
    }
}
