<?php

namespace App\Http\Controllers;

use App\Events\NilaiTugas;
use App\Events\UpdateBadge;
use App\Mail\NilaiTugasMail;
use App\Models\Level;
use App\Models\LogPoin;
use App\Models\Pembayaran;
use App\Models\Pengambilan;
use App\Models\Penilaian;
use App\Models\Poin;
use App\Models\Proyek;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class PenilaianController extends Controller
{
    public function downloadHasilTugas($id)
    {
        $tugas = Pengambilan::findOrFail($id);

        if (!$tugas->xls_hasil || !Storage::disk('public')->exists($tugas->xls_hasil)) {
            return back()->with('error', 'File template tidak ditemukan.');
        }

        $filePath = storage_path('app/public/' . $tugas->xls_hasil);

        return response()->download($filePath);
    }

    public function halamanPenilaian()
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

        $jumlahPerProyek = Pengambilan::select(
            'proyeks.id',
            'proyeks.nama_proyek',
            DB::raw('COUNT(pengambilans.id) as total')
        )
            ->join('subsubproyeks', 'pengambilans.subsubproyek_id', '=', 'subsubproyeks.id')
            ->join('subproyeks', 'subsubproyeks.subproyek_id', '=', 'subproyeks.id')
            ->join('proyeks', 'subproyeks.proyek_id', '=', 'proyeks.id')
            ->where('pengambilans.status', 'menunggu')
            ->groupBy('proyeks.id', 'proyeks.nama_proyek')
            ->get();

        $poins = Poin::where('status', 'aktif')->get();
        $totalBobot = $poins->sum('bobot');
        $isBobotValid = (round($totalBobot, 2) == 1.00);

        return view('admin.penilaian', compact(
            'jumlahPerProyek',
            'poins',
            'menus',
            'badges',
            'totalBobot',
            'isBobotValid'
        ));
    }

    public function detail(Proyek $proyek)
    {
        $proyek->load([
            'subproyeks.subsubproyeks.pengambilans.user'
        ]);

        return response()->json($proyek);
    }

    public function nilaiTugas(Request $request)
    {
        $poins = Poin::where('status', 'aktif')->get();
        $totalBobot = $poins->sum('bobot');
        if (round($totalBobot, 2) != 1.00) {
            return redirect()->back()->withErrors([
                'error' => 'Penilaian gagal diproses karena total bobot aspek saat ini tidak valid (' . number_format($totalBobot, 2) . '). Silakan sesuaikan bobot di menu Pengaturan Poin terlebih dahulu.'
            ]);
        }

        $request->validate([
            'pengambilan_id' => 'required|exists:pengambilans,id',
            'skor' => 'required|array',
            'catatan' => 'nullable|array',
        ]);

        // Validasi nilai skor tiap aspek: harus antara 1–10
        $slugAktif = $poins->pluck('slug')->toArray();
        $skorInput = $request->input('skor', []);

        foreach ($slugAktif as $slug) {
            if (!isset($skorInput[$slug])) {
                return redirect()->back()->withErrors([
                    'error' => "Skor untuk aspek '{$slug}' tidak ditemukan."
                ]);
            }

            $nilai = (int) $skorInput[$slug];

            if ($nilai < 1 || $nilai > 10) {
                return redirect()->back()->withErrors([
                    'error' => "Skor untuk aspek '{$slug}' harus berada di antara 1 hingga 10."
                ]);
            }
        }

        // Validasi catatan wajib diisi jika skor < 8
        $catatanInput = $request->input('catatan', []);

        foreach ($slugAktif as $slug) {
            $nilai = (int) $skorInput[$slug];

            if ($nilai < 8 && empty($catatanInput[$slug])) {
                return redirect()->back()->withErrors([
                    'error' => "Catatan wajib diisi untuk aspek '{$slug}' karena skornya di bawah 8."
                ]);
            }
        }

        //Ambil data pengambilan beserta relasi subsubproyek untuk harga & kualitas
        $pengambilan = Pengambilan::with('subsubproyeks')->findOrFail($request->pengambilan_id);
        $subsubproyek = $pengambilan->subsubproyeks;

        $totalHalaman   = (int) $pengambilan->total_halaman;
        $hargaPerlembar = (float) $subsubproyek->harga_perlembar;
        $kualitas       = (float) $subsubproyek->kualitas;

        //Hitung semua nilai
        $skorBersih = [];
        $totalSkor  = 0;
        $weightedScore = 0.0;

        foreach ($poins as $poin) {
            $slug  = $poin->slug;
            $nilai = (int) $skorInput[$slug];
            $bobot = (float) $poin->bobot;

            $skorBersih[$slug] = $nilai;
            $totalSkor         += $nilai;
            $weightedScore     += $nilai * $bobot;
        }

        // payment  = total_halaman × harga_perlembar × (weightedScore / 10)
        $totalPembayaran = $totalHalaman * $hargaPerlembar * ($weightedScore / 10);

        // poin     = round(total_halaman × (kualitas / 10) × (weightedScore / 10) × 2)
        $totalPoin = (int) round($totalHalaman * ($kualitas / 10) * ($weightedScore / 10) * 2);

        // Simpan ke database dalam satu transaksi
        DB::beginTransaction();

        try {
            $penilaian = Penilaian::create([
                'pengambilan_id' => $request->pengambilan_id,
                'skor' => $request->skor,
                'catatan' => $request->catatan,
                'total_skor' => $request->total_skor,
                'total_poin' => $request->total_poin,
            ]);

            $pengambilan = Pengambilan::findOrFail($request->pengambilan_id);

            $pengambilan->update([
                'status' => 'selesai',
            ]);

            $user = $pengambilan->user;

            $jumlahPoin = $user->poin_level + $totalPoin;

            $levelBaru = Level::where('min_poin', '<=', $jumlahPoin)
                ->orderByDesc('min_poin')
                ->first();

            $dataUpdate = [
                'poin_level' => $jumlahPoin
            ];

            if ($levelBaru && $levelBaru->id !== $user->level_id) {
                $dataUpdate['level_id'] = $levelBaru->id;
            }

            $user->update($dataUpdate);

            $pembayaranBaru = Pembayaran::create([
                'penilaian_id' => $penilaian->id,
                'status' => 'belum_dibayar',
                'total_pembayaran' => $request->total_pembayaran,
            ]);

            LogPoin::create([
                'pengambilan_id' => $pengambilan->id,
                'jenis' => 'tambah',
                'jumlah_poin' => $totalPoin,
            ]);

            DB::commit();

            try {
                Mail::to($user->email)->queue(new NilaiTugasMail($user, $penilaian));
            } catch (\Exception $e) {
                Log::error('Gagal mengirim email penilaian: ' . $e->getMessage());
            }

            try {
                $namaSubProyek = $pengambilan->subsubproyeks->subproyeks->nama_sub_proyek ?? 'Sub Proyek';
                NilaiTugas::dispatch($penilaian, $user, $namaSubProyek);
            } catch (\Exception $e) {
                Log::error('Gagal memicu realtime penilaian: ' . $e->getMessage());
            }

            $admin = User::role('admin')->first();
            if ($admin) {
                UpdateBadge::dispatch($admin->id, 'admin');
            }

            return redirect()->route('pembayaran.detail', $pembayaranBaru->id)
                ->with('success', 'Penilaian berhasil! Silakan periksa rincian pembayaran berikut.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors('Gagal menyimpan penilaian' . $e->getMessage());
        }
    }
}
