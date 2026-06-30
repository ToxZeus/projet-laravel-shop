<?php

namespace App\Http\Controllers;

use App\Events\OrderPlaced;
use App\Events\OrderStatusChanged;
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
            $orders = Order::with('user')->orderByDesc('id_order')->paginate(15);
        } else {
            $orders = Order::where('user_id', $user->id)->orderByDesc('id_order')->paginate(15);
        }

        return view('orders.index', ['orders' => $orders]);
    }

    // Detail commande
    public function show(int $id)
    {
        $user = Auth::user();
        $order = Order::with('items.article')->findOrFail($id);

        if ($user->role !== 'admin' && $order->user_id !== $user->id) {
            return redirect()->route('orders.index')->with('info', 'Accès refusé.');
        }

        return view('orders.show', ['order' => $order]);
    }

    // Suppression commande (admin seulement, protégé par le middleware admin)
    public function destroy(int $id)
    {
        Order::findOrFail($id)->delete();

        return redirect()->route('orders.index')->with('status', 'Commande supprimée.');
    }

    // Mise à jour du statut (admin seulement, protégé par le middleware admin)
    public function updateStatus(int $id)
    {
        request()->validate([
            'status' => 'required|in:en attente,validée,expédiée,livrée,annulée',
        ]);

        $order = Order::findOrFail($id);
        $oldStatus = $order->status;
        $order->status = request()->input('status');
        $order->save();

        if ($oldStatus !== $order->status) {
            OrderStatusChanged::dispatch($order, $oldStatus);
        }

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

        OrderPlaced::dispatch($order);

        return redirect()->route('orders.show', $order->id_order)
            ->with('status', 'Commande validée.');
    }
}
