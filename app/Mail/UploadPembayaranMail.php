<?php

namespace App\Mail;

use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UploadPembayaranMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $pembayaran;
    public $proyek;
    public function __construct(User $user, Pembayaran $pembayaran)
    {
        $this->user = $user;
        $this->pembayaran = $pembayaran;
        $this->proyek = $pembayaran->penilaian->pengambilan->subsubproyeks->subproyeks->proyeks;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pembayaran Pekerjaan Proyek Selesai',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.upload_pembayaran',
            with: [
                'user' => $this->user,
                'pembayaran' => $this->pembayaran,
                'proyek' => $this->proyek,
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
    Log::info('ATTACHMENT PATH: ' . $this->pembayaran->bukti_transfer);
    Log::info(
        'Exists: ' .
        (Storage::disk('public')->exists($this->pembayaran->bukti_transfer)
            ? 'YES'
            : 'NO')
    );
    
    if (
        !$this->pembayaran->bukti_transfer ||
        !Storage::disk('public')->exists($this->pembayaran->bukti_transfer)
    ) {
        return [];
    }

    $extension = pathinfo(
        $this->pembayaran->bukti_transfer,
        PATHINFO_EXTENSION
    );

    return [
        Attachment::fromStorageDisk(
            'public',
            $this->pembayaran->bukti_transfer
        )->as('Bukti-Transfer.' . $extension),
    ];
}
}
