<?php

namespace App\Http\Controllers;

use App\Events\UpdateBadge;
use App\Events\UploadPembayaran;
use App\Mail\UploadPembayaranMail;
use App\Models\Pembayaran;
use App\Models\Pengambilan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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

    public function detailPembayaran($id)
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

        // Cari data pembayaran dengan relasi lengkap
        $pembayaran = Pembayaran::with([
            'penilaian.pengambilan.user.rekening',
            'penilaian.pengambilan.user.level',
            'penilaian.pengambilan.subsubproyeks.subproyeks.proyeks',
        ])->findOrFail($id);

        // Extract object untuk mempermudah penulisan di Blade (meniru struktur mapping Anda)
        $penilaian   = $pembayaran->penilaian;
        $pengambilan = $penilaian?->pengambilan;
        $subsub      = $pengambilan?->subsubproyeks;
        $sub         = $subsub?->subproyeks;
        $proyek      = $sub?->proyeks;
        $user        = $pengambilan?->user;
        $rekening    = $user?->rekening;
        $level       = $user?->level;

        return view('admin.detail-pembayaran', compact(
            'pembayaran',
            'penilaian',
            'pengambilan',
            'subsub',
            'sub',
            'proyek',
            'user',
            'rekening',
            'level',
            'menus',
            'badges'
        ));
    }

    public function halamanPembayaran(Request $request)
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

        // Ambil input filter dari request GET
        $search = $request->input('search');
        $status = $request->input('status');
        $bank = $request->input('bank');
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        // Query Dasar Pembayaran
        $query = Pembayaran::with([
            'penilaian.pengambilan.user.rekening',
            'penilaian.pengambilan.user.level',
            'penilaian.pengambilan.user.portofolio',
            'penilaian.pengambilan.subsubproyeks.subproyeks.proyeks',
            'penilaian.pengambilan.user'
        ]);

        // Fitur Pencarian: Nama Freelancer, Email, Proyek, Sub-Proyek, Halaman
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('penilaian.pengambilan.user', function ($inner) use ($search) {
                    $inner->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                })
                    ->orWhereHas('penilaian.pengambilan.subsubproyeks.subproyeks.proyeks', function ($inner) use ($search) {
                        $inner->where('nama_proyek', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('penilaian.pengambilan.subsubproyeks.subproyeks', function ($inner) use ($search) {
                        $inner->where('nama_sub_proyek', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('penilaian.pengambilan.subsubproyeks', function ($inner) use ($search) {
                        $inner->where('nama_halaman', 'like', '%' . $search . '%');
                    });
            });
        }

        // Filter: Status Pembayaran
        if ($status) {
            $query->where('status', $status);
        }

        // Filter: Nama Bank Freelancer
        if ($bank) {
            $query->whereHas('penilaian.pengambilan.user.rekening', function ($q) use ($bank) {
                $q->where('nama_bank', $bank);
            });
        }

        // Filter: Bulan
        if ($bulan) {
            $query->whereMonth('updated_at', $bulan);
        }

        // Filter: Tahun
        if ($tahun) {
            $query->whereYear('updated_at', $tahun);
        }

        // Eksekusi Query dan Mapping Data
        $pembayarans = $query->orderByRaw("
                CASE
                    WHEN status = 'belum_dibayar' THEN 0
                    WHEN status = 'sudah_dibayar' THEN 1
                    ELSE 2
                END
            ")
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function ($item) {
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
                        'nama_level' => $level->nama_level ?? '-',
                    ],
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'status' => $user->status_akun,
                        'poin_level' => $user->poin_level,
                    ],
                    'rekening' => [
                        'nama_bank' => $rekening->nama_bank ?? '-',
                        'no_akun' => $rekening->no_akun ?? '-',
                        'nama_akun' => $rekening->nama_akun ?? '-',
                    ],
                    'portofolio' => [
                        'type' => $portofolio->type ?? '-',
                        'link_url' => $portofolio->link_url ?? null,
                        'file_path' => $portofolio->file_path ?? null,
                    ],
                    'pengambilan' => [
                        'id' => $pengambilan->id,
                        'dari_halaman' => $pengambilan->dari_halaman,
                        'sampai_halaman' => $pengambilan->sampai_halaman,
                        'xls_hasil' => $pengambilan->xls_hasil,
                    ],
                    'penilaian' => [
                        'skor' => $penilaian?->skor,
                        'catatan' => $penilaian?->catatan,
                        'total_skor' => $penilaian?->total_skor,
                        'total_poin' => $penilaian?->total_poin,
                    ],
                    'nama_proyek' => $proyek->nama_proyek ?? '-',
                    'nama_sub_proyek' => $sub->nama_sub_proyek ?? '-',
                    'nama_halaman' => $subsub->nama_halaman ?? '-',
                    'total_pembayaran' => $item->total_pembayaran,
                    'status' => $item->status,
                    'bukti_transfer' => $item->bukti_transfer,
                ];
            });

        // Statistik Global (Tetap tidak terpengaruh filter agar admin mengetahui totalitas data)
        $belumDibayar = Pembayaran::where('status', 'belum_dibayar')->count();
        $sudahDibayar = Pembayaran::where('status', 'sudah_dibayar')->count();
        $pengupahan = Pembayaran::sum('total_pembayaran');

        // Ambil daftar nama bank secara unik (dinamis dari database) untuk opsi filter
        $availableBanks = \App\Models\Rekening::whereNotNull('nama_bank')
            ->where('nama_bank', '!=', '')
            ->distinct()
            ->pluck('nama_bank')
            ->toArray();

        return view('admin.pembayaran', compact(
            'pembayarans',
            'belumDibayar',
            'sudahDibayar',
            'pengupahan',
            'menus',
            'badges',
            'availableBanks'
        ));
    }

    public function halamanRiwayat(Request $request)
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

        // Set filter bulan & tahun default ke bulan aktif saat ini jika filter kosong (akses pertama kali)
        $hasFilters = $request->has('search') || $request->has('bulan') || $request->has('tahun');
        if (!$hasFilters) {
            $bulan = date('m');
            $tahun = date('Y');
            $request->merge(['bulan' => $bulan, 'tahun' => $tahun]);
        } else {
            $bulan = $request->input('bulan');
            $tahun = $request->input('tahun');
        }
        $search = $request->input('search');

        $query = Pembayaran::with([
            'penilaian.pengambilan.user.rekening',
            'penilaian.pengambilan.subsubproyeks.subproyeks.proyeks'
        ])
            ->whereHas('penilaian.pengambilan', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });

        // Fitur Pencarian berdasarkan nama proyek, sub-proyek, atau halaman
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('penilaian.pengambilan.subsubproyeks.subproyeks.proyeks', function ($inner) use ($search) {
                    $inner->where('nama_proyek', 'like', '%' . $search . '%');
                })
                    ->orWhereHas('penilaian.pengambilan.subsubproyeks.subproyeks', function ($inner) use ($search) {
                        $inner->where('nama_sub_proyek', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('penilaian.pengambilan.subsubproyeks', function ($inner) use ($search) {
                        $inner->where('nama_halaman', 'like', '%' . $search . '%');
                    });
            });
        }

        // Filter berdasarkan Bulan
        if ($bulan) {
            $query->whereMonth('updated_at', $bulan);
        }

        // Filter berdasarkan Tahun
        if ($tahun) {
            $query->whereYear('updated_at', $tahun);
        }

        $pembayarans = $query->latest()->get();

        // Hitung statistik berdasarkan query yang telah disaring (clone query)
        $totalProyek = (clone $query)->count();
        $belumDibayar = (clone $query)->where('status', 'belum_dibayar')->count();
        $sudahDibayar = (clone $query)->where('status', 'sudah_dibayar')->count();
        $totalPendapatan = (clone $query)->where('status', 'sudah_dibayar')->sum('total_pembayaran');

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
            'id' => 'required|uuid|exists:pembayarans,id',
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

        $freelancer = $pembayaran->penilaian->pengambilan->user;

        // Kirim Email Ke Freelancer
        try {
            Mail::to($freelancer->email)->queue(new UploadPembayaranMail($freelancer, $pembayaran));
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email pembayaran: ' . $e->getMessage());
        }

        // Trigger Realtime Notification Ke Freelancer
        try {
            $namaProyek = $pembayaran->penilaian->pengambilan->subsubproyeks->subproyeks->proyeks->nama_proyek ?? 'Proyek';
            UploadPembayaran::dispatch($pembayaran, $freelancer, $namaProyek);
        } catch (\Exception $e) {
            Log::error('Gagal memicu realtime pembayaran: ' . $e->getMessage());
        }

        $admin = User::role('admin')->first();
        if ($admin) {
            UpdateBadge::dispatch($admin->id, 'admin');
        }

        return redirect()->back()->with('success', 'Bukti transfer berhasil diupload');
    }
}
