<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\Pembayaran;
use App\Models\Pengambilan;
use App\Models\ResetLevel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LevelController extends Controller
{
    public function halamanLevel()
    {
        $role = Auth::user()->getRoleNames()->first();

        $resetLevel = ResetLevel::first();

        $menus = config('sidebar')[$role] ?? [];

        $badges = [];

        if ($role === 'admin') {
            $badges = [
                'penilaian' => Pengambilan::where('status', 'menunggu')->count(),

                'pembayaran' => Pembayaran::where('status', 'belum_dibayar')->count(),

                'freelancer' => User::role('freelancer')->where('status_verifikasi', 'permintaan')->count()
            ];
        }

        $levels = Level::all();

        return view('admin.pengaturan.level',  compact(
            'levels',
            'resetLevel',
            'menus',
            'badges'
        ));
    }

    public function sistemLevel()
    {

        $user = Auth::user();



        $role = $user->getRoleNames()->first();
        $menus = config('sidebar')[$role] ?? [];

        $badges = [];

        if ($role === 'freelancer') {
            $badges = [
                'upload' => Pengambilan::where('status', 'diambil')->where('user_id', $user->id)->count(),
            ];
        }

        $levels = Level::all();

        return view('freelancer.sistem_level', compact(
            'user',
            'levels',
            'badges',
            'menus'
        ));
    }

    public function tambahLevel(Request $request)
    {
        $request->validate([
            'nama_level' => 'required|numeric',
            'min_poin' => 'required|numeric',
            'delay_notifikasi' => 'required|numeric',
        ]);

        $levels = Level::orderBy('nama_level')->get();

        if ($levels->contains('nama_level', $request->nama_level)) {
            return redirect()->back()
                ->withErrors([
                    'error' => 'Level sudah ada'
                ]);
        }

        $maxLevel = $levels->max('nama_level') ?? 0;

        if ($request->nama_level != $maxLevel + 1) {
            return redirect()->back()
                ->withErrors([
                    'error' => 'Level harus berurutan. Level berikutnya adalah ' . ($maxLevel + 1)
                ]);
        }

        $lastLevel = $levels->where('nama_level', $maxLevel)->first();

        if ($lastLevel && $request->min_poin <= $lastLevel->min_poin) {
            return redirect()->back()
                ->withErrors([
                    'error' => 'Minimum poin harus lebih besar dari level sebelumnya'
                ]);
        }

        $level = Level::create([
            'nama_level' => $request->nama_level,
            'slug' => Str::slug('level ' . $request->nama_level),
            'min_poin' => $request->min_poin,
            'delay_notifikasi' => $request->delay_notifikasi,
        ]);

        return redirect()->back()->with('success', 'Level berhasil ditambahkan');
    }

    public function updateLevel(Request $request, Level $level)
    {
        $request->validate([
            'min_poin' => 'required|numeric',
            'delay_notifikasi' => 'required|numeric',
        ]);

        $prevLevel = Level::where('nama_level', '<', $level->nama_level)
            ->orderByDesc('nama_level')
            ->first();

        $nextLevel = Level::where('nama_level', '>', $level->nama_level)
            ->orderBy('nama_level')
            ->first();

        if ($prevLevel && $request->min_poin <= $prevLevel->min_poin) {
            return response()->json([
                'message' => 'Minimum poin harus lebih besar dari level sebelumnya'
            ], 422);
        }

        if ($nextLevel && $request->min_poin >= $nextLevel->min_poin) {
            return response()->json([
                'message' => 'Minimum poin harus lebih kecil dari level setelahnya'
            ], 422);
        }

        $level->update([
            'min_poin' => $request->min_poin,
            'delay_notifikasi' => $request->delay_notifikasi,
        ]);

        return response()->json([
            'success' => true,
            'data' => $level
        ]);
    }

    public function hapusLevel(Level $level)
    {
        $nextLevel = Level::where('nama_level', '>', $level->nama_level)->exists();

        if ($nextLevel) {
            return response()->json([
                'message' => 'Hapus level terakhir terlebih dahulu'
            ], 422);
        }

        $previousLevel = Level::where('nama_level', '<', $level->nama_level)
        ->orderByDesc('nama_level')
        ->first();

        User::where('level_id', $level->id)
        ->update([
            'level_id' => $previousLevel?->id
        ]);

        $level->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
