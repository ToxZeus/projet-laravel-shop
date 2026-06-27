@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Mon panier</h1>

    @include('partials._flash')

    @if($items->isEmpty())
        <p>Votre panier est vide.</p>
    @else
        <table class="w-full table-auto border-collapse">
            <thead>
                <tr class="text-left">
                    <th>Article</th>
                    <th>Quantité</th>
                    <th>Prix</th>
                    <th>Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr class="border-t">
                    <td class="py-2">{{ $item->article->title }}</td>
                    <td class="py-2">
                        <form method="POST" action="{{ route('cart.update', $item->id_cart) }}" class="flex items-center gap-1">
                            @csrf
                            @method('PATCH')
                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1"
                                   max="{{ $item->article->quantity }}"
                                   class="border rounded w-16 px-1 py-0.5 text-sm text-center bg-white text-gray-900">
                            <button type="submit" class="text-blue-600 text-sm">OK</button>
                        </form>
                    </td>
                    <td class="py-2">{{ number_format($item->article->price, 2, ',', ' ') }} €</td>
                    <td class="py-2">{{ number_format($item->article->price * $item->quantity, 2, ',', ' ') }} €</td>
                    <td class="py-2">
                        <form method="POST" action="{{ route('cart.remove', $item->id_cart) }}">
                            @csrf
                            <button type="submit" class="text-red-600">Supprimer</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="border-t font-bold">
                    <td colspan="3" class="text-right pr-2">Total :</td>
                    <td>{{ number_format($items->sum(fn($item) => $item->article->price * $item->quantity), 2, ',', ' ') }} €</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <form method="POST" action="{{ route('payment.checkout') }}" class="mt-4">
            @csrf
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Payer avec Stripe</button>
        </form>
    @endif

    <div class="mt-6">
        <a href="/dashboard" class="text-blue-600">Retour aux articles</a>
    </div>
</div>
@endsection
