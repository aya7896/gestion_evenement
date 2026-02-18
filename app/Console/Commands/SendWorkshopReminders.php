<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Atelier;
use App\Models\Inscription_atelier;
use Illuminate\Support\Facades\Mail;
use App\Mail\WorkshopReminderMail;

class SendWorkshopReminders extends Command
{
    protected $signature = 'reminders:send';
    protected $description = "Envoyer des e-mails de rappel 1 heure avant les ateliers";

    public function handle()
    {
        $now = Carbon::now();
        $target = $now->copy()->addHour();
        $windowStart = $target->copy()->subMinutes(2);
        $windowEnd = $target->copy()->addMinutes(2);

        $this->info("Recherche ateliers démarrant entre {$windowStart} et {$windowEnd}");

        $ateliers = Atelier::get();
        foreach ($ateliers as $atelier) {
            try {
                $date = optional($atelier->date);
                $heure = optional($atelier->heure_debut);
                if (!$date || !$heure) continue;

                // Compose datetime for the atelier
                $atelierDateTime = Carbon::parse($date->format('Y-m-d') . ' ' . $heure->format('H:i'));

                if ($atelierDateTime->between($windowStart, $windowEnd)) {
                    $this->info("Atelier trouvé : {$atelier->titre} ({$atelierDateTime})");

                    $inscriptions = Inscription_atelier::with('inscription.user')
                        ->where('id_atelier', $atelier->id_atelier)
                        ->get();

                    foreach ($inscriptions as $ins) {
                        $inscription = $ins->inscription;
                        if (!$inscription || !$inscription->user || !$inscription->user->email) continue;

                        Mail::to($inscription->user->email)->queue(new WorkshopReminderMail($atelier, $inscription));
                        $this->info("Email queued to: {$inscription->user->email}");
                    }
                }
            } catch (\Throwable $e) {
                $this->error('Erreur lors du traitement atelier: ' . $e->getMessage());
            }
        }

        return 0;
    }
}
