<?php

namespace App\Http\Controllers;

use App\Mail\AktifUserMail;
use App\Mail\NonaktifUserMail;
use App\Mail\TerimaUserMail;
use App\Mail\TolakUserMail;
use App\Models\Level;
use App\Models\LogPoin;
use App\Models\Pembayaran;
use App\Models\Pengambilan;
use App\Models\Penilaian;
use App\Models\Portofolio;
use App\Models\Rekening;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ManajemenFreelancerController extends Controller
{
    public function filePortofolio(User $user)
    {
        $portofolio = $user->portofolio;
    
        if (!$portofolio) {
            abort(404);
        }
    
        if (!Storage::disk('public')->exists($portofolio->file_path)) {
            abort(404);
        }
    
        return Storage::disk('public')->response($portofolio->file_path);
    }

    public function halamanFreelancer(Request $request)
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

        $statusVerifikasi = User::role('freelancer')
            ->select('status_verifikasi', DB::raw('count(*) as total'))
            ->groupBy('status_verifikasi')
            ->pluck('total', 'status_verifikasi');

        $statusAkun = User::role('freelancer')
            ->select('status_akun', DB::raw('count(*) as total'))
            ->groupBy('status_akun')
            ->pluck('total', 'status_akun');

        $statusAksiVerifikasi = [
            'permintaan' => ['diterima', 'ditolak'],
            'diterima'   => ['ditolak'],
            'ditolak'    => ['permintaan'],
        ];

        $statusAksiAkun = [
            'aktif'     => ['nonaktif'],
            'nonaktif'  => ['aktif'],
        ];

        $query = User::role('freelancer')->with('level', 'portofolio',  'rekening');

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status_verifikasi') && $request->status_verifikasi !== 'all') {
            $query->where('status_verifikasi', $request->status_verifikasi);
        }

        if ($request->filled('status_akun') && $request->status_akun !== 'all') {
            $query->where('status_akun', $request->status_akun);
        }

        $freelancers = $query->latest()
            ->paginate(10)
            ->through(function ($user) {

                $user->portofolio_url = $user->portofolio
                    ? route('portofolio.download', $user)
                    : null;

                return $user;
            })
            ->withQueryString();

        return view('admin.viewFreelancer', compact(
            'freelancers',
            'statusVerifikasi',
            'statusAkun',
            'statusAksiVerifikasi',
            'statusAksiAkun',
            'menus',
            'badges'
        ));
    }

    public function updateStatusVerifikasi(Request $request, User $user)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:permintaan,diterima,ditolak'
        ]);

        $statusVerifikasi = $request->status_verifikasi;

        $statusAkun = $statusVerifikasi === 'diterima' ? 'aktif' : 'nonaktif';

        $user->update([
            'status_verifikasi' => $statusVerifikasi,
            'status_akun' => $statusAkun
        ]);

        if ($statusVerifikasi === 'diterima') {
            Mail::to($user->email)->queue(new TerimaUserMail($user));
        } elseif ($statusVerifikasi === 'ditolak') {
            Mail::to($user->email)->queue(new TolakUserMail($user));
        }

        return back()->with('success', "Status freelancer telah diubah {$statusVerifikasi}");
    }

    public function updateStatusAkun(Request $request, User $user)
    {
        $request->validate([
            'status_akun' => 'required|in:aktif,nonaktif'
        ]);

        if ($user->status_verifikasi !== 'diterima') {
            return back()->withErrors([
                'error' => 'Status akun hanya dapat diubah jika freelancer sudah diterima.'
            ]);
        }

        $statusAkun = $request->status_akun;

        $user->update([
            'status_akun' => $statusAkun,
        ]);

        if ($statusAkun === 'aktif') {
            Mail::to($user->email)->queue(new AktifUserMail($user));
        } elseif ($statusAkun === 'nonaktif') {
            Mail::to($user->email)->queue(new NonaktifUserMail($user));
        }

        return back()->with('success', "Status akun freelancer berhasil diubah menjadi {$statusAkun}");
    }

    public function profilSaya()
    {
        $user = User::with(
            'level',
            'rekening',
            'portofolio',
            'pengambilans'
        )->find(Auth::id());

        $role = $user->getRoleNames()->first();
        $menus = config('sidebar')[$role] ?? [];

        $badges = [];

        if ($role === 'freelancer') {
            $badges = [
                'upload' => Pengambilan::where('status', 'diambil')->where('user_id', $user->id)->count(),
            ];
        }

        $totalProyek = $user->pengambilans()
            ->where('status', 'selesai')->count();

        $totalProyekSelesai = $user->pengambilans()
            ->where('status', 'selesai')
            ->whereHas('penilaians.pembayarans', function ($q) {
                $q->where('status', 'sudah_dibayar');
            })
            ->count();

        $levels = Level::orderBy('nama_level')->get();

        $levelSekarang = $user->level;

        $levelSelanjutnya = Level::where('min_poin', '>', $user->poin_level)
            ->orderBy('min_poin')->first();

        $poinSekarang = $levelSekarang?->min_poin;

        if ($levelSelanjutnya) {
            $poinSelanjutnya = $levelSelanjutnya->min_poin;

            $progress = $poinSelanjutnya > $poinSekarang
                ? (($user->poin_level - $poinSekarang) / ($poinSelanjutnya - $poinSekarang)) * 100
                : 100;
        } else {
            $poinSelanjutnya = $user->poin_level;
            $progress = 100;
        }

        $rataRataSkor = Penilaian::whereHas('pengambilan', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->avg('total_skor');

        $rataRataSkor = round($rataRataSkor, 1);

        $totalTugas = $user->pengambilans()->count();

        $tugasSelesai = $user->pengambilans()
            ->where('status', 'selesai')
            ->count();

        $tingkatKeberhasilan = $totalTugas > 0
            ? ($tugasSelesai / $totalTugas) * 100
            : 0;

        $tingkatKeberhasilan = round($tingkatKeberhasilan, 0);

        $totalPendapatan = Pembayaran::whereHas('penilaian.pengambilan', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->where('status', 'sudah_dibayar')
            ->sum('total_pembayaran');

        $logpoins = LogPoin::with([
            'pengambilan.subsubproyeks.subproyeks.proyeks'
        ])
            ->whereHas('pengambilan', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->latest()
            ->get()
            ->map(function ($log) {
                $pengambilan = $log->pengambilan;
                $subsubproyek = $log->pengambilan?->subsubproyeks;
                $subproyek = $subsubproyek?->subproyeks;
                $proyek = $subproyek?->proyeks;

                $log->nama_proyek = $proyek?->nama_proyek;
                $log->nama_sub_proyek = $subproyek?->nama_sub_proyek;
                $log->dari_halaman = $pengambilan?->dari_halaman;
                $log->sampai_halaman = $pengambilan?->sampai_halaman;

                return $log;
            });

        return view('freelancer.profil', compact(
            'user',
            'totalProyek',
            'totalProyekSelesai',
            'levels',
            'levelSekarang',
            'levelSelanjutnya',
            'poinSelanjutnya',
            'progress',
            'rataRataSkor',
            'tingkatKeberhasilan',
            'totalPendapatan',
            'logpoins',
            'menus',
            'badges'
        ));
    }

    public function updateTelepon(Request $request, User $user)
    {
        $request->validate(
            [
                'no_telp' => ['required', 'string', 'min:10', 'max:20', 'regex:/^(08|\+628)[0-9]{8,15}$/']
            ],
            [
                'no_telp.required' => 'Nomor telepon wajib diisi.',
                'no_telp.min' => 'Nomor telepon terlalu pendek.',
                'no_telp.max' => 'Nomor telepon terlalu panjang.',
                'no_telp.regex' => 'Format nomor telepon harus dimulai dengan 08 atau +628.',
            ]
        );

        $user->update([
            'no_telp' => $request->no_telp
        ]);

        return back()->with('success', "Nomor telepon berhasil diubah");
    }

    public function updatePassword(Request $request, User $user)
    {
        $request->validate(
            [
                'password_lama' => ['required'],
                'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()]
            ],
            [
                'password_lama.required' => 'Password lama wajib diisi.',

                'password.required' => 'Password baru wajib diisi.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
                'password.min' => 'Password minimal 8 karakter.',
                'password.mixed_case' => 'Password harus memiliki huruf besar dan kecil.'
            ]
        );

        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->withErrors([
                'password_lama' => 'Password lama tidak sesuai.'
            ])->withInput();
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);


        return back()->with('success', "Password berhasil diubah");
    }

    public function updateRekening(Request $request, User $user)
    {
        $request->validate(
            [
                'nama_bank' => ['required', 'string', 'max:100'],
                'no_akun'   => ['required', 'string', 'min:6', 'max:30', 'regex:/^[0-9]+$/'],
                'nama_akun' => ['required', 'string', 'max:100'],
            ],
            [
                'nama_bank.required' => 'Nama bank wajib diisi.',
                'no_akun.required'   => 'Nomor rekening wajib diisi.',
                'no_akun.regex'      => 'Nomor rekening hanya boleh berisi angka.',
                'no_akun.min'        => 'Nomor rekening terlalu pendek.',
                'nama_akun.required' => 'Nama pemilik rekening wajib diisi.',
            ]
        );

        Rekening::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nama_bank' => $request->nama_bank,
                'no_akun'   => $request->no_akun,
                'nama_akun' => $request->nama_akun,
            ]
        );

        return back()->with('success', 'Data rekening berhasil disimpan.');
    }

    public function updatePortofolio(Request $request, User $user)
    {
        $request->validate(
            [
                'type'      => ['required', 'in:file,link'],
                'file_path' => ['required_if:type,file', 'nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
                'link_url'  => ['required_if:type,link', 'nullable', 'url', 'max:255'],
            ],
            [
                'type.required'         => 'Tipe portofolio wajib dipilih.',
                'file_path.required_if' => 'File portofolio wajib diunggah.',
                'file_path.mimes'       => 'File harus berupa PDF, JPG, atau PNG.',
                'file_path.max'         => 'Ukuran file maksimal 5 MB.',
                'link_url.required_if'  => 'URL portofolio wajib diisi.',
                'link_url.url'          => 'Format URL tidak valid.',
            ]
        );

        $portofolio = $user->portofolio;

        $data = [
            'type'      => $request->type,
            'file_path' => null,
            'link_url'  => null,
        ];

        if ($request->type === 'file' && $request->hasFile('file_path')) {
            // Hapus file lama dari storage jika ada
            if ($portofolio && $portofolio->file_path) {
                Storage::disk('public')->delete($portofolio->file_path);
            }
            $data['file_path'] = $request->file('file_path')->store('portofolio', 'public');
        } else {
            $data['link_url'] = $request->link_url;
        }

        if ($portofolio) {
            $portofolio->update($data);
        } else {
            $user->portofolio()->create($data);
        }

        return back()->with('success', 'Portofolio berhasil diperbarui.');
    }
}
