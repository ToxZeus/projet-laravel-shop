@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">{{ auth()->user()->role === 'admin' ? 'Toutes les commandes' : 'Mes commandes' }}</h1>

    @include('partials._flash')

    @if($orders->isEmpty())
        <p>Aucune commande pour le moment.</p>
    @else
        <table class="w-full table-auto border-collapse">
            <thead>
                <tr class="text-left">
                    <th>N°</th>
                    @if(auth()->user()->role === 'admin')
                        <th>Client</th>
                    @endif
                    <th>Date</th>
                    <th>Total</th>
                    <th>Statut</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr class="border-t">
                    <td>#{{ $order->id_order }}</td>
                    @if(auth()->user()->role === 'admin')
                        <td>{{ $order->user->name }}</td>
                    @endif
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ number_format($order->total, 2, ',', ' ') }} €</td>
                    <td>{{ $order->status }}</td>
                    <td class="flex items-center gap-3 py-1">
                        <a href="{{ route('orders.show', $order->id_order) }}" class="text-blue-600">Voir</a>
                        @if(auth()->user()->role === 'admin')
                        <form method="POST" action="{{ route('orders.destroy', $order->id_order) }}"
                              onsubmit="return confirm('Supprimer la commande #{{ $order->id_order }} ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600">Supprimer</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="mt-4">
        {{ $orders->links() }}
    </div>

    <div class="mt-4">
        <a href="/dashboard" class="text-blue-600">Retour aux articles</a>
    </div>
</div>
@endsection
