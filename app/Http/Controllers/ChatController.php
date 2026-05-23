<?php

namespace App\Http\Controllers;

use App\Events\Kirimpesan;
use App\Models\Pembayaran;
use App\Models\Pengambilan;
use App\Models\Pesan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $role = $user->getRoleNames()->first();
        $menus = config('sidebar')[$role] ?? [];

        $badges = [];

        if ($role === 'admin') {
            $badges = [
                'penilaian' => Pengambilan::where('status', 'menunggu')->count(),

                'pembayaran' => Pembayaran::where('status', 'belum_dibayar')->count(),

                'freelancer' => User::role('freelancer')->where('status_verifikasi', 'permintaan')->count()
            ];
        }
        if ($role === 'freelancer') {
            $badges = [
                'upload' => Pengambilan::where('status', 'diambil')->where('user_id', $user->id)->count()
            ];
        }

        if ($user->hasRole('admin')) {
            $users = User::role('freelancer')->get();
        } else {
            $users = User::role('admin')->get();
        }

        $selectedUser = null;
        $messages     = collect();

        if ($request->user) {
            $selectedUser = User::findOrFail($request->user);

            if ($user->hasRole('freelancer') && !$selectedUser->hasRole('admin')) {
                abort(403);
            }

            // Tandai pesan dari selectedUser sebagai sudah dibaca
            Pesan::where('pengirim_id', $selectedUser->id)
                ->where('penerima_id', $user->id)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            $messages = Pesan::where(function ($q) use ($user, $selectedUser) {
                $q->where('pengirim_id', $user->id)
                    ->where('penerima_id', $selectedUser->id);
            })->orWhere(function ($q) use ($user, $selectedUser) {
                $q->where('pengirim_id', $selectedUser->id)
                    ->where('penerima_id', $user->id);
            })->oldest()->get();
        }

        // Hitung unread per user untuk badge di sidebar chat
        $unreadCounts = Pesan::where('penerima_id', $user->id)
            ->whereNull('read_at')
            ->selectRaw('pengirim_id, count(*) as total')
            ->groupBy('pengirim_id')
            ->pluck('total', 'pengirim_id');

        return view('chat', compact(
            'users',
            'selectedUser',
            'messages',
            'unreadCounts',
            'menus',
            'badges'
        ));
    }

    public function send(Request $request)
    {
        $request->validate([
            'penerima_id' => 'required|exists:users,id',
            'teks'        => 'required|string|max:2000',
        ]);

        $user = Auth::user();

        $penerima = User::findOrFail($request->penerima_id);
        if ($user->hasRole('freelancer') && !$penerima->hasRole('admin')) {
            return response()->json(['error' => 'Tidak diizinkan'], 403);
        }

        $pesan = Pesan::create([
            'pengirim_id' => $user->id,
            'penerima_id' => $request->penerima_id,
            'teks'        => $request->teks,
        ]);

        $pesan->load('pengirim');

        broadcast(new Kirimpesan($pesan))->toOthers();

        return response()->json([
            'id'            => $pesan->id,
            'pengirim_id'   => $pesan->pengirim_id,
            'penerima_id'   => $pesan->penerima_id,
            'teks'          => $pesan->teks,
            'created_at'    => $pesan->created_at->format('H:i'),
            'nama_pengirim' => $pesan->pengirim->name,
        ]);
    }

    public function unread()
    {
        $user = Auth::user();

        $unreadCounts = Pesan::where('penerima_id', $user->id)
            ->whereNull('read_at')
            ->selectRaw('pengirim_id, count(*) as total')
            ->groupBy('pengirim_id')
            ->pluck('total', 'pengirim_id');

        return response()->json([
            'per_user' => $unreadCounts,
            'total'    => $unreadCounts->sum(),
        ]);
    }

    public function markRead($userId)
    {
        $user = Auth::user();

        Pesan::where('pengirim_id', $userId)
            ->where('penerima_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['ok' => true]);
    }
}
