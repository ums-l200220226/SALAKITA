<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PetaniApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $petani;

    public function __construct($petani)
    {
        $this->petani = $petani;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pendaftaran Anda Disetujui - SALAKITA',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.petaniApproved',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
