<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SpentCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private $spent)
    {

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
            ->greeting('Despesa cadastrada')
            ->subject('Nova despesa cadastrada')
            ->line('Olá, ' . $notifiable->name)
            ->line('Informamos que um novo gasto foi registrado em sua conta.')
            ->line($this->spent->description)
            ->line('valor: R$ ' . number_format($this->spent->value / 100, 2, ',', '.'))
            ->line('Dia do gasto ' . $this->spent->getFormattedSpentAt())
            ->line('Para mais detalhes, acesse sua área de despesas')
            ->salutation('Equipe Spentaculus');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
