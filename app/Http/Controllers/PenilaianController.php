<?php

namespace App\Http\Controllers;

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

    public function index()
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

        return view('admin.penilaian', compact(
            'jumlahPerProyek',
            'poins',
            'menus',
            'badges'
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
        $request->validate([
            'pengambilan_id' => 'required|exists:pengambilans,id',
            'skor' => 'required|array',
            'catatan' => 'nullable|array',
            'total_skor' => 'required|integer',
            'total_poin' => 'required|integer',
            'total_pembayaran' => 'required|numeric',
        ]);
        $totalPoin = (int) $request->total_poin;

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

            Pembayaran::create([
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

            return redirect()->back()->with('success', 'Penilaian berhasil');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors('Gagal menyimpan penilaian'. $e->getMessage());
        }
    }
}
