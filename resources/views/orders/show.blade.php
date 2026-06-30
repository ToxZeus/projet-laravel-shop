<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
            <x-icon name="truck" class="w-5 h-5 text-indigo-500" /> Commande #{{ $order->id_order }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('partials._flash')

            @php
                $statusBadge = match($order->status) {
                    'en attente' => 'badge-yellow',
                    'validée' => 'badge-blue',
                    'expédiée' => 'badge-indigo',
                    'livrée' => 'badge-green',
                    'annulée' => 'badge-red',
                    default => 'badge-gray',
                };
            @endphp

            <div class="card card-body">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="space-y-1 text-sm text-gray-600 dark:text-gray-300">
                        <p>Date : <span class="font-medium text-gray-900 dark:text-gray-100">{{ $order->created_at->format('d/m/Y H:i') }}</span></p>
                        @if(auth()->user()->role === 'admin')
                            <p>Client : <span class="font-medium text-gray-900 dark:text-gray-100">{{ $order->user->name }}</span></p>
                        @endif
                    </div>

                    @if(auth()->user()->role === 'admin')
                        <form method="POST" action="{{ route('orders.updateStatus', $order->id_order) }}" class="flex items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="field-select !py-1.5 text-sm">
                                @foreach(['en attente', 'validée', 'expédiée', 'livrée', 'annulée'] as $s)
                                    <option value="{{ $s }}" @selected($order->status === $s)>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn-primary btn-sm">
                                Mettre à jour
                            </button>
                        </form>
                    @else
                        <span class="{{ $statusBadge }} text-sm">{{ ucfirst($order->status) }}</span>
                    @endif
                </div>
            </div>

            <div class="card overflow-hidden">
                <table class="table-modern w-full">
                    <thead>
                        <tr>
                            <th>Article</th>
                            <th>Quantité</th>
                            <th>Prix unitaire</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td class="font-medium text-gray-900 dark:text-gray-100">{{ $item->article->title }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->price, 2, ',', ' ') }} €</td>
                            <td class="font-semibold">{{ number_format($item->price * $item->quantity, 2, ',', ' ') }} €</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="flex justify-end px-4 py-3 border-t border-gray-100 dark:border-gray-700">
                    <p class="text-lg font-extrabold text-indigo-600 dark:text-indigo-400">
                        Total : {{ number_format($order->total, 2, ',', ' ') }} €
                    </p>
                </div>
            </div>

            <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-indigo-600 dark:text-gray-400">
                <x-icon name="arrow-left" class="w-4 h-4" /> Retour aux commandes
            </a>
        </div>
    </div>
</x-app-layout>
