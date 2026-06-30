<?php

namespace App\Providers;

use App\Events\OrderPlaced;
use App\Events\OrderStatusChanged;
use App\Listeners\SendOrderConfirmationEmail;
use App\Listeners\SendOrderStatusEmail;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(OrderPlaced::class, SendOrderConfirmationEmail::class);
        Event::listen(OrderStatusChanged::class, SendOrderStatusEmail::class);
    }
}
