<?php

namespace App\Mail;

use App\Models\Inscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InscriptionVerificationCode extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Inscription $inscription,
        public string $code,
        public int $expiresInMinutes
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Code de verification de votre inscription'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.inscription-code'
        );
    }
}
