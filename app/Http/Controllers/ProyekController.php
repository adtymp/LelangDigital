<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\TambahProyek;
use App\Models\Pembayaran;
use App\Models\Pengambilan;
use App\Models\Proyek;
use App\Models\ResetLevel;
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
                'upload' => Pengambilan::where('status', 'diambil')
                    ->where('user_id', $user->id)
                    ->count()
            ];
        }

        $resetLevel = ResetLevel::firstWhere('status', 'aktif');

        $level = $user->level;

        $proyekAktif = Proyek::where('status', 'aktif')->count();

        $proyekSelesai = Pembayaran::whereHas('penilaian.pengambilan', function ($q) {
            $q->where('user_id', Auth::id());
        })->count();

        $pendapatan = Pembayaran::where('status', 'sudah_dibayar')
            ->whereHas('penilaian.pengambilan', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->sum('total_pembayaran');

        $delayProyek = $user->level->delay_notifikasi ?? 0;

        $search = request('search');
        $kualitas = request('kualitas');
        $sort = request('sort', 'terbaru');

        $proyeks = Proyek::query()
            ->with([
                'subproyeks',
                'subproyeks.subsubproyeks' => function ($query) use ($kualitas) {

                    if ($kualitas) {
                        $query->where('kualitas', $kualitas);
                    }

                    $query->withSum([
                        'pengambilans as halaman_diambil' => function ($q) {
                            $q->whereIn('status', ['diambil', 'menunggu', 'selesai']);
                        }
                    ], 'total_halaman');
                }
            ])
            ->where('status', 'aktif')
            ->where(
                'tanggal_mulai',
                '<=',
                Carbon::now()->subMinutes($delayProyek)
            )

            // SEARCH
            ->when($search, function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    $q->where('nama_proyek', 'like', "%{$search}%")

                        ->orWhereHas('subproyeks', function ($sub) use ($search) {

                            $sub->where(
                                'nama_subproyek',
                                'like',
                                "%{$search}%"
                            );
                        })

                        ->orWhereHas(
                            'subproyeks.subsubproyeks',
                            function ($subsub) use ($search) {

                                $subsub->where(
                                    'nama_subsubproyek',
                                    'like',
                                    "%{$search}%"
                                );
                            }
                        );
                });
            })

            // FILTER KUALITAS
            ->when($kualitas, function ($query) use ($kualitas) {

                $query->whereHas(
                    'subproyeks.subsubproyeks',
                    fn($q) => $q->where('kualitas', $kualitas)
                );
            })

            // SORTING
            ->when(
                $sort === 'terbaru',
                fn($q) => $q->latest()
            )

            ->when(
                $sort === 'terlama',
                fn($q) => $q->oldest()
            )

            ->when(
                $sort === 'deadline',
                fn($q) => $q->orderBy('tanggal_selesai')
            )

            ->paginate(12)
            ->withQueryString();

        // HITUNG SISA HALAMAN
        foreach ($proyeks as $proyek) {

            foreach ($proyek->subproyeks as $subproyek) {

                foreach ($subproyek->subsubproyeks as $subsubproyek) {

                    $subsubproyek->sisa_halaman = max(
                        0,
                        $subsubproyek->total_halaman -
                            ($subsubproyek->halaman_diambil ?? 0)
                    );
                }

                // SORT HALAMAN TERSEDIA TERBANYAK
                if ($sort === 'halaman_terbanyak') {

                    $subproyek->setRelation(
                        'subsubproyeks',
                        $subproyek->subsubproyeks
                            ->sortByDesc('sisa_halaman')
                            ->values()
                    );
                }
            }
        }

        return view('freelancer.dashboard', compact(
            'user',
            'level',
            'proyeks',
            'proyekAktif',
            'proyekSelesai',
            'pendapatan',
            'menus',
            'badges',
            'resetLevel'
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

        $proyeks = Proyek::with('subproyeks.subsubproyeks.pengambilans.user')
            ->whereIn('status', ['aktif', 'menunggu'])
            ->latest()->get();

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
                'freelancer' => User::role('freelancer')
                    ->where('status_verifikasi', 'permintaan')
                    ->count()
            ];
        }

        $bulan = request('bulan');
        $tahun = request('tahun');

        $proyeks = Proyek::with([
            'subproyeks.subsubproyeks.pengambilans.user'
        ])
            ->when(request('cari'), function ($q) {
                $q->where(
                    'nama_proyek',
                    'like',
                    '%' . request('cari') . '%'
                );
            })

            ->when(request('status'), function ($q) {
                $q->where('status', request('status'));
            }, function ($q) {
                $q->whereIn('status', ['selesai', 'dibatalkan']);
            })

            ->when($bulan, function ($q) use ($bulan) {
                $q->whereMonth('tanggal_selesai', $bulan);
            })

            ->when($tahun, function ($q) use ($tahun) {
                $q->whereYear('tanggal_selesai', $tahun);
            })

            ->orderByDesc('tanggal_selesai')
            ->get()

            ->map(function ($proyek) {

                $pengambilansSelesai = $proyek->subproyeks
                    ->flatMap->subsubproyeks
                    ->flatMap->pengambilans
                    ->where('status', 'selesai');

                $proyek->total_pendapatan =
                    $pengambilansSelesai->sum('total_harga');

                $proyek->total_pengambil =
                    $pengambilansSelesai->count();

                return $proyek;
            });

        // GROUP BY BULAN + TAHUN
        $groupedProyeks = $proyeks->groupBy(function ($proyek) {

            return Carbon::parse($proyek->tanggal_selesai)
                ->translatedFormat('F Y');
        });

        $bulanOptions = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $tahunOptions = Proyek::selectRaw('YEAR(tanggal_selesai) as tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        return view(
            'admin.riwayatProyek',
            compact(
                'groupedProyeks',
                'bulanOptions',
                'tahunOptions',
                'menus',
                'badges'
            )
        );
    }

    public function getPdfInfo(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf|max:512000'
        ]);

        $path = $request->file('file')->store('temp_pdf', 'public');
        $fullPath = storage_path('app/public/' . $path);

        try {
            $config = new \Smalot\PdfParser\Config();
            $config->setRetainImageContent(false);
            $parser = new \Smalot\PdfParser\Parser([], $config);
            $pdf = $parser->parseFile($fullPath);
            $totalHalaman = count($pdf->getPages());
        } catch (\Throwable $e) {
            // Fallback: baca manual dari PDF binary dengan hemat memori
            $totalHalaman = $this->hitungHalamanPdfManual($fullPath);
        }

        return response()->json([
            'total_halaman' => $totalHalaman,
            'temp_pdf' => $path
        ]);
    }

    private function hitungHalamanPdfManual($path)
    {
        $fp = @fopen($path, 'rb');
        if (!$fp) {
            return 0;
        }

        $count = 0;
        $chunkSize = 1024 * 1024; // Baca per 1MB
        $overlap = 100;
        $buffer = '';

        while (!feof($fp)) {
            $chunk = fread($fp, $chunkSize);
            $searchArea = $buffer . $chunk;

            // Cari "/Type /Page" dan pastikan bukan "/Type /Pages" (menggunakan word boundary \b)
            $matchesCount = preg_match_all('/\/Type\s*\/Page\b/i', $searchArea);
            $count += $matchesCount;

            $buffer = substr($chunk, -$overlap);
        }
        fclose($fp);

        if ($count > 0) {
            return $count;
        }

        // Fallback Metode kedua: cari properti /Count di Pages root
        $fp = @fopen($path, 'rb');
        if (!$fp) {
            return 0;
        }
        $fileSize = filesize($path);
        $content = '';
        // Cari /Count di 1MB awal dan 1MB akhir dari file (biasanya struktur catalog berada di sana)
        if ($fileSize > 2 * 1024 * 1024) {
            $content .= fread($fp, 1024 * 1024);
            fseek($fp, -1024 * 1024, SEEK_END);
            $content .= fread($fp, 1024 * 1024);
        } else {
            $content = fread($fp, $fileSize);
        }
        fclose($fp);

        if (preg_match_all('/\/Count\s+(\d+)/i', $content, $matches)) {
            return max(array_map('intval', $matches[1]));
        }

        return 0;
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
                                try {
                                    $config = new \Smalot\PdfParser\Config();
                                    $config->setRetainImageContent(false);
                                    $parser = new \Smalot\PdfParser\Parser([], $config);
                                    $pdf = $parser->parseFile($fullPath);
                                    $totalHalamanPdf = count($pdf->getPages());
                                } catch (\Throwable $e) {
                                    $totalHalamanPdf = $this->hitungHalamanPdfManual($fullPath);
                                }
                            }
                        } elseif (!empty($ss['temp_pdf'])) {
                            $tempFileName = basename($ss['temp_pdf']);
                            $tempPath = storage_path('app/public/temp_pdf/' . $tempFileName);

                            if (file_exists($tempPath)) {
                                $pdfPath = 'pdf_upload/' . $tempFileName;
                                $fullPath = storage_path('app/public/' . $pdfPath);

                                // Pindahkan file secara instan
                                rename($tempPath, $fullPath);
                                try {
                                    $config = new \Smalot\PdfParser\Config();
                                    $config->setRetainImageContent(false);
                                    $parser = new \Smalot\PdfParser\Parser([], $config);
                                    $pdf = $parser->parseFile($fullPath);
                                    $totalHalamanPdf = count($pdf->getPages());
                                } catch (\Throwable $e) {
                                    $totalHalamanPdf = $this->hitungHalamanPdfManual($fullPath);
                                }
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

                            if (file_exists($fullPath)) {
                                try {
                                    $config = new \Smalot\PdfParser\Config();
                                    $config->setRetainImageContent(false);
                                    $parser = new \Smalot\PdfParser\Parser([], $config);
                                    $pdf = $parser->parseFile($fullPath);
                                    $totalHalamanPdf = count($pdf->getPages());
                                } catch (\Throwable $e) {
                                    $totalHalamanPdf = $this->hitungHalamanPdfManual($fullPath);
                                }
                            }
                        } elseif (!empty($ss['temp_pdf'])) { // <-- TAMBAHKAN BLOK ELSEIF INI
                            $tempFileName = basename($ss['temp_pdf']);
                            $tempPath = storage_path('app/public/temp_pdf/' . $tempFileName);

                            if (file_exists($tempPath)) {
                                $pdfPath = 'pdf_upload/' . basename($ss['temp_pdf']);
                                $fullPath = storage_path('app/public/' . $pdfPath);

                                // Pindahkan file secara instan
                                rename($tempPath, $fullPath);

                                try {
                                    $config = new \Smalot\PdfParser\Config();
                                    $config->setRetainImageContent(false);
                                    $parser = new \Smalot\PdfParser\Parser([], $config);
                                    $pdf = $parser->parseFile($fullPath);
                                    $totalHalamanPdf = count($pdf->getPages());
                                } catch (\Throwable $e) {
                                    $totalHalamanPdf = $this->hitungHalamanPdfManual($fullPath);
                                }
                            }
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
