<?php

namespace App\Notifications;

use App\Models\Planification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CollecteurAffectationNotification extends Notification
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
            ->subject('Nouvelle affectation de collecte')
            ->greeting('Bonjour '.optional($notifiable)->name)
            ->line('Vous avez été affecté(e) à une nouvelle tournée de collecte.')
            ->line('Code de planification : '.$this->planification->code_planification)
            ->line('Date prévue : '.$this->planification->date_prevue?->format('d/m/Y'))
            ->line('Zone : '.optional($this->planification->zone)->nom)
            ->action('Voir la tournée', route('planifications.show', $this->planification))
            ->line('Merci de préparer la collecte.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'planification_id' => $this->planification->id,
            'code_planification' => $this->planification->code_planification,
            'statut' => $this->planification->statut,
        ];
    }
}
