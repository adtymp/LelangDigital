<?php

namespace App\Events;

use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UploadPembayaran implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $pembayaran;
    public $user;
    public $namaProyek;
    public function __construct(Pembayaran $pembayaran, User $user, string $namaProyek)
    {
        $this->pembayaran = $pembayaran;
        $this->user = $user;
        $this->namaProyek = $namaProyek;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [new PrivateChannel('user.' . $this->user->id)];
    }
    public function broadcastAs(): string
    {
        return 'pembayaran.diunggah';
    }
    public function broadcastWith(): array
    {
        return [
            'pembayaran_id' => $this->pembayaran->id,
            'nama_proyek' => $this->namaProyek,
            'total_pembayaran' => $this->pembayaran->total_pembayaran,
        ];
    }
}
