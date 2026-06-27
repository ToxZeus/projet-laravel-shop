<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Liste des commandes 
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $orders = Order::with('user')->orderByDesc('id_order')->get();
        } else {
            $orders = Order::where('user_id', $user->id)->orderByDesc('id_order')->get();
        }

        return view('orders.index', ['orders' => $orders]);
    }

    // Detai commande
    public function show(int $id)
    {
        $user = Auth::user();
        $order = Order::with('items.article')->findOrFail($id);

        if ($user->role !== 'admin' && $order->user_id !== $user->id) {
            return redirect()->route('orders.index')->with('info', 'Accès refusé.');
        }

        return view('orders.show', ['order' => $order]);
    }

    // Mise à jour du statut (admin seulement)
    public function updateStatus(int $id)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return redirect()->route('orders.index')->with('info', 'Accès refusé.');
        }

        $allowed = ['en attente', 'validée', 'expédiée', 'livrée', 'annulée'];
        $status = request()->input('status');

        if (!in_array($status, $allowed)) {
            return back()->with('info', 'Statut invalide.');
        }

        $order = Order::findOrFail($id);
        $order->status = $status;
        $order->save();

        return back()->with('status', 'Statut mis à jour.');
    }

    // Valider le panier
    public function store()
    {
        $user = Auth::user();
        $items = Cart::with('article')->where('user_id', $user->id)->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('info', 'Votre panier est vide.');
        }

        // Vérif stock 
        foreach ($items as $item) {
            if ($item->quantity > $item->article->quantity) {
                return redirect()->route('cart.index')
                    ->with('info', 'Stock insuffisant pour « '.$item->article->title.' ».');
            }
        }

        // Toto
        $total = $items->sum(fn ($item) => $item->article->price * $item->quantity);

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

        // Vider panier
        Cart::where('user_id', $user->id)->delete();

        return redirect()->route('orders.show', $order->id_order)
            ->with('status', 'Commande validée.');
    }
}
