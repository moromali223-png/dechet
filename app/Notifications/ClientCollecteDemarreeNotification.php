<?php

namespace App\Notifications;

use App\Models\Planification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClientCollecteDemarreeNotification extends Notification
{
    use Queueable;

    public function __construct(public Planification $planification) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Votre collecte commence maintenant')
            ->greeting('Bonjour '.optional($notifiable)->name)
            ->line('Votre collecte de déchets est en cours de démarrage.')
            ->line('Code de planification : '.$this->planification->code_planification)
            ->line('Collecteur : '.optional($this->planification->collecteur->user)->name)
            ->line('Date prévue : '.$this->planification->date_prevue?->format('d/m/Y'))
            ->action('Suivre ma collecte', route('planifications.show', $this->planification))
            ->line('Merci de préparer le lieu pour l’arrivée de l’équipe.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'planification_id' => $this->planification->id,
            'statut' => $this->planification->statut,
        ];
    }
}
