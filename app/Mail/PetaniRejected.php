<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PetaniRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $petani;
    public $reason;

    public function __construct($petani, $reason)
    {
        $this->petani = $petani;
        $this->reason = $reason;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pendaftaran Anda Ditolak - SALAKITA',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.petaniRejected',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
