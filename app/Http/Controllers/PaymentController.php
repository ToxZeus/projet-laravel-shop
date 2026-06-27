<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Cashier;

class PaymentController extends Controller
{
    public function checkout()
    {
        $user = Auth::user();
        $items = Cart::with('article')->where('user_id', $user->id)->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('info', 'Votre panier est vide.');
        }

        foreach ($items as $item) {
            if ($item->quantity > $item->article->quantity) {
                return redirect()->route('cart.index')
                    ->with('info', 'Stock insuffisant pour « ' . $item->article->title . ' ».');
            }
        }

        $lineItems = $items->map(fn($item) => [
            'price_data' => [
                'currency' => 'eur',
                'product_data' => ['name' => $item->article->title],
                'unit_amount' => (int) round($item->article->price * 100),
            ],
            'quantity' => $item->quantity,
        ])->values()->all();

        $checkout = $user->checkout([], [
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('payment.cancel'),
        ]);

        return redirect($checkout->url);
    }

    public function success()
    {
        $sessionId = request()->query('session_id');

        if (!$sessionId) {
            return redirect()->route('cart.index')->with('info', 'Session invalide.');
        }

        $session = Cashier::stripe()->checkout->sessions->retrieve($sessionId);

        if ($session->payment_status !== 'paid') {
            return redirect()->route('cart.index')->with('info', 'Paiement non confirmé.');
        }

        $user = Auth::user();
        $items = Cart::with('article')->where('user_id', $user->id)->get();

        if ($items->isEmpty()) {
            return redirect()->route('orders.index');
        }

        $total = $items->sum(fn($item) => $item->article->price * $item->quantity);

        $order = Order::create([
            'user_id' => $user->id,
            'total' => $total,
            'status' => 'validée',
        ]);

        foreach ($items as $item) {
            OrderItem::create([
                'order_id' => $order->id_order,
                'article_id' => $item->article_id,
                'quantity' => $item->quantity,
                'price' => $item->article->price,
            ]);

            $item->article->quantity -= $item->quantity;
            $item->article->save();
        }

        Cart::where('user_id', $user->id)->delete();

        return redirect()->route('orders.show', $order->id_order)
            ->with('status', 'Paiement confirmé, commande validée.');
    }

    public function cancel()
    {
        return redirect()->route('cart.index')->with('info', 'Paiement annulé.');
    }
}
