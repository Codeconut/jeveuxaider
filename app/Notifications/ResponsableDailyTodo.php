<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResponsableDailyTodo extends Notification
{
    use Queueable;

    public $participations;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($participations)
    {
        $this->participations = $participations;
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
        $mailMessage = (new MailMessage)
            ->subject('Des bénévoles attendent votre réponse !')
            ->greeting('Bonjour ' . $notifiable->first_name . ',')
            ->line('Des bénévoles souhaitent vous aider !')
            ->line('Votre action est requise pour valider leur participation :');
        if (count($this->participations) == 1) {
            $mailMessage->action(count($this->participations) . ' participation en attente', url(config('app.url') . '/dashboard/participations'));
        } else {
            $mailMessage->action(count($this->participations) . ' participations en attente', url(config('app.url') . '/dashboard/participations'));
        }
        $mailMessage->line('Afin d’assurer vos recrutements de bénévoles, veuillez leur répondre au plus vite.')
            ->line('Vous pouvez aussi les contacter directement ou échanger avec eux sur la messagerie de la Réserve Civique.')
            ->line('Merci beaucoup par avance pour votre action.');

        return $mailMessage;
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