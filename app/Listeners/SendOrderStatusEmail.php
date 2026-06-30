<?php

namespace App\Listeners;

use App\Events\OrderStatusChanged;
use App\Notifications\OrderStatusUpdated;

class SendOrderStatusEmail
{
    public function handle(OrderStatusChanged $event): void
    {
        $event->order->user->notify(new OrderStatusUpdated($event->order, $event->oldStatus));
    }
}
