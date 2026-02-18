<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WorkshopReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $atelier;
    public $inscription;

    /**
     * Create a new message instance.
     */
    public function __construct($atelier, $inscription)
    {
        $this->atelier = $atelier;
        $this->inscription = $inscription;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = sprintf('Rappel : atelier "%s" dans 1 heure', $this->atelier->titre ?? 'Atelier');

        return $this->subject($subject)
                    ->view('emails.workshop_reminder')
                    ->with([
                        'atelier' => $this->atelier,
                        'inscription' => $this->inscription,
                    ]);
    }
}
