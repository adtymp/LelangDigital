<?php

namespace App\Http\Controllers;

use App\Http\Requests\PoinRequest;
use App\Models\Pembayaran;
use App\Models\Pengambilan;
use App\Models\Poin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PoinController extends Controller
{
    public function halamanPoin()
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

        $poins = Poin::all();
        $totalBobot = Poin::where('status', 'aktif')->sum('bobot');

        return view('admin.pengaturan.poin', compact(
            'poins',
            'totalBobot',
            'menus',
            'badges'
        ));
    }

    public function halamanSimulasi()
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

        $poins = Poin::all();

        return view('admin.pengaturan.simulasi', compact(
            'poins',
            'menus',
            'badges'
        ));
    }

    public function tambahAspek(PoinRequest $request)
    {
        $request->validated();

        $bobotBaru = round($request->bobot * 100); // contoh 0.25 => 25

        $poins = Poin::where('status', 'aktif')->get();

        $totalLama = $poins->sum(function ($p) {
            return round($p->bobot * 100);
        });

        // validasi tidak boleh lebih dari 100
        if (($totalLama + $bobotBaru) > 100) {

            if ($poins->count() == 0) {
                return response()->json([
                    'message' => 'Tidak bisa menambahkan bobot'
                ], 422);
            }

            // jumlah yang harus dikurangi
            $selisih = ($totalLama + $bobotBaru) - 100;

            $totalBobotLama = $poins->sum('bobot');

            $updatedBobots = [];
            $sisa = $selisih;

            foreach ($poins as $index => $p) {

                $bobotInt = round($p->bobot * 100);

                // pengurangan proporsional
                $pengurang = floor(($p->bobot / $totalBobotLama) * $selisih);

                $newBobot = $bobotInt - $pengurang;

                if ($newBobot < 0) {
                    return response()->json([
                        'message' => 'Bobot terlalu besar, menyebabkan nilai negatif'
                    ], 422);
                }

                $updatedBobots[$p->id] = $newBobot;
                $sisa -= $pengurang;
            }

            // 🔥 handle sisa pembulatan
            if ($sisa > 0) {
                foreach ($updatedBobots as $id => $val) {
                    if ($sisa <= 0) break;

                    if ($val > 0) {
                        $updatedBobots[$id] -= 1;
                        $sisa--;
                    }
                }
            }

            // update database
            foreach ($updatedBobots as $id => $val) {
                Poin::where('id', $id)->update([
                    'bobot' => $val / 100
                ]);
            }
        }

        // simpan bobot baru
        $poin = Poin::create([
            'aspek' => $request->aspek,
            'slug' => Str::slug($request->aspek),
            'bobot' => $bobotBaru / 100,
            'status' => 'aktif',
        ]);

        return response()->json([
            'success' => true,
            'data' => $poin,
            'data_all' => Poin::where('status', 'aktif')->get()
        ]);
    }

    public function updateAspek(PoinRequest $request, Poin $poin)
    {
        $request->validated();

        if (
            $poin->aspek == $request->aspek &&
            (float)$poin->bobot == (float)$request->bobot &&
            $poin->status == $request->status
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada perubahan data'
            ], 422);
        }

        //total bobot setelah update
        $totalAktif = Poin::where('status', 'aktif')
            ->where('id', '!=', $poin->id)
            ->sum('bobot');

        if ($request->status === 'aktif') {
            $totalAktif += $request->bobot;
        }

        if ($totalAktif > 1) {
            return response()->json([
                'success' => false,
                'message' => 'Total bobot aktif tidak boleh melebihi 1.00'
            ], 422);
        }

        $poin->update([
            'aspek' => $request->aspek,
            'slug' => Str::slug($request->aspek),
            'bobot' => $request->bobot,
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'data' => $poin
        ]);
    }

    public function hapusAspek(Poin $poin)
    {
        $poin->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
