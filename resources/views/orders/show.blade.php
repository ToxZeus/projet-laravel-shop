@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-4">
    <h1 class="text-2xl font-bold mb-2">Commande #{{ $order->id_order }}</h1>

    @include('partials._flash')

    <p class="mb-1">Date : {{ $order->created_at->format('d/m/Y H:i') }}</p>
    @if(auth()->user()->role === 'admin')
        <p class="mb-1">Client : {{ $order->user->name }}</p>
        <form method="POST" action="{{ route('orders.updateStatus', $order->id_order) }}" class="flex items-center gap-2 my-3">
            @csrf
            @method('PATCH')
            <label class="text-sm font-medium">Statut :</label>
            <select name="status" class="border rounded px-2 py-1 text-sm">
                @foreach(['en attente', 'validée', 'expédiée', 'livrée', 'annulée'] as $s)
                    <option value="{{ $s }}" @selected($order->status === $s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-blue-600 text-white text-sm px-3 py-1 rounded hover:bg-blue-700">
                Mettre à jour
            </button>
        </form>
    @else
        <p class="mb-1">Statut : {{ $order->status }}</p>
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
