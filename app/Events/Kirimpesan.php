<?php

namespace App\Events;

use App\Models\Pesan;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Kirimpesan implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $pesan;

    public function __construct(Pesan $pesan)
    {
        $this->pesan = $pesan;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $ids = [$this->pesan->pengirim_id, $this->pesan->penerima_id];
        sort($ids);

        return [
            new PrivateChannel('chat.' . $ids[0] . '.' . $ids[1]),
            new PrivateChannel('user.' . $this->pesan->penerima_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'id'          => $this->pesan->id,
            'pengirim_id'   => $this->pesan->pengirim_id,
            'penerima_id' => $this->pesan->penerima_id,
            'teks'        => $this->pesan->teks,
            'created_at'  => $this->pesan->created_at->format('H:i'),
            'nama_pengirim' => $this->pesan->pengirim->name,
        ];
    }

    public function broadcastAs(): string
    {
        return 'pesan.kirim';
    }
}
