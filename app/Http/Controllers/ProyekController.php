<?php

namespace App\Http\Controllers;

use App\Http\Requests\TambahProyek;
use App\Models\Pembayaran;
use App\Models\Pengambilan;
use App\Models\Proyek;
use App\Models\Subproyek;
use App\Models\Subsubproyek;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use setasign\Fpdi\Tcpdf\Fpdi;

class ProyekController extends Controller
{
    public function halamanFormProyek()
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

        $proyek = null;

        return view('admin.proyekForm', compact(
            'proyek',
            'menus',
            'badges'
        ));
    }

    public function freelanceDashboard()
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

        $proyekAktif = Proyek::where('status', 'aktif')->count();

        $proyekSelesai = Pembayaran::whereHas('penilaian.pengambilan', function ($q) {
            $q->where('user_id', Auth::id());
        })->count();

        $pendapatan = Pembayaran::where('status', 'sudah_dibayar')->sum('total_pembayaran');

        $delayProyek = $user->level->delay_notifikasi ?? 0;

        $proyeks = Proyek::where('status', 'aktif')
            ->where('tanggal_mulai', '<=', Carbon::now()->subMinutes($delayProyek))
            ->latest()
            ->get();

        return view('freelancer.dashboard', compact(
            'user',
            'proyeks',
            'proyekAktif',
            'proyekSelesai',
            'pendapatan',
            'menus',
            'badges'
        ));
    }

    public function adminDashboard()
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

        $proyeks = Proyek::with('subproyeks.subsubproyeks')->where('status', ['aktif', 'menunggu'])->latest()->get();

        $totalProyek = Proyek::where('status', ['aktif', 'menunggu'])->count();

        $proyekAktif = Proyek::where('status', 'aktif')->count();

        $freelancer = User::role('freelancer')->count();

        $upah = Pembayaran::sum('total_pembayaran');


        return view('admin.dashboard', compact(
            'proyeks',
            'totalProyek',
            'proyekAktif',
            'freelancer',
            'upah',
            'menus',
            'badges'
        ));
    }

    public function riwayatProyek()
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

        $proyeks = Proyek::with([
            'subproyeks.subsubproyeks.pengambilans.user'
        ])->where('status', 'selesai')->get();

        return view('admin.riwayatProyek', compact(
            'proyeks',
            'menus',
            'badges'
        ));
    }

    public function getPdfInfo(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf|max:20480'
        ]);

        $path = $request->file('file')->store('temp_pdf', 'public');
        $fullPath = storage_path('app/public/' . $path);

        $pdf = new Fpdi();
        $totalHalaman = $pdf->setSourceFile($fullPath);

        return response()->json([
            'total_halaman' => $totalHalaman
        ]);
    }

    public function tambahProyek(TambahProyek $request)
    {
        DB::beginTransaction();

        try {
            $proyek = Proyek::create([
                'nama_proyek' => $request->nama_proyek,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
            ]);

            foreach ($request->sub_proyek as $sub) {

                $subProyek = Subproyek::create([
                    'proyek_id' => $proyek->id,
                    'nama_sub_proyek' => $sub['nama'],
                    'total_halaman' => 0,
                ]);

                $totalHalamanSub = 0;

                if (!empty($sub['sub_sub'])) {
                    foreach ($sub['sub_sub'] as $ss) {

                        $pdfPath = null;
                        $totalHalamanPdf = 0;

                        if (!empty($ss['file_pdf'])) {

                            $pdfPath = $ss['file_pdf']->store('pdf_upload', 'public');
                            $fullPath = storage_path('app/public/' . $pdfPath);

                            if (file_exists($fullPath)) {
                                $pdf = new Fpdi();
                                $totalHalamanPdf = $pdf->setSourceFile($fullPath);
                            }
                        }

                        $xlsPath = null;
                        if (!empty($ss['file_xls'])) {
                            $xlsPath = $ss['file_xls']->store('xls_upload', 'public');
                        }

                        Subsubproyek::create([
                            'subproyek_id' => $subProyek->id,
                            'file_pdf' => $pdfPath,
                            'file_xls' => $xlsPath,
                            'nama_halaman' => $ss['nama_halaman'],
                            'total_halaman' => $totalHalamanPdf,
                            'harga_perlembar' => $ss['harga'],
                            'kualitas' => $ss['kualitas'],
                        ]);

                        $totalHalamanSub += $totalHalamanPdf;
                    }
                }

                $subProyek->update([
                    'total_halaman' => $totalHalamanSub
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Proyek berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function editProyek(Proyek $proyek)
    {
        $proyek->load('subproyeks.subsubproyeks');

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

        return view('admin.proyekForm', compact(
            'proyek',
            'menus',
            'badges'
        ));
    }

    public function updateProyek(Request $request, Proyek $proyek)
    {
        DB::beginTransaction();

        try {
            $proyek->update([
                'nama_proyek' => $request->nama_proyek,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
            ]);

            foreach ($request->sub_proyek as $sub) {

                if (!empty($sub['id'])) {
                    $subProyek = Subproyek::find($sub['id']);
                    $subProyek->update([
                        'nama_sub_proyek' => $sub['nama']
                    ]);
                } else {
                    $subProyek = Subproyek::create([
                        'proyek_id' => $proyek->id,
                        'nama_sub_proyek' => $sub['nama']
                    ]);
                }

                $totalHalamanSub = 0;

                if (!empty($sub['sub_sub'])) {

                    foreach ($sub['sub_sub'] as $ss) {

                        $subsub = null;

                        if (!empty($ss['id'])) {
                            $subsub = Subsubproyek::find($ss['id']);
                        }

                        $pdfPath = $subsub->file_pdf ?? null;
                        $totalHalamanPdf = $subsub->total_halaman ?? 0;

                        if (!empty($ss['file_pdf'])) {

                            $pdfPath = $ss['file_pdf']->store('pdf_upload', 'public');

                            $fullPath = storage_path('app/public/' . $pdfPath);

                            $pdf = new Fpdi();
                            $totalHalamanPdf = $pdf->setSourceFile($fullPath);
                        }

                        $xlsPath = $subsub->file_xls ?? null;

                        if (!empty($ss['file_xls'])) {
                            $xlsPath = $ss['file_xls']->store('xls_upload', 'public');
                        }

                        $data = [
                            'subproyek_id' => $subProyek->id,
                            'file_pdf' => $pdfPath,
                            'file_xls' => $xlsPath,
                            'nama_halaman' => $ss['nama_halaman'],
                            'total_halaman' => $totalHalamanPdf,
                            'harga_perlembar' => $ss['harga'],
                            'kualitas' => $ss['kualitas'],
                            'status' => 'aktif'
                        ];

                        if ($subsub) {
                            $subsub->update($data);
                        } else {
                            Subsubproyek::create($data);
                        }

                        $totalHalamanSub += $totalHalamanPdf;
                    }
                }

                $subProyek->update([
                    'total_halaman' => $totalHalamanSub
                ]);
            }

            DB::commit();

            return redirect()->route('dashboard.admin')->with('success', 'Proyek berhasil diperbarui');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->withErrors('error', $e->getMessage());
        }

        return redirect()->route('dashboard.admin')->with('success', 'Proyek berhasil diperbarui');
    }

    public function hapusProyek(Proyek $proyek)
    {
        $proyek->delete();

        return redirect()->back()->with('success', 'Proyek berhasil dihapus');
    }
}
