<?php

namespace App\Events;

use App\Models\Proyek;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProyekAktifRealtime implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $proyek;
    public $user;

    public function __construct(Proyek $proyek, User $user)
    {
        $this->proyek = $proyek;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [new PrivateChannel('user.' . $this->user->id),];
    }

    public function broadcastAs(): string
    {
        return 'proyek.aktif';
    }
    public function broadcastWith(): array
    {
        return [
            'id' => $this->proyek->id,
            'judul' => $this->proyek->nama_proyek,
        ];
    }
}
