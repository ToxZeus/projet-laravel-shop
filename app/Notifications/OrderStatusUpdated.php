<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusUpdated extends Notification
{
    public function __construct(
        private Order $order,
        private string $oldStatus,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Mise à jour de votre commande #' . $this->order->id_order)
            ->greeting('Bonjour ' . $notifiable->name . ' !')
            ->line('Le statut de votre commande #' . $this->order->id_order . ' a changé.')
            ->line('Ancien statut : ' . ucfirst($this->oldStatus))
            ->line('Nouveau statut : ' . ucfirst($this->order->status))
            ->action('Voir ma commande', url('/orders/' . $this->order->id_order));
    }
}
