<?php

namespace App\Notifications;

use App\Models\Abonnement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AbonnementRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Abonnement $abonnement;

    /**
     * Create a new notification instance.
     */
    public function __construct(Abonnement $abonnement)
    {
        $this->abonnement = $abonnement;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Votre abonnement a été rejeté')
            ->greeting('Bonjour '.$notifiable->name.',')
            ->line('Votre demande d\'abonnement pour '.$this->abonnement->type_abonnement.' a été rejetée.')
            ->line('Motif du rejet : '.$this->abonnement->motif_rejet)
            ->line('Vous pouvez contacter notre support pour plus d\'informations.')
            ->action('Voir mes abonnements', route('abonnements.index'))
            ->salutation('Cordialement, L\'équipe EcoFlux');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'abonnement_id' => $this->abonnement->id,
            'type' => 'abonnement_rejected',
            'motif' => $this->abonnement->motif_rejet,
        ];
    }
}
