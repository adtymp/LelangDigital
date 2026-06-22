<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\NotifikasiPendaftaranMail;
use App\Models\Level;
use App\Models\Portofolio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {

            $googleUser = Socialite::driver('google')->user();

            /**
             * Cari user berdasarkan email atau google_id
             */
            $user = User::where('email', $googleUser->email)
                ->orWhere('google_id', $googleUser->id)
                ->first();

            /**
             * Jika user belum ada
             */
            if (!$user) {

                $level = Level::orderBy('nama_level')->first();

                $user = User::create([
                    'google_id' => $googleUser->id,
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'avatar' => $googleUser->avatar,
                    'is_google_user' => true,
                    'password' => null,
                    'no_telp' => null,
                    'level_id' => $level?->id,
                    'status_akun' => 'nonaktif',
                    'status_verifikasi' => 'permintaan',
                ]);

                $user->assignRole('freelancer');
            }

            /**
             * Jika email sudah pernah register manual
             */
            if (!$user->google_id) {

                $user->update([
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'is_google_user' => true,
                ]);
            }

            /**
             * Login user
             */
            Auth::login($user);

            /**
             * Jika belum punya portofolio
             */
            if (!$user->portofolio || $user->status_verifikasi === 'ditolak') {
                return redirect()->route('google.lengkapi');
            }

            /**
             * Redirect normal
             */
            return $this->redirectByRole($user);

        } catch (\Throwable $e) {

            Log::error('Google Login Error', [
                'message' => $e->getMessage(),
            ]);

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Login Google gagal.'
                ]);
        }
    }

    public function formLengkapi()
    {
        $user = Auth::user();

        /**
         * Jika sudah punya portofolio
         */
        if ($user->portofolio && $user->status_verifikasi !== 'ditolak') {
            return $this->redirectByRole($user);
        }

        $terimaDomain = config('portofolio.terima_domain');

        return view('auth.form-portofolio', compact(
            'user',
            'terimaDomain'
        ));
    }

    public function simpanLengkapi(Request $request)
    {
        $request->validate([
            'no_telp' => 'required|string|max:20',

            'type' => 'required|in:file,link',

            'file_path' =>
                'required_if:type,file|nullable|mimes:jpg,jpeg,png,pdf|max:5120',

            'link_url' =>
                'required_if:type,link|nullable|url',
        ]);

        DB::beginTransaction();

        try {

            $user = Auth::user();

            /**
             * Update user
             */
            $user->update([
                'no_telp' => $request->no_telp,
                'status_akun' => 'nonaktif',
                'status_verifikasi' => 'permintaan'
            ]);

            /**
             * Hapus portofolio lama
             */
            Portofolio::where('user_id', $user->id)->delete();

            /**
             * Data portofolio
             */
            $portofolio = [
                'user_id' => $user->id,
                'type' => $request->type,
            ];

            /**
             * Upload file
             */
            if ($request->type === 'file') {

                $portofolio['file_path'] =
                    $request->file('file_path')
                        ->store('portfolios', 'public');

                $portofolio['link_url'] = null;

            } else {

                $portofolio['link_url'] = $request->link_url;

                $portofolio['file_path'] = null;
            }

            /**
             * Simpan portofolio
             */
            Portofolio::create($portofolio);

            /**
             * Kirim email admin
             */
            Mail::to(env('ADMIN_EMAIL'))
                ->queue(new NotifikasiPendaftaranMail($user));

            DB::commit();

            /**
             * Logout user
             */
            Auth::logout();

            return redirect('login')->with('success', 'Pendaftaran sukses, sedang diajukan untuk dikonfirmasi oleh admin');

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('Lengkapi Google Error', [
                'message' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'error' => 'Gagal menyimpan data.'
            ]);
        }
    }

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
                        'Pendaftaran Anda ditolak admin.',

                    $user->status_verifikasi === 'diterima'
                    && $user->status_akun === 'nonaktif' =>
                        'Akun Anda sedang dinonaktifkan.',

                    default =>
                        'Akun tidak dapat mengakses sistem.',
                };

                return redirect()
                    ->route('login')
                    ->withErrors([
                        'email' => $pesan
                    ]);
            }

            /**
             * Jika belum isi rekening
             */
            if (!$user->rekening) {
                return redirect()
                    ->route('rekening.form');
            }

            return redirect()
                ->route('dashboard.freelance')->with('success', 'Anda berhasil login');
        }

        Auth::logout();

        return redirect()
            ->route('login')
            ->withErrors([
                'email' => 'Role tidak dikenali.'
            ]);
    }
}
