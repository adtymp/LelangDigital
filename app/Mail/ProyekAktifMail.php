<?php

namespace App\Mail;

use App\Models\Proyek;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProyekAktifMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user, $proyek;

    public function __construct(User $user, Proyek $proyek)
    {
        $this->user = $user;
        $this->proyek = $proyek;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Proyek Aktif Tersedia',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.proyek_aktif',
            with: [
                'user' => $this->user,
                'proyek' => $this->proyek
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
