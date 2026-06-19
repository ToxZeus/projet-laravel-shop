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
                    <td><a href="{{ route('orders.show', $order->id_order) }}" class="text-blue-600">Voir</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="mt-6">
        <a href="/dashboard" class="text-blue-600">Retour aux articles</a>
    </div>
</div>
@endsection
