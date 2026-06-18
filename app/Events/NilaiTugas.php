<?php

namespace App\Events;

use App\Models\Penilaian;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NilaiTugas implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $penilaian;
    public $user;
    public $namaSubProyek;
    public function __construct(Penilaian $penilaian, User $user, string $namaSubProyek)
    {
        $this->penilaian = $penilaian;
        $this->user = $user;
        $this->namaSubProyek = $namaSubProyek;
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
        return 'nilai.tugas';
    }
    public function broadcastWith(): array
    {
        return [
            'penilaian_id' => $this->penilaian->id,
            'nama_sub_proyek' => $this->namaSubProyek,
            'total_skor' => $this->penilaian->total_skor,
            'total_poin' => $this->penilaian->total_poin,
        ];
    }
}
