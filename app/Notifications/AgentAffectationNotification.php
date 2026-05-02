<?php

namespace App\Notifications;

use App\Models\Planification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AgentAffectationNotification extends Notification
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
            ->subject('Affectation d’une collecte')
            ->greeting('Bonjour '.optional($notifiable)->name)
            ->line('Une collecte a été affectée et attend votre supervision.')
            ->line('Code de planification : '.$this->planification->code_planification)
            ->line('Collecteur : '.optional($this->planification->collecteur->user)->name)
            ->action('Voir la planification', route('planifications.show', $this->planification))
            ->line('Veuillez vérifier l’état et la préparation de la mission.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'planification_id' => $this->planification->id,
            'collecteur' => optional($this->planification->collecteur->user)->name,
            'statut' => $this->planification->statut,
        ];
    }
}
