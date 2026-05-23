<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUser;
use App\Mail\NotifikasiPendaftaran;
use App\Models\Level;
use App\Models\Portofolio;
use App\Models\Rekening;
use App\Models\User;
use App\Rules\TerimaDomainPortofolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    private function redirectByRole($user)
    {
        if ($user->hasRole('admin')) {
            return redirect()->route('dashboard.admin');
        }

        if ($user->hasRole('freelancer')) {

            if (
                $user->status_verifikasi !== 'diterima' ||
                $user->status_akun !== 'aktif'
            ) {
                Auth::logout();

                $pesan = match (true) {
                    $user->status_verifikasi === 'permintaan' =>
                    'Akun Anda masih menunggu persetujuan admin.',

                    $user->status_verifikasi === 'ditolak' =>
                    'Pendaftaran Anda ditolak admin. Silakan daftar ulang.',

                    $user->status_verifikasi === 'diterima' && $user->status_akun === 'nonaktif' =>
                    'Akun Anda sedang dinonaktifkan. Silakan hubungi admin.',

                    default =>
                    'Akun Anda tidak dapat mengakses sistem.',
                };

                return redirect()->route('login')->withErrors(['email' => $pesan]);
            }

            if (!$user->rekening) {
                return redirect()->route('rekening.form');
            }

            return redirect()->route('dashboard.freelance');
        }

        Auth::logout();

        return redirect()->route('login')->withErrors('Akun anda tidak terdaftar');
    }

    public function index()
    {
        $terimaDomain = config('portofolio.terima_domain');

        if (!Auth::check()) {
            return view('loginregister', compact('terimaDomain'));
        }
        return $this->redirectByRole(Auth::user());
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $cek = $request->only('email', 'password');

        if (!Auth::attempt($cek)) {
            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        return $this->redirectByRole(Auth::user());
    }

    public function register(RegisterUser $request)
    {
        $request->validated();

        DB::beginTransaction();

        try {

            $level = Level::orderBy('nama_level')->first();

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'no_telp' => $request->no_telp,
                    'level_id' => $level?->id,
                    'status_akun' => 'nonaktif',
                    'status_verifikasi' => 'permintaan',
                ]);

                $user->assignRole('freelancer');
            } else {
                if ($user->status_verifikasi === 'permintaan') {
                    return back()->withErrors([
                        'email' => 'Akun ini masih dalam proses verifikasi admin.'
                    ]);
                }
                if ($user->status_verifikasi === 'diterima') {
                    return back()->withErrors([
                        'email' => 'Email ini sudah terdaftar dan akun telah diverifikasi.'
                    ]);
                }
                if ($user->status_verifikasi === 'ditolak') {
                    $user->update([
                        'name' => $request->name,
                        'password' => Hash::make($request->password),
                        'no_telp' => $request->no_telp,
                        'status_akun' => 'nonaktif',
                        'status_verifikasi' => 'permintaan',
                    ]);
                }
            }

            $portofolio = [
                'user_id' => $user->id,
                'type'    => $request->type,
            ];

            if ($request->type === 'file') {
                $portofolio['file_path'] =
                    $request->file('file_path')->store('portfolios', 'public');
                $portofolio['link_url'] = null;
            } else {
                $portofolio['link_url'] = $request->link_url;
                $portofolio['file_path'] = null;
            }

            Portofolio::where('user_id', $user->id)->delete();

            Portofolio::create($portofolio);

            Mail::to(env('ADMIN_EMAIL'))->queue(new NotifikasiPendaftaran($user));

            DB::commit();

            return back()->with(
                'success',
                'Permintaan sukses dan sedang menunggu konfirmasi Admin.'
            );
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Register gagal', [
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors('Gagal mendaftar, silakan coba lagi.');
        }
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('login');
    }
}
