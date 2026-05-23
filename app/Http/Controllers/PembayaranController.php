<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pengambilan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
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

    public function halamanPembayaran()
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
        
        $sudahDibayar = Pembayaran::where('status', 'sudah_dibayar')->count();

        $belumDibayar = Pembayaran::where('status', 'belum_dibayar')->count();

        $pengupahan = Pembayaran::sum('total_pembayaran');

        $pembayarans = Pembayaran::with(
            'penilaian.pengambilan.user.rekening',
            'penilaian.pengambilan.user.level',
            'penilaian.pengambilan.user.portofolio',
            'penilaian.pengambilan.subsubproyeks.subproyeks.proyeks',
            'penilaian.pengambilan.user'
        )->get()->map(function ($item) {
            $penilaian = $item->penilaian;
            $pengambilan = $penilaian->pengambilan;
            $subsub = $pengambilan->subsubproyeks;
            $sub = $subsub->subproyeks;
            $proyek = $sub->proyeks;
            $user = $pengambilan->user;
            $portofolio = $user->portofolio;
            $rekening = $user->rekening;
            $level = $user->level;

            return [
                'id' => $item->id,
                'level' => [
                    'nama_level' => $level->nama_level,
                ],
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'status' => $user->status_akun,
                    'poin_level' => $user->poin_level,
                ],
                'rekening' => [
                    'nama_bank' => $rekening->nama_bank,
                    'no_akun' => $rekening->no_akun,
                    'nama_akun' => $rekening->nama_akun,
                ],
                'portofolio' => [
                    'type' => $portofolio->type,
                    'link_url' => $portofolio->link_url,
                    'file_path' => $portofolio->file_path,
                ],
                'pengambilan' => [
                    'id' => $pengambilan->id,
                    'dari_halaman' => $pengambilan->dari_halaman,
                    'sampai_halaman' => $pengambilan->sampai_halaman,
                    'xls_hasil' => $pengambilan->xls_hasil,
                ],

                'nama_proyek' => $proyek->nama_proyek,
                'nama_sub_proyek' => $sub->nama_sub_proyek,
                'nama_halaman' => $subsub->nama_halaman,
                'total_pembayaran' => $item->total_pembayaran,
                'status' => $item->status,
                'bukti_transfer' => $item->bukti_transfer,
            ];
        });

        return view('admin.pembayaran', compact(
            'pembayarans',
            'belumDibayar',
            'sudahDibayar',
            'pengupahan',
            'menus',
            'badges'
        ));
    }

    public function halamanRiwayat()
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

        $query = Pembayaran::with([
            'penilaian.pengambilan.user.rekening',
            'penilaian.pengambilan.subsubproyeks.subproyeks.proyeks'
        ])
            ->whereHas('penilaian.pengambilan', function ($q) {
                $q->where('user_id', Auth::id());
            });

        $pembayarans = (clone $query)->latest()->get();

        $totalProyek = (clone $query)->count();

        $belumDibayar = (clone $query)
            ->where('status', 'belum_dibayar')
            ->count();

        $sudahDibayar = (clone $query)
            ->where('status', 'sudah_dibayar')
            ->count();

        $totalPendapatan = (clone $query)
            ->where('status', 'sudah_dibayar')
            ->sum('total_pembayaran');

        return view('freelancer.riwayat', compact(
            'pembayarans',
            'totalProyek',
            'sudahDibayar',
            'belumDibayar',
            'totalPendapatan',
            'menus',
            'badges'
        ));
    }

    public function uploadPembayaran(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:pembayarans,id',
            'bukti_transfer' => 'required|file|mimes:jpg,png,pdf|max:2048'
        ]);

        $pembayaran = Pembayaran::findOrFail($request->id);

        $file = $request->file('bukti_transfer');

        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('bukti_transfer', $fileName, 'public');

        if ($pembayaran->bukti_transfer) {
            Storage::disk('public')->delete($pembayaran->bukti_transfer);
        }

        $pembayaran->update([
            'bukti_transfer' => $filePath,
            'status' => 'sudah_dibayar',
        ]);

        return redirect()->back()->with('success', 'Bukti transfer berhasil diupload');
    }
}
