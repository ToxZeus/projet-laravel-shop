<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Models\Article;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $items = Cart::with('article')->where('user_id', $user->id)->get();

        return view('cart.index', compact('items'));
    }

    public function add(AddToCartRequest $request)
    {
        $validated = $request->validated();
        $user = Auth::user();

        $article = Article::findOrFail($validated['article_id']);

        $cart = Cart::where('user_id', $user->id)
            ->where('article_id', $validated['article_id'])
            ->first();

        $current = $cart ? $cart->quantity : 0;

        if ($current + $validated['quantity'] > $article->quantity) {
            return redirect()->route('cart.index')
                ->with('info', 'Stock insuffisant : il reste ' . $article->quantity . ' en stock pour « ' . $article->title . ' ».');
        }

        if ($cart) {
            $cart->quantity += $validated['quantity'];
            $cart->save();
        } else {
            Cart::create([
                'user_id' => $user->id,
                'article_id' => $validated['article_id'],
                'quantity' => $validated['quantity'],
            ]);
        }

        return redirect()->route('cart.index')->with('status', 'Article ajouté au panier.');
    }

    public function update(UpdateCartRequest $request, $id)
    {
        $validated = $request->validated();
        $user = Auth::user();
        $item = Cart::with('article')->where('id_cart', $id)->where('user_id', $user->id)->firstOrFail();

        if ($validated['quantity'] > $item->article->quantity) {
            return back()->with('info', 'Stock insuffisant : il reste ' . $item->article->quantity . ' en stock.');
        }

        $item->quantity = $validated['quantity'];
        $item->save();

        return back()->with('status', 'Quantité mise à jour.');
    }

    public function remove($id)
    {
        $user = Auth::user();

        $item = Cart::where('id_cart', $id)->where('user_id', $user->id)->first();
        if ($item) {
            $item->delete();
        }

        return redirect()->route('cart.index')->with('status', 'Article retiré du panier.');
    }
}
