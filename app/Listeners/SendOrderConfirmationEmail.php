<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Notifications\OrderConfirmed;

class SendOrderConfirmationEmail
{
    public function handle(OrderPlaced $event): void
    {
        $event->order->user->notify(new OrderConfirmed($event->order));
    }
}
