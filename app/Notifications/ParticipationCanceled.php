<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Participation;

class ParticipationCanceled extends Notification
{
    use Queueable;

    /**
     * The order instance.
     *
     * @var Participation
     */
    public $participation;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Participation $participation)
    {
        $this->participation = $participation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Votre participation a été annulée')
            ->greeting('Bonjour ' . $notifiable->first_name . ',')
            ->line('Nous sommes au regret de vous informer que votre participation à la mission « ' . $this->participation->mission->name . ' » est annulée.')
            ->line('Nous vous invitons à poursuivre votre engagement au titre d\'une autre mission proposée sur le site JeVeuxAider.gouv.fr')
            ->line('Le responsable de l\'organisation « ' . $this->participation->mission->structure->name . ' »')
            ->action('Toutes nos missions', url(config('app.url') . '/missions'))
        ;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
