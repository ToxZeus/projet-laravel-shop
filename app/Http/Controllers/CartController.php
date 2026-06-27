<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $items = Cart::with('article')->where('user_id', $user->id)->get();

        return view('cart.index', compact('items'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'article_id' => 'required|integer|exists:articles,id_article',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();

        $article = Article::findOrFail($request->article_id);

        $cart = Cart::where('user_id', $user->id)
            ->where('article_id', $request->article_id)
            ->first();

        $current = $cart ? $cart->quantity : 0;

        if ($current + $request->quantity > $article->quantity) {
            return redirect()->route('cart.index')
                ->with('info', 'Stock insuffisant : il reste ' . $article->quantity . ' en stock pour « ' . $article->title . ' ».');
        }

        if ($cart) {
            $cart->quantity += $request->quantity;
            $cart->save();
        } else {
            Cart::create([
                'user_id' => $user->id,
                'article_id' => $request->article_id,
                'quantity' => $request->quantity,
            ]);
        }

        return redirect()->route('cart.index')->with('status', 'Article ajouté au panier.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        $item = Cart::with('article')->where('id_cart', $id)->where('user_id', $user->id)->firstOrFail();

        if ($request->quantity > $item->article->quantity) {
            return back()->with('info', 'Stock insuffisant : il reste ' . $item->article->quantity . ' en stock.');
        }

        $item->quantity = $request->quantity;
        $item->save();

        return back()->with('status', 'Quantité mise à jour.');
    }

    public function remove(Request $request, $id)
    {
        $user = Auth::user();

        $item = Cart::where('id_cart', $id)->where('user_id', $user->id)->first();
        if ($item) {
            $item->delete();
        }

        return redirect()->route('cart.index')->with('status', 'Article retiré du panier.');
    }
}
