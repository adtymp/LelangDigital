<?php

namespace App\Events;

use App\Models\Pembayaran;
use App\Models\Pengambilan;
use App\Models\Proyek;
use App\Models\Pesan;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class UpdateBadge implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $role;
    public function __construct(int $userId, string $role)
    {
        $this->userId = $userId;
        $this->role   = $role;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [new PrivateChannel('user.' . $this->userId)];
    }
    public function broadcastAs(): string
    {
        return 'badge.updated';
    }
    public function broadcastWith(): array
    {
        $badges = [];

        if ($this->role === 'admin') {
            $badges = [
                'penilaian'  => Pengambilan::where('status', 'menunggu')->count(),
                'pembayaran' => Pembayaran::where('status', 'belum_dibayar')->count(),
                'freelancer' => User::role('freelancer')
                    ->where('status_verifikasi', 'permintaan')->count(),
                'chat'       => Pesan::where('penerima_id', $this->userId)
                    ->whereNull('read_at')->count(),
            ];
        }
        if ($this->role === 'freelancer') {
            $user = User::find($this->userId);
            $delayProyek = $user ? ($user->level->delay_notifikasi ?? 0) : 0;
            $badges = [
                'upload'      => Pengambilan::where('status', 'diambil')
                    ->where('user_id', $this->userId)->count(),
                'proyekAktif' => Proyek::where('status', 'aktif')
                    ->where('tanggal_mulai', '<=', Carbon::now()->subMinutes($delayProyek))
                    ->count(),
                'chat'        => Pesan::where('penerima_id', $this->userId)
                    ->whereNull('read_at')->count(),
            ];
        }
        return ['badges' => $badges];
    }
}
