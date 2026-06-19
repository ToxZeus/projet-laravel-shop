@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-4">
    <h1 class="text-2xl font-bold mb-2">Commande #{{ $order->id_order }}</h1>

    @include('partials._flash')

    <p class="mb-1">Date : {{ $order->created_at->format('d/m/Y H:i') }}</p>
    <p class="mb-1">Statut : {{ $order->status }}</p>
    @if(auth()->user()->role === 'admin')
        <p class="mb-1">Client : {{ $order->user->name }}</p>
    @endif

    <table class="w-full table-auto border-collapse mt-4">
        <thead>
            <tr class="text-left">
                <th>Article</th>
                <th>Quantité</th>
                <th>Prix unitaire</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr class="border-t">
                <td>{{ $item->article->title }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->price, 2, ',', ' ') }} €</td>
                <td>{{ number_format($item->price * $item->quantity, 2, ',', ' ') }} €</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="border-t font-bold">
                <td colspan="3" class="text-right pr-2">Total :</td>
                <td>{{ number_format($order->total, 2, ',', ' ') }} €</td>
            </tr>
        </tfoot>
    </table>

    <div class="mt-6">
        <a href="{{ route('orders.index') }}" class="text-blue-600">Retour aux commandes</a>
    </div>
</div>
@endsection
