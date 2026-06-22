<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([], 401);
        }

        // Ambil notifikasi maksimal berusia 2 minggu (14 hari)
        $notifications = Notifikasi::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subWeeks(2))
            ->orderBy('created_at', 'desc')
            ->get();

        $unreadCount = $notifications->whereNull('read_at')->count();

        return response()->json([
            'notifications' => $notifications->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'message' => $item->message,
                    'type' => $item->type,
                    'read_at' => $item->read_at,
                    'time_ago' => $item->created_at->diffForHumans(),
                ];
            }),
            'unread_count' => $unreadCount
        ]);
    }

    public function markRead()
    {
        $user = Auth::user();
        if ($user) {
            Notifikasi::where('user_id', $user->id)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }
        return response()->json(['success' => true]);
    }
}