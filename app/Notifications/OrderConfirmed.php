<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderConfirmed extends Notification
{
    public function __construct(private Order $order)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Confirmation de votre commande #' . $this->order->id_order)
            ->greeting('Bonjour ' . $notifiable->name . ' !')
            ->line('Votre commande #' . $this->order->id_order . ' a bien été enregistrée.')
            ->line('Montant total : ' . number_format($this->order->total, 2, ',', ' ') . ' €')
            ->action('Voir ma commande', url('/orders/' . $this->order->id_order))
            ->line('Merci pour votre achat !');
    }
}
