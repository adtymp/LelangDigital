<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pengambilan;
use App\Models\Proyek;
use App\Models\Pesan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BadgeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->getRoleNames()->first();

        $badges = [];

        if ($role === 'admin') {
            $badges = [
                'penilaian'  => Pengambilan::where('status', 'menunggu')->count(),
                'pembayaran' => Pembayaran::where('status', 'belum_dibayar')->count(),
                'freelancer' => User::role('freelancer')
                    ->where('status_verifikasi', 'permintaan')->count(),
                'chat'       => Pesan::where('penerima_id', $user->id)
                    ->whereNull('read_at')->count(),
            ];
        }

        if ($role === 'freelancer') {
            $delayProyek = $user->level->delay_notifikasi ?? 0;

            $badges = [
                'upload'      => Pengambilan::where('status', 'diambil')
                    ->where('user_id', $user->id)->count(),
                'proyekAktif' => Proyek::where('status', 'aktif')
                    ->where('tanggal_mulai', '<=', Carbon::now()->subMinutes($delayProyek))
                    ->count(),
                'chat'        => Pesan::where('penerima_id', $user->id)
                    ->whereNull('read_at')->count(),
            ];
        }

        return response()->json($badges);
    }
}