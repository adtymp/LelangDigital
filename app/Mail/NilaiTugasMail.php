<?php

namespace App\Mail;

use App\Models\Penilaian;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NilaiTugasMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $penilaian;
    public $subsubproyek;
    public function __construct(User $user, Penilaian $penilaian)
    {
        $this->user = $user;
        $this->penilaian = $penilaian;
        $this->subsubproyek = $penilaian->pengambilan->subsubproyeks;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Penilaian Tugas Selesai',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.nilai_tugas',
            with: [
                'user' => $this->user,
                'penilaian' => $this->penilaian,
                'subsubproyek' => $this->subsubproyek,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
